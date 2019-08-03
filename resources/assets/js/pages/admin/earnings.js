export default {
    data: function(){
        return {
            payout: {
                id: 0,
                amount: 0,
                coinName: '',
                coin: '',
            }
        }
    },

    mounted(){
        this.$nextTick(function () {
            this.initPayoutModal();
        })
    },

    methods: {
        initPayoutModal(){
            let vm = this;

            $('body').on('click', '.payout', function (e) {
                e.preventDefault();

                vm.payout.id = $(this).data('id');
                vm.payout.amount = $(this).data('amount');
                vm.payout.coinName = $(this).data('coinName');
                vm.payout.coin = $(this).data('coin');

                $('#payout').modal('show');
            });
        }
    }
}
