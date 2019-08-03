export default {
    data: function () {
        return Object.assign({
            text: '',
        }, window._vueData)
    },

    mounted: function () {
        this.$nextTick(function () {
            this.scrollToBottom();
            this.listenTradeStatus();
            this.listenTradeChats();
            this.bindLadda();
        })
    },

    methods: {
        bindLadda: function () {
            this.ladda = Ladda.create($('#submit')[0]);
        },

        typing: function (e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        },

        sendMessage: function () {
            let vm = this;

            const endpoint = route('home.trades.send-message', {
                token: vm.trade.token
            });

            this.ladda.start();

            axios.post(endpoint, {
                message: vm.text
            }).then((response) => {
                this.ladda.stop();
            }).catch((error) => {
                let response = error.response,
                    data     = response.data;

                if (data && data.errors !== undefined) {
                    var timeout = 1000;

                    $.each(data.errors, function (k, v) {

                        $.each(v, function (i, d) {
                            setTimeout(function () {
                                toastr.error(d);
                            }, timeout);
                        });

                        timeout += 1000;
                    });

                } else {
                    toastr.error(data);
                }
            });

            this.text = '';
        },

        listenTradeStatus: function () {
            let vm = this;

            Echo.private('trade.' + vm.token)
                .listen('TradeStatusUpdated', function (e) {
                    vm.status = e.status;
                    vm.confirmed = e.confirmed;
                    vm.dispute_comment = e.dispute_comment;
                    vm.dispute_by = e.dispute_by;
                    vm.scrollToTop();
                });

        },

        uploadMedia: function (data) {
            let vm = this;

            const endpoint = route('home.trades.upload-media', {
                token: vm.trade.token
            });

            this.ladda.start();

            axios.post(endpoint, data)
                 .then((response) => {
                     this.ladda.stop();
                 })
                 .catch((error) => {
                     let response = error.response,
                         data     = response.data;

                     if (data && data.errors !== undefined) {
                         var timeout = 1000;

                         $.each(data.errors, function (k, v) {

                             $.each(v, function (i, d) {
                                 setTimeout(function () {
                                     toastr.error(d);
                                 }, timeout);
                             });

                             timeout += 1000;
                         });

                     } else {
                         toastr.error(data);
                     }
                 });
        },

        selectFiles: function () {
            const formData = new FormData();
            const el = this.$refs.fileSelect;

            Array.prototype.forEach.call(el.files, file => {
                formData.append(el.name, file, file.name);
            });

            this.uploadMedia(formData, el.files);
        },

        listenTradeChats: function () {
            let vm = this;

            Echo.private('trade.' + vm.token)
                .listen('NewTradeChatMessage', function (e) {
                    vm.chats = e.chats;
                    vm.scrollToBottom();
                });

        },

        scrollToTop: function () {
            $(document).ready(function () {
                let chat = $('#chat');

                chat.animate({
                    scrollTop: 0
                }, 1000);
            })
        },

        scrollToBottom: function () {
            $(document).ready(function () {
                let chat = $('#chat');

                chat.animate({
                    scrollTop: chat.prop('scrollHeight')
                }, 1000);
            })
        },

        getProfileAvatar: function (user) {
            if (user.profile && user.profile.picture) {
                return user.profile.picture;
            }

            return '/images/objects/avatar.png';
        },

        getProfileLink: function (user) {
            return route('profile.index', {
                user: user.name
            })
        },

        displayContent: function (content, type) {
            let html, name;

            if (type === 'text') {
                return content;
            } else {
                html = "<a href='" + content + "' class='btn btn-float btn-float-lg m-1 btn-success'>";
                html += "<i class='la la-cloud-download'></i><span> Download </span></a>";
                html += "<br/> File: <b>" + this.truncate(content.replace(/^.*[\\\/]/, ''), 8) + "</b>";

            }

            return html;
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

        formatDate: function (date) {
            let localTime = moment.utc(date).toDate();

            return moment(localTime, 'YYYY-MM-DD')
                .calendar(null, {
                    sameDay: '[Today]',
                    nextDay: '[Tomorrow]',
                    nextWeek: 'dddd',
                    lastDay: '[Yesterday]',
                    lastWeek: '[Last] dddd',
                    sameElse: 'MMMM Do, YYYY'
                });
        }
    },
}
