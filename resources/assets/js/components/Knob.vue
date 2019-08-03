<template>
    <input type="text">
</template>

<script>
    export default {
        name: "Knob",
        props: {
            value: {},
            dataKnobIcon: {
                type: String,
                default: 'icon-note'
            },
        },
        mounted: function () {
            let vm = this;

            let rtl = ($('html').data('textdirection') == 'rtl');

            let elem = $(this.$el).knob({
                rtl: rtl,

                draw: function () {
                    let knob = $('<i class="knob-center-icon ' + vm.dataKnobIcon + '"></i>');

                    let ele = this.$;
                    let newFontSize = Math.ceil(parseInt(ele.css('font-size'), 10) * 1.65);
                    let style = ele.attr('style');

                    ele.hide();


                    knob.attr('style', style).css('font-size', newFontSize).insertAfter(ele);
                }
            });

            $(this.$el).on('change', function () {
                vm.$emit('input', this.value)
            });

            if (this.value !== undefined) {
                $(this.$el).val(this.value)
                           .trigger('change');
            }
        },

        watch: {
            value: function (value) {
                $(this.$el)
                    .val(value)
                    .trigger('change')
            }
        },
    }
</script>

<style scoped>

</style>
