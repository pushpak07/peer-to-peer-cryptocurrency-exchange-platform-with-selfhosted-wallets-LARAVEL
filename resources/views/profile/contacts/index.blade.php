@extends('layouts.master')
@section('page.name', __(":name - Contacts", ['name' => $user->name]))
@section('page.body')
    <profile-page inline-template>
        <div class="content-wrapper">

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{strtoupper($user->name) . ' | ' . __('Contacts')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('profile', $user->name) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Users List')}}</h4>
                                        <a class="heading-elements-toggle">
                                            <i class="la la-ellipsis-h font-medium-3"></i>
                                        </a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li>
                                                    <a data-action="reload">
                                                        <i class="ft-rotate-cw"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <!-- Task List table -->
                                        <div class="table-responsive">
                                            <table id="contacts-list" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="all">{{__('Username')}}</th>
                                                    <th>{{__('Last Seen')}}</th>
                                                    <th>{{__('Trust')}}</th>
                                                    <th class="all">{{__('Actions')}}</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th class="all">{{__('Username')}}</th>
                                                    <th>{{__('Last Seen')}}</th>
                                                    <th>{{__('Trust')}}</th>
                                                    <th class="all">{{__('Actions')}}</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="sidebar-detached sidebar-left">
                <div class="sidebar">
                    <div class="bug-list-sidebar-content">
                        <!-- Predefined Views -->
                        <div class="card">
                            <div class="card-head">
                                <div class="media p-1">
                                    <div class="media-left pr-1">
                                        <span class="avatar avatar-sm {{getPresenceClass($user)}} rounded-circle">
                                            <img src="{{getProfileAvatar($user)}}" alt="avatar"><i></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-middle">
                                        <h5 class="media-heading">{{$user->name}}</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Groups -->
                            <div class="card-body border-top-blue-grey border-top-lighten-5">
                                <div class="bug-list-search">
                                    <div class="bug-list-search-content">
                                        <form action="#">
                                            <div class="position-relative">
                                                <input type="search" id="search-table" class="form-control" placeholder="Search contacts...">
                                                <div class="form-control-position">
                                                    <i class="la la-search text-size-base text-muted"></i>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="lead">{{__('By Role')}}</p>
                                <div class="list-group">
                                    <a href="{{route('profile.contacts.index', ['user' => $user->name])}}"
                                       class="list-group-item {{!request()->get('filter')? 'active' : ''}}">
                                        <span class="badge badge-primary badge-pill float-right">
                                            {{$user->contacts()->count()}}
                                        </span>

                                        {{__('All Contacts')}}
                                    </a>

                                    <a href="{{updateUrlQuery(request(), ['filter' => 'trust'])}}"
                                       class="list-group-item {{request()->get('filter') == 'trust'? 'active': ''}}">
                                        <span class="badge badge-success badge-pill float-right">
                                            {{$user->contacts()->where('state', 'trust')->count()}}
                                        </span>

                                        {{__('Trusted Contacts')}}
                                    </a>

                                    <a href="{{updateUrlQuery(request(), ['filter' => 'block'])}}"
                                       class="list-group-item {{request()->get('filter') == 'block'? 'active': ''}}">
                                        <span class="badge badge-danger badge-pill float-right">
                                            {{$user->contacts()->where('state', 'block')->count()}}
                                        </span>

                                        {{__('Blocked Contacts')}}
                                    </a>
                                </div>
                            </div>
                            <!--/ Groups-->
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </profile-page>
@endsection

@push('data')
    <script type="text/javascript">
        let url = new URL(window.location);
        let params = url.searchParams;

        window._tableData = [
            {
                'selector': '#contacts-list',
                'options': {
                    processing: false,
                    serverSide: true,

                    "ajax": {
                        "async": true,
                        "type": "POST",
                        "url": '{{route('profile.contacts.data', ['user' => $user->name])}}',
                        "data": {
                            'filter': params.get('filter')
                        }
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'name'},
                        {data: 'last_seen'},
                        {data: 'trust', searchable: false},
                        {data: 'action', orderable: false, searchable: false}
                    ]
                }
            }
        ];

        window._vueData = {!! json_encode([
            'profile' => [
                'name' => $user->name,
                'lastSeen' => $user->last_seen,
                'presence' => $user->presence,
                'id' => $user->id,
            ],
        ]) !!}
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        function reloadContactsTable() {
            App._reloadDataTable('#contacts-list')
        }
    </script>
@endpush
