export default {
	data: function () {
		return Object.assign({}, window._vueData);
	},


	methods: {
		formatAmount: function (value) {
			let currency = (this.currency) ? this.currency : 'USD';

			return new Intl.NumberFormat(this.locale, {
				style: 'currency', currencyDisplay: 'symbol', currency: currency
			}).format(value);
		}
	},

	computed: {
		totalPrice: function () {
			return (this.totalPercent * this.coinPrice) / 100;
		},

		netAmount: function () {
			return Math.abs(this.totalPrice - this.coinPrice);
		},

		totalPercent: function () {
			let margin = 0;

			if (this.profit_margin) {
				margin = this.profit_margin;
			}

			return 100 + margin;
		},

		coinPrice: function () {
			let currency = (this.currency) ? this.currency : 'USD';
			let coin = (this.coin) ? this.coin : 'BTC';

			return this.coin_prices[coin.toUpperCase()][currency.toUpperCase()];
		},
	}
}
