export default {
    data: function () {
        return {
            amount: '',
            payment_method: '',
            currency: ''
        }
    },

    mounted: function () {
        let url = new URL(window.location);
        let params = url.searchParams;

        this.amount = params.get('amount');
        this.payment_method = params.get('payment_method');
        this.currency = params.get('currency');
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
        coinPrice: function () {
            let currency = (this.currency) ? this.currency : 'USD';
            let coin = (this.coin) ? this.coin : 'BTC';

            return this.coin_prices[coin.toUpperCase()][currency.toUpperCase()];
        },

        profitMarginPrice: function() {
            return (this.totalPercent * this.coinPrice) / 100;
        },

        totalPercent: function () {
            let margin = 0;

            if (this.profit_margin) {
                margin = Math.abs(this.profit_margin);
            }

            return 100 + margin;
        }
    }
}
