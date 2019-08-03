@extends('layouts.master')
@section('page.name', __(":name - Notifications", ['name' => $user->name]))

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

        div.notification-list {
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
                    <h3 class="content-header-title">{{strtoupper($user->name) . ' | ' . __('Notifications')}}</h3>
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
                            <div class="col-12">
                                <div class="card border-top-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Notifications')}}</h4>
                                        <a class="heading-elements-toggle">
                                            <i class="la la-ellipsis-h font-medium-3"></i>
                                        </a>
                                        <div class="heading-elements">
                                            <a href="{{route('profile.notifications.markAllAsRead', ['user' => $user->name])}}"
                                               onclick="event.preventDefault(); document.getElementById('mark-all-as-read').submit();"
                                               class="btn btn-warning btn-sm">
                                                <i class="ft-check-square white"></i> {{__('Mark all as read')}}
                                            </a>

                                            <form action="{{route('profile.notifications.markAllAsRead', ['user' => $user->name])}}"
                                                  method="POST" style="display: none;" id="mark-all-as-read">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card-content">
                                        <div class="media-list media-bordered">
                                            <div class="notification-list" ref="notificationsScrollWrapper" infinite-wrapper>
                                                <transition-group tag="div" name="slide-fade">
                                                    <div v-for="(notification, id) in notifications.data" :key="notification.id">
                                                        <a :href="notification.data.link" @click.prevent="markAsRead(notification.id, $event)">
                                                            <div class="media">
                                                                <span class="media-left pt-1">
                                                                    <i :class="notification.data.icon_class"></i>
                                                                </span>
                                                                <div class="media-body">

                                                                    <h5 class="media-heading">
                                                                        <span class="float-right text-center">
                                                                            <time class="media-meta text-muted" :datetime="notification.created_at"
                                                                                  v-text="dateDiffForHumans(notification.created_at)">
                                                                            </time>
                                                                            <br/>
                                                                            <span v-if="notification.read_at">
                                                                                <i class="la la-star-o blue mt-1"></i>
                                                                            </span>
                                                                            <span v-else>
                                                                                <i class="la la-star blue mt-1"></i>
                                                                            </span>
                                                                        </span>
                                                                    </h5>

                                                                    <p class="text-muted">
                                                                        @{{notification.data.message}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </transition-group>

                                                <infinite-loading @infinite="notificationsInfiniteHandler" ref="notificationsInfiniteLoading">
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
