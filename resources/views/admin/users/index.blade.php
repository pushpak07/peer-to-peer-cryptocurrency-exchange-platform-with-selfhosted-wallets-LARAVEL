@extends('admin.layouts.master')
@section('page.name', __('Users'))
@section('page.body')
    <admin-users-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('Users')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.users') }}
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
                                                    @if(!$show_trashed)
                                                        <a href="{{updateUrlQuery(request(), ['deleted' => 'true'])}}"
                                                           class="btn btn-red btn-sm">
                                                            <i class="ft-trash white"></i>
                                                            {{__('Trashed')}}
                                                        </a>
                                                    @else
                                                        <a href="{{updateUrlQuery(request(), ['deleted' => 'false'])}}"
                                                           class="btn btn-primary btn-sm">
                                                            <i class="ft-user-check white"></i>
                                                            {{__('Users')}}
                                                        </a>
                                                    @endif
                                                </li>
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
                                            <table id="users-list" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="all">{{__('Username')}}</th>
                                                    <th>{{__('Email')}}</th>
                                                    <th>{{__('Phone')}}</th>
                                                    <th>{{__('Role')}}</th>
                                                    <th>{{__('Status')}}</th>
                                                    <th class="all">{{__('Actions')}}</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>{{__('Username')}}</th>
                                                    <th>{{__('Email')}}</th>
                                                    <th>{{__('Phone')}}</th>
                                                    <th>{{__('Role')}}</th>
                                                    <th>{{__('Status')}}</th>
                                                    <th>{{__('Actions')}}</th>
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
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Filter')}}</h4>
                                    <a class="heading-elements-toggle">
                                        <i class="la la-ellipsis-h font-medium-3"></i>
                                    </a>
                                    <div class="heading-elements">
                                        <a href="{{route('admin.users.index')}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="ft-filter white"></i>
                                            {{__('Clear')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <!-- Groups -->
                                <div class="card-body">
                                    <p class="lead">{{__('By Role')}}</p>
                                    <div class="list-group">

                                        @foreach($roles as $role)
                                            <a href="{{updateUrlQuery(request(), ['role' => $role->name])}}"
                                               class="list-group-item {{request()->get('role') == $role->name? 'active': ''}}">
                                                <span class="badge badge-primary badge-pill float-right">
                                                    {{$role->users_count}}
                                                </span>

                                                {{removeSnakeCase($role->name)}}
                                            </a>
                                        @endforeach

                                    </div>
                                </div>
                                <!--/ Groups-->

                            @if(count($users_count))
                                <!-- Groups-->
                                    <div class="card-body">
                                        <p class="lead">{{__('By Status')}}</p>
                                        <div class="list-group">

                                            @foreach($users_count as $count)
                                                <a href="{{updateUrlQuery(request(), ['status' => $count->status])}}"
                                                   class="list-group-item {{request()->get('status') == $count->status? 'active': ''}}">
                                                    <span class="badge badge-primary badge-pill float-right">
                                                        {{$count->total}}
                                                    </span>

                                                    {{removeSnakeCase($count->status)}}
                                                </a>
                                            @endforeach

                                        </div>
                                    </div>
                                    <!--/ Groups-->
                                @endif
                            </div>
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </admin-users-page>
@endsection

@push('data')
    <script type="text/javascript">
        let url = new URL(window.location);
        let params = url.searchParams;

        window._tableData = [
            {
                'selector': '#users-list',
                'options': {
                    processing: false,
                    serverSide: true,

                    "ajax": {
                        "async": true,
                        "type": "POST",
                        "url": '{{route('admin.users.data')}}',
                        "data": {
                            'deleted': params.get('deleted'),
                            'role': params.get('role'),
                            'status': params.get('status')
                        }
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'phone'},
                        {data: 'role', searchable: false, orderable: false},
                        {data: 'status', searchable: false, orderable: false},
                        {data: 'action', orderable: false}
                    ]
                }
            }
        ]
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        function reloadUsersTable() {
            App._reloadDataTable('#users-list')
        }
    </script>
@endpush
