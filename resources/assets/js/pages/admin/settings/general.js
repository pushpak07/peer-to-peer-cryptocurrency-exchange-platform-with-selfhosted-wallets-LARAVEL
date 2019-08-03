export default {
    data: function () {
        return Object.assign({}, window._vueData)
    },

    mounted: function () {
        this.$nextTick(function () {
            this.initFileSelect();
        })
    },

    methods: {
        initFileSelect: function () {
            $('.fileselect').fileselect({
                language: window.Laravel.locale,
            });
        }
    }
}
