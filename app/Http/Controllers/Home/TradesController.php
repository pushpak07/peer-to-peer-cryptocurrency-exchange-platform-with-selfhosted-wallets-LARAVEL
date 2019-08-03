<?php

namespace App\Http\Controllers\Home;

use App\Events\NewMessageAlert;
use App\Events\NewTradeChatMessage;
use App\Events\TradeStatusUpdated;
use App\Models\Offer;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Notifications\Trades\Cancelled;
use App\Notifications\Trades\Completed;
use App\Notifications\Trades\Confirmed;
use App\Notifications\Trades\Disputed;
use App\Notifications\Trades\Rated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use willvincent\Rateable\Rating;
use Yajra\DataTables\Facades\DataTables;

class TradesController extends Controller
{
    /**
     * Show active/successful trades
     *
     * @param string $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($token)
    {
        $trades = Trade::has('user')->has('partner')
            ->where('token', $token)->get()
            ->filter(function ($trade) {
                return $trade->grantAccess(Auth::user());
            });

        if ($trade = $trades->first()) {
            $rating = $trade->partner->ratings()->where([
                'trade_id' => $trade->id,
                'user_id'  => $trade->user->id,
            ])->latest()->first();

            return view('home.trades.index')
                ->with(compact('trade'))
                ->with(compact('rating'));
        } else {
            return abort(404);
        }
    }

    /**
     * Complete trade
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function complete(Request $request, $token)
    {
        if ($request->ajax()) {
            $trades = Trade::has('user')->has('partner')
                ->where('token', $token)->get()
                ->filter(function ($trade) {
                    return $trade->party(Auth::user(), ['seller', 'moderator']);
                });

            if ($trade = $trades->first()) {

                $trade->processTransaction();

                $trade->buyer()->notify(new Completed($trade));

                $message = __('The coin held on escrow has been released to the buyer.');

                broadcast(new TradeStatusUpdated($trade));

                return response($message);

            } else {
                return response(__("The trade could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }


    /**
     * Confirm buyer's payment
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function confirm(Request $request, $token)
    {
        if ($request->ajax()) {
            $trades = Trade::has('user')->has('partner')
                ->where('token', $token)->get()
                ->filter(function ($trade) {
                    return $trade->party(Auth::user(), 'buyer');
                });

            if ($trade = $trades->first()) {
                $trade->confirmed = true;
                $trade->save();

                broadcast(new TradeStatusUpdated($trade));

                $trade->seller()->notify(new Confirmed($trade));

                $message = __('Timer has been stopped! Kindly wait patiently for seller to release coin.');

                return response($message);

            } else {
                return response(__("The trade could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }


    /**
     * Change status to dispute
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function dispute(Request $request, $token)
    {
        if ($request->ajax()) {
            $trades = Trade::where('token', $token)
                ->has('user')
                ->has('partner')->get()->filter(function ($trade) {
                    return $trade->canRaiseDispute(Auth::user());
                });

            if ($trade = $trades->first()) {
                $trade->fill([
                    'status'          => 'dispute',
                    'dispute_by'      => Auth::user()->name,
                    'dispute_comment' => $request->prompt,
                ])->save();

                $message = __('A moderator will attend to your request shortly.');

                broadcast(new TradeStatusUpdated($trade));

                if ($trade->party(Auth::user(), 'buyer')) {
                    $trade->seller()->notify(new Disputed($trade));
                }

                if ($trade->party(Auth::user(), 'seller')){
                    $trade->buyer()->notify(new Disputed($trade));
                }

                return response($message);
            } else {
                return response(__("The trade could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Change status to cancelled
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function cancel(Request $request, $token)
    {
        if ($request->ajax()) {
            $trades = Trade::where('token', $token)
                ->has('user')->has('partner')->get()
                ->filter(function ($trade) {
                    return $trade->party(Auth::user(), 'moderator');
                });

            if ($trade = $trades->first()) {
                $trade->status = 'cancelled';
                $trade->save();

                broadcast(new TradeStatusUpdated($trade));

                $message = __('The trade was cancelled successfully.');

                $trade->buyer()->notify(new Cancelled($trade));
                $trade->seller()->notify(new Cancelled($trade));

                return response($message);

            } else {
                return response(__("The trade could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Send a chat message
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function sendMessage(Request $request, $token)
    {
        $trades = Trade::where('token', $token)
            ->has('user')->has('partner')
            ->whereIn('status', ['active', 'dispute'])
            ->get()->filter(function ($trade) {
                return $trade->grantAccess(Auth::user());
            });

        if ($trade = $trades->first()) {
            $chat = new TradeChat();

            $chat->fill([
                'user_id' => Auth::id(),
                'content' => $request->message
            ]);

            $trade->chats()->save($chat);

            broadcast(new NewTradeChatMessage($trade));
            broadcast(new NewMessageAlert($chat));

            return response('', 200);
        } else {
            return abort(403);
        }
    }


    /**
     * Send a chat message
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadMedia(Request $request, $token)
    {
        $this->validate($request, [
            'files.*' => 'required|max:10000'
        ]);

        $trades = Trade::where('token', $token)
            ->has('user')->has('partner')
            ->whereIn('status', ['active', 'dispute'])
            ->get()->filter(function ($trade) {
                return $trade->grantAccess(Auth::user());
            });

        if ($trade = $trades->first()) {

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $chat = new TradeChat();

                    $name = $file->getClientOriginalName();
                    $file->storeAs('trade/' . $token, $name);

                    $chat->fill([
                        'user_id' => Auth::id(),
                        'type'    => 'media',
                        'content' => route('home.trades.download', [
                            'token' => $token, 'name' => $name
                        ], false),
                    ]);

                    $trade->chats()->save($chat);

                    broadcast(new NewTradeChatMessage($trade));
                    broadcast(new NewMessageAlert($chat));
                }
            } else {
                return response(__('No file was selected!'), 419);
            }

            return response('', 200);
        } else {
            return abort(403);
        }
    }

    /**
     * Download a file from the trade.
     *
     * @param Request $request
     * @param $token
     * @param $name
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Request $request, $token, $name)
    {
        $trades = Trade::where('token', $token)
            ->has('user')->has('partner')
            ->get()->filter(function ($trade) {
                return $trade->grantAccess(Auth::user());
            });

        if ($trade = $trades->first()) {
            return Storage::download('trade/' . $token . '/' . $name);
        } else {
            return abort(404);
        }
    }


    /**
     * Rate current trade
     *
     * @param Request $request
     * @param $token
     * @param $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function rate(Request $request, $token)
    {
        $this->validate($request, [
            'score' => 'required|numeric|min:1'
        ]);

        $trades = Trade::where('token', $token)
            ->has('user')->has('partner')
            ->where('status', 'successful')
            ->get()->filter(function ($trade) {
                return $trade->user->id == Auth::id();
            });

        if ($trade = $trades->first()) {
            $rating = $trade->partner->ratings()->firstOrNew([
                'trade_id' => $trade->id,
                'user_id'  => Auth::id(),
            ]);

            $rating->rating = $request->score;
            $rating->comment = $request->comment;
            $rating->trade_id = $trade->id;
            $rating->user_id = $trade->user->id;

            $trade->partner->ratings()->save($rating);

            $trade->partner->notify(new Rated($trade, $request->score, $request->comment));

            $message = __('Rating has been stored successfully!');

            return success_response($message);
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $trades = Trade::has('user')->has('partner')
                ->where(function ($query) {
                    $query->where('partner_id', '=', Auth::id());
                    $query->orWhere('user_id', '=', Auth::id());
                })
                ->whereIn('status', ['active', 'dispute']);

            return DataTables::eloquent($trades)
                ->editColumn('type', function ($data) {
                    return strtoupper($data->type);
                })
                ->editColumn('status', function ($data) {
                    $status = ucfirst($data->status);

                    $html = "<span class='badge badge-secondary'>{$status}</span>";

                    switch ($data->status) {
                        case 'cancelled':
                            $html = "<span class='badge badge-danger'>{$status}</span>";
                            break;

                        case 'successful':
                            $html = "<span class='badge badge-success'>{$status}</span>";
                            break;

                        case 'dispute':
                            $html = "<span class='badge badge-warning'>{$status}</span>";
                            break;

                        case 'active':
                            $html = "<span class='badge badge-info'>{$status}</span>";
                            break;

                    }

                    return $html;
                })
                ->editColumn('coin', function ($data) {
                    return get_coin($data->coin);
                })
                ->editColumn('amount', function ($data) {
                    return money($data->amount, $data->currency, true);
                })
                ->editColumn('rate', function ($data) {
                    return money($data->rate, $data->currency, true);
                })
                ->addColumn('coin_value', function ($data) {
                    return $data->coinValue() . strtoupper($data->coin);
                })
                ->addColumn('buyer', function ($data) {
                    return view('home.trades.partials.datatable.buyer')
                        ->with(compact('data'));
                })
                ->addColumn('seller', function ($data) {
                    return view('home.trades.partials.datatable.seller')
                        ->with(compact('data'));
                })
                ->addColumn('trade', function ($data) {
                    return \HTML::link(route('home.trades.index', [
                        'token' => $data->token
                    ]), $data->token);
                })
                ->addColumn('offer', function ($data) {
                    if ($offer = $data->offer) {
                        return \HTML::link(route('home.offers.index', [
                            'token' => $offer->token
                        ]), $offer->token);
                    }
                })
                ->removeColumn('dispute_by', 'dispute_comment')
                ->rawColumns(['status', 'buyer', 'seller'])
                ->make(true);
        } else {
            return abort(404);
        }

    }
}
