export default {
    data: function(){
        return {
            bellAnimation: '',

            notifications: {
                total: 0,
                data: [],
                current: 0,
                next: false
            },

            mailAnimation: '',

            messages: {
                total: 0,
                data: [],
                current: 0,
                next: false
            },
        }
    },

    methods: {
        getUnreadNotifications: function () {
            let vm = this;

            if (window.Laravel.user.id) {
                const endpoint = route('ajax.profile.unreadNotifications', {
                    'user': window.Laravel.user.name
                });

                axios.post(endpoint)
                     .then((response) => {
                         var notifications = response.data;

                         vm.notifications.current = notifications.current_page;
                         vm.notifications.data = notifications.data;
                         vm.notifications.next = Boolean(notifications.next_page_url);
                         vm.notifications.total = notifications.total;
                     })
                     .catch((error) => {
                         console.log(error)
                     });

                Echo.private('user.' + window.Laravel.user.id)
                    .listen('NotificationsUpdated', function (e) {
                        let prev_total = vm.notifications.total;

                        vm.notifications.current = e.notifications.current_page;
                        vm.notifications.data = e.notifications.data;
                        vm.notifications.next = Boolean(e.notifications.next_page_url);
                        vm.notifications.total = e.notifications.total;

                        if (prev_total < vm.notifications.total) {
                            vm.showBellAlert();
                        }
                    });
            }
        },

        showBellAlert: function () {
            this.bellAnimation = 'rubberBand';
            this.playSound();
        },

        hideBellAlert: function () {
            this.bellAnimation = '';
        },

        getActiveTradeChats: function () {
            let vm = this;

            if (window.Laravel.user.id) {
                this.getMessages();

                Echo.private('user.' + window.Laravel.user.id)
                    .listen('NewMessageAlert', function (e) {
                        vm.getMessages();
                        vm.showMailAlert();
                    });
            }

        },

        getMessages: function () {
            let vm = this;

            if (window.Laravel.user.id) {

                const endpoint = route('ajax.profile.activeTradeChats', {
                    'user': window.Laravel.user.name
                });

                axios.post(endpoint)
                     .then((response) => {
                         var messages = response.data;

                         vm.messages.current = messages.current_page;
                         vm.messages.data = messages.data;
                         vm.messages.next = Boolean(messages.next_page_url);
                         vm.messages.total = messages.total;
                     })
                     .catch((error) => {
                         console.log(error)
                     });
            }
        },

        showMailAlert: function () {
            this.mailAnimation = 'rubberBand';
            this.playSound();
        },

        hideMailAlert: function () {
            this.mailAnimation = '';
        },

        playSound: function () {
            new Audio(
                '/sounds/notify.mp3'
            ).play();
        },

        markAllAsRead: function () {
            if (window.Laravel.user.id) {
                let name = window.Laravel.user.name;

                let endpoint = route('profile.notifications.markAllAsRead', {
                    user: name
                });

                axios.post(endpoint);
            }
        },

        markAsRead: function (id, event) {
            if (window.Laravel.user.id) {
                let element = event.currentTarget;
                let name = window.Laravel.user.name;

                let endpoint = route('profile.notifications.markAsRead', {
                    user: name, id: id
                });

                axios.post(endpoint).then((response) => {
                    window.location.href = element.getAttribute('href')
                }).catch((error) => {
                    console.log(error)
                });
            }
        },

        truncate: function (n, len) {
            var ext = n.substring(n.lastIndexOf(".") + 1, n.length).toLowerCase();
            var filename = n.replace('.' + ext, '');
            if (filename.length <= len) {
                return n;
            }
            filename = filename.substr(0, len) + (n.length > len ? '[...]' : '');
            return filename + '.' + ext;
        },

        getProfileAvatar: function (user) {
            if (user.profile && user.profile.picture) {
                return user.profile.picture;
            }

            return '/images/objects/avatar.png';
        },

        dateDiffForHumans: function (date) {
            let localTime = moment.utc(date).toDate();

            return moment(localTime).fromNow();
        },
    },

    mounted: function () {
        this.$nextTick(function () {
            this.getUnreadNotifications();
            this.getActiveTradeChats();
        });
    },
};


