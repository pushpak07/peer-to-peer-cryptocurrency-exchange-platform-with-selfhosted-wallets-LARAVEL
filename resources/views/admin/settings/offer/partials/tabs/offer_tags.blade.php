<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Offer Tags')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">
                <table id="offer-tags-table" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
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
                <button @click="resetOfferTag()" data-toggle="modal"
                        data-target="#offer-tag-form" class="btn btn-primary">
                    {{__('NEW OFFER TAG')}}
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade text-left" id="offer-tag-form" tabindex="-1" role="dialog" aria-labelledby="offer-tag-form-label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        {!! Form::open(['url' => route('admin.settings.offer.store-offer-tag'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="offer-tag-form-label">
                    <i class="la la-tags"></i> {{__('New Offer Tag')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-body">
                    <span v-if="Boolean(form.offerTag.id)">
                        {!! Form::hidden('id', null, ['v-model' => 'form.offerTag.id']) !!}
                    </span>

                    <div class="form-group row">
                        <label class="col-md-4">{{__('Name')}}</label>
                        <div class="col-md-8">
                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'form.offerTag.name', 'placeholder' => __('Enter a tag name...')]) !!}
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
