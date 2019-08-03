<template>
    <select :name="name" :class="htmlClass" :placeholder="placeholder" :multiple="multiple">
        <slot></slot>
    </select>
</template>

<script>
    export default {
        name: "Select2",
        props: {
            name: {
                type: String,
                required: true
            },
            htmlClass: String,
            placeholder: String,
            value: [String, Array, Number],
            options:  Object,
            multiple: Boolean,
            max: {
                type: Number,
                default: 0
            },
        },
        mounted: function () {
            let vm = this;

            let elem =$(this.$el)
                .select2({
                    placeholder: vm.placeholder,
                    maximumSelectionLength: vm.max
                })
                .on('change', function () {
                    vm.$emit('input', this.value)
                });
            if(this.value !== undefined){
                elem.val(this.value)
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
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }
    }
</script>

<style scoped>

</style>
