window.Cropper = require('cropperjs/dist/cropper');

export default {
	data: function () {
		return Object.assign({
			dropzone: {},
		}, window._vueData);
	},

	mounted: function () {
		this.$nextTick(function () {
			if (this.profile !== undefined) {
				this.handleIntlPhoneInput();
				this.handlePictureUpload();
			}
		})
	},

	methods: {
		getTwofaCode: function () {
			return '{"code": "' + this.form.twofa_code + '"}';
		},

		getPhoneCode: function () {
			return '{"code": "' + this.form.phone_code + '"}';
		},

		handleIntlPhoneInput: function () {
			let phone = $('#phone');
			let phoneCountry = $('#phone-country');

			if (phone.length > 0) {
				phone.intlTelInput({
					initialCountry: 'auto',
					nationalMode: false,

					geoIpLookup: function (callback) {
						$.get("https://ipinfo.io", function () {
						}, "jsonp")
						 .always(function (resp) {
							 callback((resp && resp.country) ? resp.country : "");
						 });
					}
				});

				let data = phone.intlTelInput("getSelectedCountryData");

				phoneCountry.val(data.iso2);

				phone.on("countrychange", function (e, data) {
					phoneCountry.val(data.iso2);
				});
			}
		},

		handlePictureUpload: function () {
			if ($('div#picture-upload').length > 0) {
				let vm = this;


				// vm.dropzone['#picture-upload'] = new Dropzone(
				//     "div#picture-upload",
				//     {
				//         url: endpoint,
				//         paramName: 'profile_picture',
				//         headers: {
				//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				//         },
				//         acceptedFiles: 'image/*',
				//         addRemoveLinks: false,
				//         autoProcessQueue: false,
				//         success: function (file, response) {
				//             toastr.success(response);
				//         },
				//         error: function (file, response) {
				//             if ($.isPlainObject(response)) {
				//                 var timeout = 1000;
				//
				//                 $.each(response.errors, function (key, value) {
				//                     setTimeout(function () {
				//                         toastr.error(value);
				//                     }, timeout);
				//
				//                     timeout += 1000;
				//                 });
				//             } else {
				//                 toastr.error(response);
				//             }
				//         }
				//     }
				// );
				//
				// let cropper;
				//
				// vm.dropzone['#picture-upload'].on('thumbnail', function (file) {
				//     // Ignore files which were already cropped and re-rendered
				//     // to prevent infinite loop
				//     if (file.cropped) {
				//         return;
				//     }
				//
				//     // Cache filename to re-assign it to cropped file
				//     var cachedFilename = file.name;
				//
				//     // Remove not cropped file from dropzone (we will replace it later)
				//     vm.dropzone['#picture-upload'].removeFile(file);
				//
				//     // Dynamically create modals to allow multiple files processing
				//     var $cropperModal = $('#cropper-custom');
				//
				//     // 'Crop and Upload' button in a modal
				//     var $uploadCrop = $cropperModal.find('.crop-upload');
				//
				//     var $img = $('<img id="img-cropper" style="max-width:100%;"/>');
				//
				//     // Initialize FileReader which reads uploaded file
				//     var reader = new FileReader();
				//
				//     reader.onloadend = function () {
				//         // Add uploaded and read image to modal
				//         $cropperModal.find('.image-container').html($img);
				//
				//         $img.attr('src', reader.result);
				//
				//         let image = document.getElementById('img-cropper');
				//
				//         // Initialize cropper for uploaded image
				//         cropper = new Cropper(image, {
				//             aspectRatio: 1,
				//             autoCropArea: 1,
				//             movable: false,
				//             cropBoxResizable: true,
				//             minContainerWidth: $('.image-container').actual('width'),
				//             minContainerHeight: 500,
				//             viewMode: 1
				//         });
				//     };
				//
				//     // Read uploaded file (triggers code above)
				//     reader.readAsDataURL(file);
				//
				//     $cropperModal.modal('show');
				//
				//     // Listener for 'Crop and Upload' button in modal
				//     $uploadCrop.on('click', function () {
				//         // Get cropped image data
				//         var uri = cropper.getCroppedCanvas()
				//                          .toDataURL("image/jpeg");
				//
				//         // Transform it to Blob object
				//         var newFile = vm.dataURItoBlob(uri);
				//
				//         // Set 'cropped to true' (so that we don't get to that listener again)
				//         newFile.cropped = true;
				//
				//         // Assign original filename
				//         newFile.name = cachedFilename;
				//
				//         // add cropped file to dropzone
				//         vm.dropzone['#picture-upload'].addFile(newFile);
				//
				//         // Upload cropped file with dropzone
				//         vm.dropzone['#picture-upload'].processQueue();
				//
				//         // We destroy the cropper to avoid duplicate in the next upload
				//         cropper.destroy();
				//
				//         $cropperModal.modal('hide');
				//     });
				// });
			}
		},


		getProfileAvatar: function (user) {
			if (user.profile && user.profile.picture) {
				return user.profile.picture;
			}

			return '/images/objects/avatar.png';
		},

		dataURItoBlob: function (dataURI) {
			var byteString = atob(dataURI.split(',')[1]);
			var ab = new ArrayBuffer(byteString.length);
			var ia = new Uint8Array(ab);
			for (var i = 0; i < byteString.length; i++) {
				ia[i] = byteString.charCodeAt(i);
			}
			return new Blob([ab], {type: 'image/jpeg'});
		},

		base64toBlob: function (string, sliceSize) {
			let block       = string.split(";"),
			    contentType = block[0].split(":")[1],
			    data        = block[1].split(",")[1];

			sliceSize = sliceSize || 512;

			let byteArrays = [];

			let byteCharacters = atob(data);

			for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
				var slice = byteCharacters.slice(offset, offset + sliceSize);

				var byteNumbers = new Array(slice.length);
				for (var i = 0; i < slice.length; i++) {
					byteNumbers[i] = slice.charCodeAt(i);
				}

				var byteArray = new Uint8Array(byteNumbers);

				byteArrays.push(byteArray);
			}

			return new Blob(byteArrays, {type: contentType});
		},

		markAsRead: function (id, event) {
			if (this.profile.id !== undefined) {
				let element = event.currentTarget;

				let endpoint = '/profile/' + this.profile.name + '/notifications/' + id + '/markAsRead';

				axios.post(endpoint).then((response) => {
					window.location.href = element.getAttribute('href')
				}).catch((error) => {
					console.log(error)
				});
			}
		},

		dateDiffForHumans: function (date) {
			let localTime = moment.utc(date).toDate();

			return moment(localTime).fromNow();
		},

		onPictureChange: function (image) {
			let card = $('#picture-input').closest('.card');

			card.block({
				css: {
					border: 0,
					backgroundColor: 'none',
					padding: 0,
				},
				message: '<i class="ft-refresh-cw icon-spin"></i>',
				overlayCSS: {
					backgroundColor: '#FFF',
					cursor: 'wait',
				},
			});

			let formData = new FormData();

			formData.append("image", this.base64toBlob(image));

			const endpoint = route('profile.settings.upload-picture', {
				'user': this.profile.name
			});

			window.axios({
				url: endpoint,
				data: formData,
				method: 'POST',
				config: {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
			}).then(function (response) {
				window.toastr.success(response.data);
				card.unblock();
			}).catch(function (error) {
				window.toastr.error(error);
				card.unblock();
			});
		},
		
		onPictureRemove: function () {
			let card = $('#picture-input').closest('.card');

			card.block({
				css: {
					border: 0,
					backgroundColor: 'none',
					padding: 0,
				},
				message: '<i class="ft-refresh-cw icon-spin"></i>',
				overlayCSS: {
					backgroundColor: '#FFF',
					cursor: 'wait',
				},
			});

			const endpoint = route('profile.settings.delete-picture', {
				'user': this.profile.name
			});

			window.axios({
				url: endpoint,
				method: 'POST',
			}).then(function (response) {
				window.toastr.success(response.data);
				card.unblock();
			}).catch(function (error) {
				window.toastr.error(error);
				card.unblock();
			});
		}
	}
};
