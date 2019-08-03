@extends('admin.layouts.master')
@section('page.name', __('Offer Settings'))
@section('page.body')
    <admin-settings-offer-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('Offer Settings')}}</h3>
                </div>
            </div>
            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in show" id="payment-methods"
                                     aria-labelledby="payment-methods-tab" aria-expanded="true">
                                    @include('admin.settings.offer.partials.tabs.payments')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="offer-tags"
                                     aria-labelledby="offer-tags-tab" aria-expanded="false">
                                    @include('admin.settings.offer.partials.tabs.offer_tags')
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>


            <div class="sidebar-detached sidebar-sticky sidebar-left">
                <div class="sidebar">
                    <div class="bug-list-sidebar-content">
                        <!-- Predefined Views -->
                        <div class="card">
                            <div class="card-head">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Navigation')}}</h4>
                                </div>
                            </div>
                            <div class="card-content">
                                <!-- Groups -->
                                <div class="card-body">

                                    <ul class="nav nav-pills nav-pill-with-active-bordered flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="payment-methods-tab" data-toggle="pill"
                                               href="#payment-methods" role="tab" aria-controls="payment-methods" aria-expanded="true">
                                                <i class="ft-globe"></i> {{__('Payments')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="offer-tags-tab" data-toggle="pill"
                                               href="#offer-tags" role="tab" aria-controls="offer-tags" aria-expanded="false">
                                                <i class="ft-tag"></i> {{__('Offer Tags')}}
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                                <!--/ Groups-->
                            </div>
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </admin-settings-offer-page>
@endsection

@push('data')
    <script type="text/javascript">
        window._tableData = [
            // Offer Tags
            {
                'selector': '#offer-tags-table',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.settings.offer.offer-tags-data')}}',
                        "type": "POST"
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'name'},
                        {
                            data: 'action', orderable: false, searchable: false,
                            createdCell: function (td) {
                                let res = Vue.compile($(td).html());

                                let component = new Vue({
                                    render: res.render,
                                    staticRenderFns: res.staticRenderFns
                                }).$mount();

                                $(td).html(component.$el)
                            }
                        },
                    ]
                }
            },

            // Payment Method Categories
            {
                'selector': '#payment-method-categories-table',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.settings.offer.payment-categories-data')}}',
                        "type": "POST"
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'name'},
                        {
                            data: 'action', orderable: false, searchable: false,
                            createdCell: function (td) {
                                let res = Vue.compile($(td).html());

                                let component = new Vue({
                                    render: res.render,
                                    staticRenderFns: res.staticRenderFns
                                }).$mount();

                                $(td).html(component.$el)
                            }
                        },
                    ]
                }
            },

            // Payment Methods
            {
                'selector': '#payment-methods-table',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.settings.offer.payment-methods-data')}}',
                        "type": "POST"
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'name'},
                        {data: 'time_frame', searchable: false},
                        {data: 'category', searchable: false},
                        {
                            data: 'action', orderable: false, searchable: false,
                            createdCell: function (td) {
                                let res = Vue.compile($(td).html());

                                let component = new Vue({
                                    render: res.render,
                                    staticRenderFns: res.staticRenderFns
                                }).$mount();

                                $(td).html(component.$el)
                            }
                        },
                    ]
                }
            },
        ]
    </script>
@endpush


