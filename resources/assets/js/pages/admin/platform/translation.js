import InfiniteLoading from "vue-infinite-loading";

export default {
	data: function () {
		return Object.assign({
			importType: 0,

			translation: {
				total: 0,
				data: [],
				current: 0,
				next: true,
			}
		}, window._vueData);
	},

	mounted() {

	},

	methods: {
		getImportType: function () {
			return '{"type": "' + this.importType + '"}';
		},

		translationsInfiniteHandler: function ($state) {
			let vm = this;

			if (this.translation.next) {
				const endpoint = route('admin.platform.translation.group.data', {
					'group': vm.translationGroup
				});

				axios.post(endpoint, {
					page: vm.translation.current + 1,
				}).then(function (response) {
					let translation = response.data;

					if (Object.keys(translation.data).length && vm.translation.next) {
						vm.translation.current = translation.current_page;
						vm.translation.data = vm.translation.data.concat(translation.data);
						vm.translation.next = Boolean(translation.next_page_url);
						vm.translation.total = translation.total;
					} else {
						vm.translation.next = false;
					}

					$state.loaded();

					if (!vm.translation.next) {
						$state.complete();
					}
				}).catch(function (error) {
					if (error.response) {
						let response = error.response.data;

						if ($.isPlainObject(response)) {
							$.each(response.errors, function (key, value) {
								toastr.error(value[0]);
							});
						} else {
							toastr.error(response);
						}

						vm.translation.next = false;

						$state.complete();
					} else {
						console.log(error.message);
					}
				});
			} else {
				$state.complete();
			}
		},

		getTranslationLink: function () {
			return route('admin.platform.translation.group.edit', {
				'group': this.translationGroup
			});
		},

		getTranslation: function (translation, locale) {
			let key = this.getKey(translation);
			let localeGroup = translation[key];

			if(!localeGroup.hasOwnProperty(locale)) return '';

			return localeGroup[locale].value ?
				localeGroup[locale].value : '';
		},

		setTranslation: function (index, locale, value) {
			let key = this.getKey(this.translation.data[index]);

			this.translation.data[index][key][locale].value = value;
		},

		changeTranslation: function (event) {
			let button = $(event.currentTarget), vm = this;
			button.removeData();

			const endpoint = route('admin.platform.translation.group.update', {
				'group': this.translationGroup
			});

			let title  = button.data('title'),
			    locale = button.data('locale'),
			    index  = button.data('index'),
			    key    = button.data('key');

			swal({
				title: title,
				text: button.data('text'),
				icon: "info",
				content: {
					element: "input",
					attributes: {
						value: button.data('value'),
						type: 'text',
					},
				},
				closeOnClickOutside: false,
				buttons: {
					cancel: {
						text: "No",
						visible: true,
						value: null,
					},
					confirm: {
						text: "Yes",
						visible: true,
						value: true,
						closeModal: false
					}
				}
			}).then(value => {
				if (value) {
					$.ajax({
						url: endpoint,
						type: 'PUT',
						data: {
							locale: locale,
							key: key,
							value: value,
						},
					}).done(function (response) {
						swal.stopLoading();
						vm.setTranslation(index, locale, value);
						swal.close();
					}).fail(function (xhr) {
						swal({
							title: "Oops!",
							text: xhr.responseText,
							icon: "error"
						})
					});
				}else{
					swal.close();
				}
			});
		},

		getKey: function (translation) {
			return Object.keys(translation)[0];
		},

	},

	components: {InfiniteLoading},
}
