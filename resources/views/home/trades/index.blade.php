@extends('layouts.master')
@section('page.name', get_trade_title($trade))
@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/chat.css')}}">
@endpush
@section('page.body')
    <home-trades-page inline-template>
        <div class="content-wrapper">

            <div class="content-body">
                <section class="chat-app-window" id="chat">
                    <dropzone @drop="uploadMedia" form-field-name="files[]"></dropzone>

                    <div class="chats">
                        <div class="card box-shadow-2">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h1 class="card-title font-medium-5 text-uppercase">
                                                {{__('Trade Status:')}}

                                                <span v-if="status === 'active'">
                                                    <span class="blue darken-4 text-bold-500" v-text="status"></span>
                                                </span>

                                                <span v-if="status === 'successful'">
                                                    <span class="success darken-4 text-bold-500" v-text="status"></span>
                                                </span>

                                                <span v-if="status === 'dispute'">
                                                    <span class="warning darken-4 text-bold-500" v-text="status"></span>
                                                </span>

                                                <span v-if="status === 'cancelled'">
                                                    <span class="danger darken-4 text-bold-500" v-text="status"></span>
                                                </span>
                                            </h1>
                                        </div>
                                    </div>

                                    <div class="row text-left">
                                        <div class="col-xl-6">
                                            <div class="row text-center">
                                                <div class="col p-1 px-md-2">
                                                    @if($trade->type == 'buy')
                                                        <h4 class="card-title text-center text-bold-500 darken-4 success">{{__('BUYER')}}</h4>
                                                    @else
                                                        <h4 class="card-title text-center text-bold-500 darken-4 danger">{{__('SELLER')}}</h4>
                                                    @endif

                                                    <user-tag :id="{{$trade->user->id}}" name="{{$trade->user->name}}" avatar="{{getProfileAvatar($trade->user)}}"
                                                              presence="{{$trade->user->presence}}" last-seen="{{$trade->user->last_seen}}" :rating="{{$trade->user->averageRating() ?? 0}}"
                                                              country-code="{{$trade->user->getCountryCode()}}">
                                                    </user-tag>
                                                </div>
                                                <div class="col p-1 px-md-2">
                                                    @if($trade->type == 'buy')
                                                        <h4 class="card-title text-center text-bold-500 darken-4 danger">{{__('SELLER')}}</h4>
                                                    @else
                                                        <h4 class="card-title text-center text-bold-500 darken-4 success">{{__('BUYER')}}</h4>
                                                    @endif

                                                    <user-tag :id="{{$trade->partner->id}}" name="{{$trade->partner->name}}" avatar="{{getProfileAvatar($trade->partner)}}"
                                                              presence="{{$trade->partner->presence}}" last-seen="{{$trade->partner->last_seen}}" :rating="{{$trade->partner->averageRating() ?? 0}}"
                                                              country-code="{{$trade->partner->getCountryCode()}}">
                                                    </user-tag>
                                                </div>
                                            </div>

                                            <div class="row  text-center">
                                                <div class="col">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="media align-items-stretch">
                                                                <div class="p-2 text-center bg-info rounded-left">
                                                                    <i class="icon-calculator font-large-2 text-white"></i>
                                                                </div>
                                                                <div class="p-2 media-body">
                                                                    <h5>{{__('RATE')}}</h5>
                                                                    <h5 class="text-bold-400 mb-0">{{$trade->rate()}}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="media align-items-stretch">
                                                                <div class="p-2 text-center bg-warning rounded-left">
                                                                    <i class="icon-wallet font-large-2 text-white"></i>
                                                                </div>
                                                                <div class="p-2 media-body">
                                                                    <h5>{{__('VALUE')}}</h5>
                                                                    <h5 class="text-bold-400 mb-0">
                                                                        {{$trade->coinValue() . strtoupper($trade->coin)}}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="card-text text-center">
                                                <span class="badge badge-success">{{__('Note:')}}</span> {{__('The above value of :coin is held safely by our escrow service.', ['coin' => get_coin($trade->coin)])}}
                                            </p>
                                        </div>

                                        <div class="col-xl-6  pt-1">
                                            <div class="alert alert-light mb-2" role="alert">
                                                @if($trade->type == 'buy')
                                                    <h4 class="alert-heading mb-1"><b>{{__('Seller Instruction!')}}</b>
                                                    </h4>
                                                @else
                                                    <h4 class="alert-heading mb-1"><b>{{__('Buyer Instruction!')}}</b>
                                                    </h4>
                                                @endif

                                                {!! nl2br(e($trade->instruction)) !!}
                                            </div>

                                            <div v-if="status === 'active'">
                                                <div class="bs-callout-warning callout-transparent callout-border-left p-1 mb-1" role="alert">
                                                    <h4 class="warning">{{__('Important!')}}</h4>

                                                    @if($trade->party(Auth::user(), 'buyer'))
                                                        <span>
                                                            {{__('After making payment, you should click CONFIRM PAYMENT button to stop the counter. After making payment and you presume this as a scam attempt, you should click the RAISE DISPUTE button to call the attention of a moderator.')}}
                                                        </span>
                                                    @elseif($trade->party(Auth::user(), 'seller'))
                                                        <span>
                                                            {{__('After verifying payment, you should click RELEASE COIN button to complete the trade. If you do not receive payment and you suspect this as a scam attempt, you should click the RAISE DISPUTE button to call the attention of a moderator.')}}
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>

                                            <div v-else-if="status === 'dispute'">
                                                <div class="bs-callout-danger callout-transparent text-left callout-border-left p-1 mb-1" role="alert">
                                                    @if(!$trade->party(Auth::user(), 'moderator'))
                                                        <h4 class="danger"> {{__('Attention!')}} </h4>
                                                        {{__('A dispute has been raised over this trade! Investigation will be conducted based on the previous chats as well as uploaded proof of payment. Any decision taking by our moderator should be considered as final.')}}
                                                    @else
                                                        <h4 class="alert-heading mb-1">
                                                            {{__('Dispute By')}}
                                                            <span v-text="dispute_by"></span>
                                                        </h4>

                                                        <span v-text="dispute_comment"></span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($trade->party(Auth::user(), ['buyer', 'seller']))
                                                <div v-else-if="status === 'successful'">
                                                    <div class="bs-callout-success  callout-transparent text-left callout-border-left p-1 mb-1" role="alert">
                                                        <h4 class="success"> {{__('Successful!')}} </h4>
                                                        @if($trade->party(Auth::user(), 'buyer'))
                                                            {{__('Seller has confirmed your payment and released the coin into your wallet address!')}}
                                                        @elseif($trade->party(Auth::user(), 'seller'))
                                                            {{__('The coin value of this transaction has been released into the wallet of the buyer.')}}
                                                            <br/>

                                                            @if($trade->shouldDeductFee())
                                                                @php $fee = $trade->calcFee(); @endphp
                                                                {!! __('The standard percentage fee of :fee has been charged as well for this trade.', ['fee' => "<b>{$fee}</b>"]) !!}
                                                            @endif

                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($trade->party(Auth::user(), 'buyer'))
                                                <h4 class="card-title text-bold-500 text-center">
                                                    {{__('Pay :amount With :payment_method', ['amount' => $trade->amount(), 'payment_method' => $trade->payment_method])}}
                                                </h4>
                                            @else
                                                <h4 class="card-title text-bold-500 text-center">
                                                    {{__('Buyer will pay :amount With :payment_method', ['amount' => $trade->amount(), 'payment_method' => $trade->payment_method])}}
                                                </h4>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg">
                                            <div v-if="!parseInt(confirmed)">
                                                @if($trade->party(Auth::user(), 'buyer'))
                                                    <h6>{{__('Time left to complete payment:')}}</h6>
                                                @else
                                                    <h6>{{__('Time left for buyer to complete payment:')}}</h6>
                                                @endif

                                                <count-down deadline="{{$trade->created_at->addMinutes($trade->deadline)->format('Y-m-d H:i:s')}}"></count-down>
                                            </div>

                                            <div v-else-if="status === 'active'">
                                                @if($trade->party(Auth::user(), 'buyer'))
                                                    <h5>{{__('Timer has been stopped! You need to wait patiently for seller to release coin.')}}</h5>
                                                @elseif($trade->party(Auth::user(), 'seller'))
                                                    <h5>{{__('Payment has been confirmed by buyer! Ensure that you have received your full payment before you proceed to release coin.')}}</h5>
                                                @else
                                                    <h5>{{__('Payment has been confirmed by buyer!')}}</h5>
                                                @endif
                                            </div>

                                            <div v-if="status === 'active' || status === 'dispute'">
                                                <div class="row pt-1">
                                                    <div class="col-12">
                                                        @if($trade->party(Auth::user(), 'buyer'))
                                                            <span v-if="!parseInt(confirmed)">
                                                                <a href="{{route('home.trades.confirm', ['token' => $trade->token])}}" data-swal="confirm-ajax"
                                                                   class="btn btn-success btn-sm box-shadow-1 round mb-1" data-icon="success" data-ajax-type="POST"
                                                                   data-text="{{__("You should upload the proof of payment, just in case the seller raises a dispute!")}}">
                                                                    <i class="la la-check-circle"></i> {{__('CONFIRM PAYMENT')}}
                                                                </a>
                                                            </span>
                                                        @endif

                                                        <span v-if="parseInt(confirmed)">
                                                            @if($trade->party(Auth::user(), ['moderator', 'seller']))
                                                                <a href="{{route('home.trades.complete', ['token' => $trade->token])}}" data-swal="confirm-ajax"
                                                                   class="btn btn-success btn-sm box-shadow-1 round mb-1" data-icon="warning" data-ajax-type="POST"
                                                                   data-text="{{__("The coin held on escrow will be released to buyer. This cannot be undone!")}}">
                                                                    <i class="la la-check-circle"></i> {{__('RELEASE COIN')}}
                                                                </a>
                                                            @endif

                                                            @if($trade->party(Auth::user(), 'moderator'))
                                                                <a href="{{route('home.trades.cancel', ['token' => $trade->token])}}" data-swal="confirm-ajax"
                                                                   class="btn btn-secondary btn-sm box-shadow-1 round mb-1" data-icon="warning" data-ajax-type="POST"
                                                                   data-text="{{__("The coin held on escrow will be returned back to the seller!")}}">
                                                                    <i class="la la-ban"></i> {{__('CANCEL TRADE')}}
                                                                </a>
                                                            @endif

                                                            @if($trade->canRaiseDispute(Auth::user()))
                                                                <span v-if="status !== 'dispute'">
                                                                    <a href="{{route('home.trades.dispute', ['token' => $trade->token])}}" data-swal="prompt-ajax"
                                                                       class="btn btn-danger btn-sm box-shadow-1 round mb-1" data-icon="warning" data-ajax-type="POST"
                                                                       data-text="{{__("This trade will be brought to the notice of our moderators!")}}">
                                                                        <i class="la la-flag"></i> {{__('RAISE DISPUTE')}}
                                                                    </a>
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="status !== 'successful'">
                            <div v-for="(group, date) in chats">
                                <p class="time" v-text="formatDate(date)"></p>

                                <div v-for="chat in group">
                                    <div v-if="chat.user.id !== auth_user_id">
                                        <div class="chat chat-left mb-2">
                                            <div class="chat-avatar">
                                                <a class="avatar" data-toggle="tooltip" href="#" data-placement="left">
                                                    <img :src="getProfileAvatar(chat.user)" :alt="chat.user.name"/>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content" v-for="message in chat.content">
                                                    <p>
                                                        <a :href="getProfileLink(chat.user)">
                                                            <small class="text-bold-500" v-text="chat.user.name"></small>
                                                        </a>
                                                        <br/>
                                                        <span v-html="displayContent(message.content, message.type)"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else>
                                        <div class="chat mb-2">
                                            <div class="chat-avatar">
                                                <a class="avatar" data-toggle="tooltip" href="#" data-placement="right">
                                                    <img :src="getProfileAvatar(chat.user)" :alt="chat.user.name"/>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content" v-for="message in chat.content">
                                                    <p v-html="displayContent(message.content, message.type)"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            @if($trade->user->id == Auth::id())
                                <div class="media-list media-bordered">
                                    <div class="media">
                                        <h4 class="media-head text-uppercase">
                                            {{__('Leave a Rating')}}
                                        </h4>
                                    </div>
                                    {!! Form::open(['url' => route('home.trades.rate', ['token' => $trade->token]), 'class' => 'form form-horizontal']) !!}
                                    <div class="media">
                                        <a class="media-left" href="#">
                                            <img class="media-object rounded-circle" src="{{getProfileAvatar($trade->user)}}"
                                                 alt="{{$trade->user->name}}" style="width: 50px;height: 50px;"/>
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <rating :score="{{$rating->rating ?? 0}}" size="md" :read-only="false"></rating>
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-10 offset-md-1">
                                                    {!! Form::textarea('comment', $rating->comment ?? null, ['placeholder' => __('Write a comment...'), 'class' => 'form-control m-1', 'rows' => 4]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions right">
                                        <button type="submit" class="btn btn-primary">
                                            {{__('Submit')}}
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            @else
                                <div class="media-list media-bordered">
                                    <div class="media">
                                        <h4 class="media-head text-uppercase">
                                            {{__('Trade Rating')}}
                                        </h4>
                                    </div>
                                    <div class="media">
                                        <a class="media-left" href="#">
                                            <img class="media-object rounded-circle" src="{{getProfileAvatar($trade->user)}}"
                                                 alt="{{$trade->user->name}}" style="width: 70px;height: 70px;"/>
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <rating :score="{{$rating->rating ?? 0}}" size="md"></rating>
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-10 offset-md-1">
                                                    {{$rating->comment ?? __('No Rating Yet!')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </section>

                <section class="chat-app-form">
                    <form class="chat-app-input d-flex">
                        <fieldset class="form-group position-relative has-icon-left col-9 col-sm-10 m-0">
                            <div class="form-control-position" @click.prevent="$refs.fileSelect.click()">
                                <i class="ft-image"></i></div>
                            {!! Form::text('message', null, ['class' => 'form-control', 'placeholder' => __('Type your message'), '@keydown' => 'typing', 'v-model' => 'text', ':disabled' => "status !== 'active' && status !== 'dispute'"]) !!}
                            <input ref="fileSelect" type="file" name="files[]" style="display: none;" multiple @change="selectFiles"/>
                        </fieldset>

                        <fieldset class="form-group position-relative col-3 col-sm-2 m-0">
                            <button type="button" class="btn btn-info round ladda-button" @click.prevent="sendMessage"
                                    id="submit" :disabled="status !== 'active' && status !== 'dispute'">
                                <i class="la la-paper-plane-o d-lg-none"></i>

                                <span class="d-none d-lg-block">{{__('Send')}}</span>
                            </button>
                        </fieldset>
                    </form>
                </section>
            </div>
        </div>
    </home-trades-page>
@endsection

@push('data')
    <script type="text/javascript">
        window._vueData = {!! json_encode([
                'chats' => $trade->chatsByDate(),
                'status' => $trade->status,
                'dispute_by' => $trade->dispute_by,
                'dispute_comment' => $trade->dispute_comment,
                'confirmed' => $trade->confirmed,
                'token' => $trade->token,
                'trade' => $trade,
                'auth_user_id' => Auth::id(),
            ]) !!}
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        let footer, i;

        // Set chat class
        document.body.classList.add('chat-application');
        footer = document.getElementsByTagName('footer');

        // Remove Footer
        for (i = 0; i < footer.length; i++) {
            footer[i].style.display = 'none';
        }
    </script>
@endpush

