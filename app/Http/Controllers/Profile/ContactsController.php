<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ContactsController extends Controller
{
    /**
     * Contacts index page
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        return view('profile.contacts.index')
            ->with(compact('contacts_count'))
            ->with(compact('user'));
    }

    /**
     * Add contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request, User $user)
    {
        if ($request->ajax()) {
            if ($contact = User::where('name', $request->name)->first()) {

                $user->contacts()->attach($contact->id);

                return response(__("Your contact list have been updated!"));
            } else {
                return response(__("The specified contacts could not be found!"), 404);
            }
        } else {
            return abort(403);
        }
    }

    /**
     * Delete contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request, User $user)
    {
        if ($request->ajax()) {

            $contact = $user->contacts()->where('name', $request->name)->first();

            if ($contact) {

                $user->contacts()->detach($contact->id);

                return response(__("The contact has been deleted!"));
            } else {
                return response(__("The specified contact could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Trust contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function trust(Request $request, User $user)
    {
        if ($request->ajax()) {

            $contact = $user->contacts()->where('name', $request->name)->first();

            if ($contact) {
                $contact->pivot->state = 'trust';
                $contact->pivot->save();

                return response(__("The contact has been added to your trusted list!"));
            } else {
                return response(__("The specified contact could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Untrust contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function untrust(Request $request, User $user)
    {
        if ($request->ajax()) {

            $contact = $user->contacts()->where('name', $request->name)->first();

            if ($contact) {
                $contact->pivot->state = null;
                $contact->pivot->save();

                return response(__("The contact has been removed from your trusted list!"));
            } else {
                return response(__("The specified contact could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Block contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function block(Request $request, User $user)
    {
        if ($request->ajax()) {

            $contact = $user->contacts()->where('name', $request->name)->first();

            if ($contact) {
                $contact->pivot->state = 'block';
                $contact->pivot->save();

                return response(__("The contact has been added to your blocked list!"));
            } else {
                return response(__("The specified contact could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Unblock contact
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function unblock(Request $request, User $user)
    {
        if ($request->ajax()) {

            $contact = $user->contacts()->where('name', $request->name)->first();

            if ($contact) {
                $contact->pivot->state = null;
                $contact->pivot->save();

                return response(__("The contact has been removed from your blocked list!"));
            } else {
                return response(__("The specified contact could not be found!"), 404);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws \Exception
     */
    public function data(Request $request, User $user)
    {
        if ($request->ajax()) {
            $contacts = $user->contacts();

            if ($request->filter) {
                $contacts = $contacts->wherePivot('state', $request->filter);
            }

            $contacts = $contacts->get();

            return DataTables::of($contacts)
                ->addColumn('trust', function ($data) {
                    $state = $data->pivot->state;

                    if ($state == 'trust') {
                        $html = '<i class="la la-star blue darken-2"></i></td>';
                    } else {
                        $html = '<i class="la la-star-o blue darken-2"></i></td>';
                    }

                    return $html;
                })
                ->editColumn('name', function ($data) {
                    return view('profile.contacts.partials.datatable.name')
                        ->with(compact('data'));
                })
                ->editColumn('last_seen', function ($data) {
                    if ($data->last_seen) {
                        return Carbon::parse($data->last_seen)
                            ->diffForHumans();
                    }
                })
                ->addColumn('action', function ($data) use ($user) {
                    return view('profile.contacts.partials.datatable.action')
                        ->with(compact('data', 'user'));
                })
                ->rawColumns(['action', 'trust', 'name'])
                ->make(true);

        } else {
            return abort(403);
        }
    }
}
