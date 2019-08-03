export default {
    data: function () {
        return {
            form: {
                offerTag: {
                    id: '',
                    name: '',
                },

                paymentCategory: {
                    id: '',
                    name: '',
                },

                paymentMethod: {
                    id: '',
                    category: '',
                    timeFrame: 0,
                    name: '',
                }
            }
        }
    },

    mounted: function(){
        this.$nextTick(function () {
            this.handlePaymentMethodEditor();
            this.handlePaymentMethodCategoryEditor();
            this.handleOfferTagEditor();
        });
    },

    methods: {
        handlePaymentMethodEditor: function () {
            let vm = this, data;
            let selector = '#payment-methods-table';

            $(selector).on('click', '.edit', function (e) {
                data = App.dataTable[selector].row('#' + $(this).data('id')).data();

                vm.form.paymentMethod.id = data.id;
                vm.form.paymentMethod.category = data.payment_method_category_id;
                vm.form.paymentMethod.timeFrame = data.time_frame;
                vm.form.paymentMethod.name = data.name;

                $('#payment-method-form').modal('show');
            });
        },

        handlePaymentMethodCategoryEditor: function () {
            let vm = this, data;
            let selector = '#payment-method-categories-table';

            $(selector).on('click', '.edit', function (e) {
                data = App.dataTable[selector].row('#' + $(this).data('id')).data();

                vm.form.paymentCategory.id = data.id;
                vm.form.paymentCategory.name = data.name;

                $('#payment-method-category-form').modal('show');
            });
        },

        handleOfferTagEditor: function () {
            let vm = this, data;
            let selector = '#offer-tags-table';

            $(selector).on('click', '.edit', function (e) {
                data = App.dataTable[selector].row('#' + $(this).data('id')).data();

                vm.form.offerTag.id = data.id;
                vm.form.offerTag.name = data.name;

                $('#offer-tag-form').modal('show');
            });
        },

        resetPaymentCategory: function (id) {
            this.form.paymentCategory.id = '';
            this.form.paymentCategory.name = '';
        },

        resetOfferTag: function (id) {
            this.form.offerTag.id = '';
            this.form.offerTag.name = '';
        },

        resetPaymentMethod: function (id) {
            this.form.paymentMethod.id = '';
            this.form.paymentMethod.category = '';
            this.form.paymentMethod.timeFrame = 30;
            this.form.paymentMethod.name = '';
        },
    }
}
