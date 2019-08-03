<template>
    <span></span>
</template>

<script>
	export default {
		name: "Rating",
        props: {
		    score: {
		        type: [Number, Object],
                required: true,
            },
            readOnly: {
		        type: Boolean,
                default: true,
            },
            size: {
                type: String,
                validator: function (value) {
                    return ['sm', 'md']
                        .indexOf(value) !== -1;
                },
                default: 'sm',
            }
        },

        mounted: function () {
            let vm = this;

            this.$nextTick(function () {
                let options = {
                    score: vm.score,
                    readOnly: vm.readOnly,
                };

                if(this.size === 'sm'){
                    options = Object.assign(options, {
                        starOff: 'star-off-sm.png',
                        starHalf: 'star-half-sm.png',
                        starOn: 'star-on-sm.png'
                    });
                }

                if(vm.score > 0 || !vm.readOnly){
                    $(this.$el).raty(options)
                }else{
                    $(this.$el).text('No rating yet!');
                }
            });
        }
	}
</script>

<style scoped>

</style>
