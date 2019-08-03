@extends('layouts.master')
@section('page.name', __('Edit Offer'))
@section('page.body')
	<home-offers-edit-page inline-template>
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-12 mb-2">
					<h3 class="content-header-title"> {{__('Edit Offer (#:token)', ['token' => $offer->token])}} </h3>
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							{{ Breadcrumbs::render(($offer->type == 'sell') ? 'edit_buy_offer': 'edit_sell_offer', $offer->token) }}
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">
								<i class="la la-pencil"></i> {{__('Edit Offer')}}
							</h4>
						</div>
						
						<div class="card-content">
							<div class="card-body">
								{!! Form::model($offer, ['url' => route('home.offers.update', ['token' => $offer->token]), 'class' => 'form form-horizontal']) !!}
								<div class="form-body">
									<div class="row">
										<div class="col-md-6">
											<h4 class="form-section">
												<i class="la la-dollar"></i> {{__('Select Wallet')}} </h4>
											
											<div class="form-group">
												{!! Form::label('coin', __('Select Wallet')) !!}
												{!! Form::select('coin', get_coins(), null, ['is' => 'select2', 'html-class' => 'form-control', 'required', 'v-model' => 'coin']) !!}
											</div>
											
											<div class="bs-callout-info callout-border-left mt-1 p-1">
												<strong>{{__('INFORMATION')}}</strong>
												<p class="card-text">
													{{__('You are able to set several ways to pay for coins, if you are unable to find a preferred payment method, please contact our support center and we will gladly review your request.')}}
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<h4 class="form-section">
												<i class="la la-money"></i> {{__('Payment Method')}}
											</h4>
											
											<div class="form-group">
												{!! Form::label('payment_method', __('Payment Method')) !!}
												{!! Form::select('payment_method', $payment_methods, null, ['is' => 'select2', 'html-class' => 'form-control', 'required', 'v-model' => 'payment_method']) !!}
											</div>
											
											<div class="form-group">
												{!! Form::label('currency', __('I Trade In')) !!}
												{!! Form::select('currency', get_iso_currencies(), null, ['is' => 'select2', 'html-class' => 'form-control', 'required', 'v-model' => 'currency']) !!}
												<p class="help-inline mt-1">
													{{__('Your offer will buy/sell bitcoin for the selected currency. For example, if you select US Dollars then your offer is visible for everyone willing to sell bitcoin with US Dollar currency.')}}
												</p>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<h4 class="form-section"><i class="ft-percent"></i> {{__('Profit Margin')}}
											</h4>
											<div class="form-group row">
												{!! Form::label('profit_margin', __('I want to earn'), ['class' => 'col-md-3']) !!}
												<div class="col-md-9">
													<div class="input-group">
														{!! Form::number('profit_margin', null, ['class' => 'form-control', 'required', 'v-model.number' => 'profit_margin', 'step' => '.1']) !!}
														
														<div class="input-group-append">
															<span class="input-group-text"><i class="ft-percent"></i>
															</span>
														</div>
													</div>
												</div>
											</div>
											
											<div class="bs-callout-primary callout-border-left mt-1 p-1">
												<strong>{{__('STATISTICS')}}</strong>
												<p class="card-text">
													{{__('Current market price is')}} <b>@{{ formatAmount(coinPrice)
														}}</b>. <br/>
													{{__('I am selling')}} @{{ payment_method }} {{__('for')}}
													<b>@{{ formatAmount(totalPrice) }}</b> {{__('per')}} <b>@{{
														coins[coin] }}</b>.
												</p>
												
												<p class="card-text">
													{{__('I will get')}} <b>@{{ totalPercent }}</b>% {{__('of')}}
													<b>@{{ payment_method }}</b>. <br/>
													{{__('I will')}}
													<span v-if="profit_margin >= 0">{{__('earn')}}</span>
													<span v-else>{{__('lose')}}</span>
													<b>@{{ formatAmount(netAmount)}}</b>
													{{__('for every')}} <b>@{{ coins[coin] }}</b> {{__('bought')}}.
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<h4 class="form-section"><i class="ft-briefcase"></i> {{__('Amount Range')}}
											</h4>
											<div class="form-group row">
												<div class="col-6">
													{!! Form::label('min_amount', __('Minimum Amount:')) !!}
													<div class="input-group">
														{!! Form::number('min_amount', null, ['class' => 'form-control', 'required', 'v-model.number' => 'min_amount', 'min' => 0]) !!}
														
														<div class="input-group-append">
															<span class="input-group-text" v-text="currency"></span>
														</div>
													</div>
												</div>
												
												<div class="col-6">
													{!! Form::label('max_amount', __('Maximum Amount:')) !!}
													<div class="input-group">
														{!! Form::number('max_amount', null, ['class' => 'form-control', 'required', 'v-model.number' => 'max_amount', 'min' => 0]) !!}
														
														<div class="input-group-append">
															<span class="input-group-text" v-text="currency"></span>
														</div>
													</div>
												</div>
											</div>
											<p class="card-text">
												{{__('How much time you have to pay the seller. Trade will auto-cancel if you have not clicked "marked as paid" before payment window expires.')}}
											</p>
											<div class="form-group row">
												{!! Form::label('deadline', __('Auto cancel after:'), ['class' => 'col-md-3']) !!}
												<div class="col-md-9">
													<div class="input-group">
														{!! Form::number('deadline', null, ['class' => 'form-control', 'required', 'min' => 0]) !!}
														
														<div class="input-group-append">
															<span class="input-group-text"> {{__('minutes')}} </span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<h4 class="form-section"><i class="ft-tag"></i> {{__('Tags & Labels')}}</h4>
											<div class="form-group">
												{!! Form::label('tags', __('Select Tags:')) !!}
												{!! Form::select('tags[]', get_tags(), null, ['is' => 'select2', 'html-class' => 'form-control', 'multiple', 'novalidate']) !!}
												<p class="help-block">
													{{__('Add quick tags that best describe your offer terms.')}}
													<b>{{__('Maximum 3 tags.')}}</b>
												</p>
											</div>
											
											<div class="form-group">
												{!! Form::label('label', __('Offer Label:')) !!}
												{!! Form::text('label', null, ['class' => 'form-control', 'required', 'maxlength' => 25, 'placeholder' => __('e.g INSTANT RELEASE')]) !!}
												<p class="help-block">
													{{__('Any marketing text that will appear after your payment method. Maximum 25 characters and only letters and numbers.')}}
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<h4 class="form-section">
												<i class="ft-info"></i> {{__('Terms & Instruction')}}</h4>
											
											<div class="form-group">
												{!! Form::label('terms', __('Offer Terms:')) !!}
												{!! Form::textarea('terms', null, ['class' => 'form-control', 'rows' => 4]) !!}
											</div>
											<div class="form-group">
												{!! Form::label('trade_instruction', __('Trade Instructions:')) !!}
												{!! Form::textarea('trade_instruction', null, ['class' => 'form-control', 'rows' => 4]) !!}
												<p class="help-block">
													{{__('Shown once the trade has started. Trade instructions must be short, clear and bulleted if possible')}}
												</p>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<h4 class="form-section">
												<i class="ft-check"></i> {{__('Requirements')}}
											</h4>
											
											<div class="form-group">
												<div class="custom-control custom-checkbox">
													{!! Form::checkbox('phone_verification', 1, null, ['class' => 'custom-control-input', 'id' => 'phone_verification']) !!}
													<label class="custom-control-label" for="phone_verification">{{__('Verified Phone')}}</label>
												</div>
											</div>
											
											<div class="form-group">
												<div class="custom-control custom-checkbox">
													{!! Form::checkbox('email_verification', 1, null, ['class' => 'custom-control-input', 'id' => 'email_verification']) !!}
													<label class="custom-control-label" for="email_verification">{{__('Verified Email')}}</label>
												</div>
											</div>
										
										</div>
										<div class="col-md-6">
											<h4 class="form-section"><i class="ft-eye"></i> {{__('Visibility')}}</h4>
											
											<div class="form-group">
												<div class="custom-control custom-checkbox">
													{!! Form::checkbox('trusted_offer', 1, null, ['class' => 'custom-control-input', 'id' => 'trusted_offer']) !!}
													<label class="custom-control-label" for="trusted_offer">{{__('Show to only trusted contacts')}}</label>
												</div>
											</div>
										
										</div>
									</div>
								</div>
								
								<div class="form-actions right">
									<button type="submit" class="btn btn-primary">
										{{__('SUBMIT')}}
									</button>
								</div>
								
								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</home-offers-edit-page>
@endsection

@push('data')
	<script type="text/javascript">
		window._vueData = {!! json_encode([
				'coin' => $offer->coin,
				'min_amount' => $offer->min_amount,
				'max_amount' => $offer->max_amount,
				'payment_method' => $offer->payment_method,
				'profit_margin' => $offer->profit_margin,
                'currency' => $offer->currency,
                'coins' => get_coins(),
                'coin_prices' => get_prices(),
            ]) !!}
	</script>
@endpush
