export default {
    data: function () {
        return {
            visibleOffers: {
                count: 0,
                percent: 0,
            },

            completedTrades: {
                count: 0,
                percent: 0,
                dispute: 0,
            },

            onlineUsers: {
                count: 0,
                percent: 0,
            }

        }
    },

    mounted: function () {
        this.$nextTick(function () {
            this.initOnlineUsers();
            this.initCompletedTrades();
            this.initVisibleOffers();
        });
    },

    methods: {
        formatNumber(number) {
            return new Intl.NumberFormat().format(number);
        },

        initVisibleOffers(){
            this.getVisibleOffers();
        },

        getVisibleOffers() {
            const vm = this;

            var card = $('#visible-offers');

            card.block({
                overlayCSS: {
                    backgroundColor: '#FFF',
                    cursor: 'wait',
                },
                message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });

            const endpoint = route('admin.home.visible-offers');

            axios.post(endpoint)
                 .then((response) => {
                     let data = response.data;
                     vm.visibleOffers.percent = parseInt(data.percent);
                     vm.visibleOffers.count = parseInt(data.count);
                     card.unblock();
                 })
                 .catch((error) => {
                     console.log(error);
                     card.unblock();
                 });
        },

        initCompletedTrades(){
            const vm = this;

            //TODO: change listen class
            Echo.private('administration')
                .listen('TradeStatusUpdated', function (e) {
                    vm.getCompletedTrades();
                });

            vm.getCompletedTrades();
        },

        getCompletedTrades() {
            const vm = this;

            var card = $('#completed-trades');

            card.block({
                overlayCSS: {
                    backgroundColor: '#FFF',
                    cursor: 'wait',
                },
                message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });

            const endpoint = route('admin.home.completed-trades');

            axios.post(endpoint)
                 .then((response) => {
                     let data = response.data;
                     vm.completedTrades.count = parseInt(data.count);
                     vm.completedTrades.dispute = parseInt(data.dispute);
                     vm.completedTrades.percent = parseInt(data.percent);
                     card.unblock();
                 })
                 .catch((error) => {
                     console.log(error);
                     card.unblock();
                 });
        },

        initOnlineUsers(){
            const vm = this;

            Echo.private('administration')
                .listen('UserPresenceUpdated', function (e) {
                    vm.getOnlineUsers();
                });

            vm.getOnlineUsers();
        },

        getOnlineUsers() {
            const vm = this;

            var card = $('#online-users');

            card.block({
                overlayCSS: {
                    backgroundColor: '#FFF',
                    cursor: 'wait',
                },
                message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });

            const endpoint = route('admin.home.online-users');

            axios.post(endpoint)
                 .then((response) => {
                     let data = response.data;
                     vm.onlineUsers.percent = parseInt(data.percent);
                     vm.onlineUsers.count = parseInt(data.count);
                     card.unblock();
                 })
                 .catch((error) => {
                     console.log(error);
                     card.unblock();
                 });
        }
    }
}
