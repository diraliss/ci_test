var app = new Vue({
    el: '#app',
    data: {
        login: '',
        pass: '',
        post: false,
        invalidLogin: false,
        invalidPass: false,
        invalidSum: false,
        posts: [],
        addSum: 0,
        amount: 0,
        likes: 0,
        commentText: '',
        commentParent: null,
        packs: [],
        backendData: {
            type: 'array',
            title: 'Backend data',
            data: [],
            singleData: {}
        }
    },
    computed: {
        test: function () {
            var data = [];
            return data;
        }
    },
    created() {
        var self = this
        axios
            .get('/main_page/get_all_posts')
            .then(function (response) {
                self.posts = response.data.posts;
            });
        axios
            .get('/main_page/get_all_boosterpacks')
            .then(function (response) {
                self.packs = response.data.boosterpacks;
            });
    },
    methods: {
        logout: function () {
            console.log('logout');
        },
        logIn: function () {
            var self = this;
            if (self.login === '') {
                self.invalidLogin = true
            } else if (self.pass === '') {
                self.invalidLogin = false
                self.invalidPass = true
            } else {
                self.invalidLogin = false
                self.invalidPass = false
                axios.post('/main_page/login', {
                    login: self.login,
                    password: self.pass
                })
                    .then(function (response) {
                        setTimeout(function () {
                            $('#loginModal').modal('hide');
                        }, 500);
                    })
            }
        },
        addComment: function () {
            var self = this;
            if (self.commentText !== '') {
                let url = '/main_page/comment/' + self.post.id;
                if (self.commentParent !== null) {
                    url += '/comment/' + self.commentParent;
                }

                axios.post(url, {
                    message: self.commentText
                }).then(function () {
                    self.reloadPost();
                })
            }
        },
        fiilIn: function () {
            var self = this;
            if (self.addSum === 0) {
                self.invalidSum = true
            } else {
                self.invalidSum = false
                axios.post('/main_page/add_money', {
                    amount: self.addSum,
                })
                    .then(function (response) {
                        setTimeout(function () {
                            $('#addModal').modal('hide');
                        }, 500);
                    })
            }
        },
        openPost: function (id) {
            var self = this;
            axios
                .get('/main_page/get_post/' + id)
                .then(function (response) {
                    self.post = response.data.post;
                    self.likes = 0;
                    if (self.post) {
                        setTimeout(function () {
                            $('#postModal').modal('show');
                        }, 500);
                    }
                })
        },
        reloadPost: function () {
            var self = this;
            axios.get('/main_page/get_post/' + self.post.id)
                .then(function (response) {
                    self.post = response.data.post;
                    self.likes = 0;
                })
        },
        addPostLike: function (id) {
            var self = this;
            axios.get('/main_page/like/post/' + id)
                .then(function (response) {
                    self.likes = response.data.likes;
                })
        },
        addCommentLike: function (id) {
            var self = this;
            axios.get('/main_page/like/comment/' + id).then(self.reloadPost);
        },
        buyPack: function (id) {
            axios.post('/main_page/buy_boosterpack/' + id, {
                id: id,
            })
        },
        openUsersTransactions: function () {
            var self = this;
            axios.get('/main_page/user_transactions')
                .then(function (response) {
                    self.backendData.data = response.data.transactions;
                    self.backendData.title = 'Transactions';

                    if (self.backendData.data.length > 0) {
                        setTimeout(function () {
                            $('#backendModal').modal('show');
                        }, 500);
                    }
                })
        },
        openUsersBoosterpacks: function () {
            var self = this;
            axios.get('/main_page/user_boosterpacks')
                .then(function (response) {
                    self.backendData.data = response.data.boosterpacks;
                    self.backendData.title = 'Boosterpacks';

                    if (self.backendData.data.length > 0) {
                        setTimeout(function () {
                            $('#backendModal').modal('show');
                        }, 500);
                    }
                })
        },
        openUsersInfo: function () {
            var self = this;
            axios.get('/main_page/get_current_user_info')
                .then(function (response) {
                    self.backendData.singleData = response.data.user;
                    self.backendData.title = 'User info';
                    self.backendData.type = 'single';

                    setTimeout(function () {
                        $('#backendModal').modal('show');
                    }, 500);
                })
        }
    }
});

