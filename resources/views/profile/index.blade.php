@extends('layouts.master')
@section('page.name', __(":name - Profile", ['name' => $user->name]))
@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/profile.css')}}">

    <style rel="stylesheet">
        .slide-fade-enter {
            transform: translateY(100px);
            opacity: 0;
        }

        .slide-fade-enter-active {
            transition: all .3s ease;
        }

        div.rating-list {
            height: 300px;
            position: relative;
        }
    </style>
@endpush
@section('page.body')
    <profile-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{strtoupper($user->name) . ' | ' . __('Profile')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('profile', $user->name) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            @include('profile.includes.profile_cover')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border-top-blue">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Verification')}}</h4>
                                        <a class="heading-elements-toggle">
                                            <i class="la la-ellipsis-v font-medium-3"></i>
                                        </a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse show">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                @if($user->verified_phone)
                                                    <span class="la la-check text-success float-right"></span>
                                                @else
                                                    <span class="la la-close text-danger float-right"></span>
                                                @endif
                                                {{__('Phone')}}
                                            </li>
                                            <li class="list-group-item">
                                                @if($user->verified)
                                                    <span class="la la-check text-success float-right"></span>
                                                @else
                                                    <span class="la la-close text-danger float-right"></span>
                                                @endif
                                                {{__('Email')}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card border-top-blue">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Information')}}</h4>
                                        <a class="heading-elements-toggle">
                                            <i class="la la-ellipsis-v font-medium-3"></i>
                                        </a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse show">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <rating :score="{{$user->averageRating() ?? 0}}" size="md"></rating>

                                                <span class="float-right">
                                                    ({{$user->ratings()->count()}} {{__('ratings')}})
                                                </span>
                                            </li>

                                            <li class="list-group-item">
                                                <span class="float-right">
                                                    {{$user->created_at->diffForHumans()}}
                                                </span>
                                                {{__('Registered on')}}
                                            </li>
                                            <li class="list-group-item">
                                                <span class="float-right" v-text="lastSeenPresence"></span>
                                                {{__('Last seen on')}}
                                            </li>
                                            <li class="list-group-item">
                                                <span class="badge badge-pill badge-success float-right">
                                                    {{$user->countSuccessfulTrades()}}
                                                </span>
                                                {{__('Successful Trades')}}
                                            </li>
                                            <li class="list-group-item">
                                                <span class="badge badge-pill badge-primary float-right">
                                                    {{DB::table('user_contact')->where('contact_id', $user->id)->where('state', 'trust')->count()}}
                                                </span>
                                                {{__('Trusted by')}}
                                            </li>
                                            <li class="list-group-item">
                                                <span class="badge badge-pill badge-danger float-right">
                                                    {{DB::table('user_contact')->where('contact_id', $user->id)->where('state', 'block')->count()}}
                                                </span>
                                                {{__('Blocked by')}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card border-top-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Offers')}}</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="my-offers" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="all">{{__('Coin')}}</th>
                                                        <th>{{__('Pay with')}}</th>
                                                        <th>{{__('Currency')}}</th>
                                                        <th class="all">{{__('Amount Range')}}</th>
                                                        <th class="all">{{__('Worth')}}</th>
                                                        <th>{{__('Coin Rate')}}</th>
                                                        <th class="all">{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>{{__('Coin')}}</th>
                                                        <th>{{__('Pay with')}}</th>
                                                        <th>{{__('Currency')}}</th>
                                                        <th>{{__('Amount Range')}}</th>
                                                        <th>{{__('Worth')}}</th>
                                                        <th>{{__('Coin Rate')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-top-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Reviews')}}</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="media-list media-bordered">
                                            <div class="rating-list" ref="ratingScrollWrapper" infinite-wrapper>
                                                <transition-group tag="div" name="slide-fade">
                                                    <div class="media" v-for="(rating, id) in ratings.data" :key="rating.id">
                                                        <div class="media">
                                                            <span class="media-left">
                                                                <img class="media-object rounded-circle" :src="getProfileAvatar(rating.user)"
                                                                     :alt="rating.user.name" style="width: 64px;height: 64px;"/>
                                                            </span>
                                                            <div class="media-body">
                                                                <h5 class="media-heading">
                                                                    <a :href="'/profile/'+rating.user.name">
                                                                        <b>@{{ rating.user.name }}</b>
                                                                    </a>
                                                                    {{__('wrote:')}}
                                                                </h5>
                                                                @{{rating.comment}}
                                                                <div class="media-notation mt-1 no-wrap">
                                                                    @{{ dateDiffForHumans(rating.created_at) }} |
                                                                    <rating :score="rating.rating"></rating>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </transition-group>

                                                <infinite-loading @infinite="ratingInfiniteHandler" ref="ratingInfiniteLoading">
                                                    <h3 slot="no-more" class="text-center">{{__('No more results available!')}}</h3>
                                                </infinite-loading>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </profile-page>

@endsection

@push('data')
    <script type="text/javascript">
        window._tableData = [
            // My Offers
            {
                'selector': '#my-offers',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('profile.offers-data', ['user' => $user->name])}}',
                        "type": "POST",
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'coin'},
                        {data: 'payment_method', orderable: false},
                        {data: 'currency'},
                        {data: 'amount_range', orderable: false, searchable: false},
                        {data: 'worth', searchable: false},
                        {data: 'coin_rate', searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ]
                }
            }

        ]
    </script>
@endpush
