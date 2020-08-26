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
		packs: [
			{
				id: 1,
				price: 5
			},
			{
				id: 2,
				price: 20
			},
			{
				id: 3,
				price: 50
			},
		],
	},
	computed: {
		test: function () {
			var data = [];
			return data;
		}
	},
	created(){
		var self = this
		axios
			.get('/main_page/get_all_posts')
			.then(function (response) {
				self.posts = response.data.posts;
			})
	},
	methods: {
		logout: function () {
			console.log ('logout');
		},
		logIn: function () {
			var self= this;
			if(self.login === ''){
				self.invalidLogin = true
			}
			else if(self.pass === ''){
				self.invalidLogin = false
				self.invalidPass = true
			}
			else{
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
			var self= this;
			if(self.commentText !== ''){
				let url = '/main_page/comment/'+ self.post.id;
				if (self.commentParent !== null) {
					url += '/comment/' + self.commentParent;
				}

				axios.post(url, {
					message: self.commentText
				})
			}
		},
		fiilIn: function () {
			var self= this;
			if(self.addSum === 0){
				self.invalidSum = true
			}
			else{
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
			var self= this;
			axios
				.get('/main_page/get_post/' + id)
				.then(function (response) {
					self.post = response.data.post;
					self.likes = 0;
					if(self.post){
						setTimeout(function () {
							$('#postModal').modal('show');
						}, 500);
					}
				})
		},
		addPostLike: function (id) {
			var self= this;
			axios.get('/main_page/like/post/' + id)
				.then(function (response) {
					self.likes = response.data.likes;
				})
		},
		addCommentLike: function (id) {
			var self= this;
			axios.get('/main_page/like/comment/' + id)
		},
		buyPack: function (id) {
			axios.post('/main_page/buy_boosterpack/' + id, {
				id: id,
			})
		}
	}
});

