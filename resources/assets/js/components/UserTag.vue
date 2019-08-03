<template>
    <div class="user-tag d-inline-block">
        <div class="media">
            <div class="media-left d-none d-sm-block pr-1">
                <span class="avatar avatar-md rounded-circle" :class="avatarPresenceObject">
                    <img :src="avatar" alt="avatar"> <i></i>
                </span>
            </div>

            <div class="media-body media-middle">
                <a :href="profileLink" class="media-heading">
                    {{name}} <span class="flag-icon" v-if="countryCode !== 'null'" :class="'flag-icon-' + countryCode"></span>
                </a>
                <br/>
                <span v-if="rating">
                    <rating :score="rating"></rating>
                </span>
                <span v-else>
                    No rating yet!
                </span>
            </div>
        </div>
        <p class="blue-grey font-small-3 lighten-2">
            {{lastSeenPresence}}
        </p>
    </div>
</template>

<script>
    export default {
        name: "UserTag",
        props: {
            id: {
                type: Number,
                required: true,
            },
            name: {
                type: String,
                required: true,
            },
            avatar: {
                type: String,
                required: true,
            },
            presence: {
                type: String,
                validator: function (value) {
                    return ['online', 'away', 'offline']
                        .indexOf(value) !== -1;
                },
                required: true,
            },
            countryCode: {
            	type: String,
            },
            lastSeen: String,
            rating: Number
        },

        data: function(){
            return {
                profilePresence: null,
                profileLastSeen: null
            }
        },

        mounted: function () {
            this.profilePresence = this.presence;
            this.profileLastSeen = this.lastSeen;

            this.$nextTick(function () {
                this.listenProfilePresence();
            })
        },

        methods: {
            listenProfilePresence: function () {
                let vm = this;

                Echo.private('user.' + vm.id + '.presence')
                    .listen('UserPresenceUpdated', function (e) {
                        vm.profilePresence = e.presence;
                        vm.profileLastSeen = e.last_seen;
                    });

            },
        },

        computed: {
            profileLink: function(){
              return route('profile.index', {
                  user: this.name
              })
            },

            avatarPresenceObject: function () {
                return {
                    'avatar-off': this.profilePresence === 'offline',
                    'avatar-online': this.profilePresence === 'online',
                    'avatar-away': this.profilePresence === 'away',
                }
            },

            lastSeenPresence: function () {
                let lastSeen = 'Not Available!';

                if (this.profileLastSeen) {
                    let time = moment.utc(this.profileLastSeen)
                                     .toDate();

                    lastSeen = 'Seen ' + moment(time).fromNow();
                }

                return lastSeen;
            },
        }
    }
</script>

<style scoped>

</style>
