<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Methods')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">
                <table id="payment-methods-table" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="all">{{__('Name')}}</th>
                        <th class="all">{{__('Time Frame (min)')}}</th>
                        <th class="all">{{__('Category')}}</th>
                        <th class="all">{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Time Frame (min)')}}</th>
                        <th>{{__('Category')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer text-right">
            <button @click="resetPaymentMethod()" data-toggle="modal"
                    data-target="#payment-method-form" class="btn btn-primary">
                {{__('NEW METHOD')}}
            </button>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="payment-method-form" tabindex="-1" role="dialog" aria-labelledby="payment-method-form-label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        {!! Form::open(['url' => route('admin.settings.offer.store-payment-method'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="payment-method-form-label">
                    <i class="la la-credit-card"></i> {{__('New Method')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-body">
                    <span v-if="Boolean(form.paymentMethod.id)">
                        {!! Form::hidden('id', null, ['v-model' => 'form.paymentMethod.id']) !!}
                    </span>

                    <div class="form-group row">
                        <label class="col-md-4">{{__('Name')}}</label>
                        <div class="col-md-8">
                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'form.paymentMethod.name', 'placeholder' => __('Enter a method name...')]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4">{{__('Time Frame (min)')}}</label>
                        <div class="col-md-8">
                            {!! Form::text('time_frame', null, ['class' => 'form-control', 'v-model' => 'form.paymentMethod.timeFrame', 'placeholder' => __('Enter a payment verification time frame...')]) !!}
                            <small class="help-block">
                                {{__('The time frame required to verify payment on a trade.')}}
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4">{{__('Category')}}</label>
                        <div class="col-md-8">
                            {!! Form::select('category', $categories, null, ['class' => 'form-control', 'is' => 'select2', 'v-model' => 'form.paymentMethod.category', 'placeholder' => __('Select a category...')]) !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">
                    {{__('Close')}}
                </button>
                <button type="submit" class="btn btn-success">
                    {{__('Submit')}}
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Categories')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">
                <table id="payment-method-categories-table" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="all">{{__('Name')}}</th>
                        <th class="all">{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-footer text-right">
                <button @click="resetPaymentCategory()" data-toggle="modal"
                        data-target="#payment-method-category-form" class="btn btn-primary">
                    {{__('NEW CATEGORY')}}
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade text-left" id="payment-method-category-form" tabindex="-1" role="dialog" aria-labelledby="payment-method-category-form-label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        {!! Form::open(['url' => route('admin.settings.offer.store-payment-category'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="payment-method-category-form-label">
                    <i class="la la-globe"></i> {{__('New Category')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-body">
                    <span v-if="Boolean(form.paymentCategory.id)">
                        {!! Form::hidden('id', null, ['v-model' => 'form.paymentCategory.id']) !!}
                    </span>

                    <div class="form-group row">
                        <label class="col-md-4">{{__('Name')}}</label>
                        <div class="col-md-8">
                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'form.paymentCategory.name', 'placeholder' => __('Enter a category name...')]) !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">
                    {{__('Close')}}
                </button>
                <button type="submit" class="btn btn-success">
                    {{__('Submit')}}
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
