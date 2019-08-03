export default function (window, document, $) {
	'use strict';
	var $html = $('html');
	var $body = $('body');


	$(window).on('load', function () {
		var compactMenu = false; // Set it to true, if you want default menu to be compact

		var rtl = ($('html').data('textdirection') == 'rtl');

		setTimeout(function () {
			$html.removeClass('loading').addClass('loaded');
		}, 1200);

		$.app.menu.init(compactMenu);

		// Navigation configurations
		var config = {
			speed: 300 // set speed to expand / collpase menu
		};

		if ($.app.nav.initialized === false) {
			$.app.nav.init(config);
		}

		Unison.on('change', function (bp) {
			$.app.menu.change();
		});

		// Tooltip Initialization
		$('[data-toggle="tooltip"]').tooltip({
			container: 'body'
		});

		// Top Navbars - Hide on Scroll
		if ($(".navbar-hide-on-scroll").length > 0) {
			$(".navbar-hide-on-scroll.fixed-top").headroom({
				"offset": 205,
				"tolerance": 5,
				"classes": {
					// when element is initialised
					initial: "headroom",
					// when scrolling up
					pinned: "headroom--pinned-top",
					// when scrolling down
					unpinned: "headroom--unpinned-top",
				}
			});
			// Bottom Navbars - Hide on Scroll
			$(".navbar-hide-on-scroll.fixed-bottom").headroom({
				"offset": 205,
				"tolerance": 5,
				"classes": {
					// when element is initialised
					initial: "headroom",
					// when scrolling up
					pinned: "headroom--pinned-bottom",
					// when scrolling down
					unpinned: "headroom--unpinned-bottom",
				}
			});
		}

		//Match content & menu height for content menu
		setTimeout(function () {
			if ($('body').hasClass('vertical-content-menu')) {
				setContentMenuHeight();
			}
		}, 500);

		function setContentMenuHeight() {
			var menuHeight = $('.main-menu').height();
			var bodyHeight = $('.content-body').height();
			if (bodyHeight < menuHeight) {
				$('.content-body').css('height', menuHeight);
			}
		}

		// Collapsible Card
		$('a[data-action="collapse"]').on('click', function (e) {
			e.preventDefault();
			$(this).closest('.card').children('.card-content').collapse('toggle');
			$(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ft-minus ft-plus');

		});

		// Toggle fullscreen
		$('a[data-action="expand"]').on('click', function (e) {
			e.preventDefault();
			$(this).closest('.card').find('[data-action="expand"] i').toggleClass('ft-maximize ft-minimize');
			$(this).closest('.card').toggleClass('card-fullscreen');
		});

		//  Notifications & messages scrollable
		if ($('.scrollable-container').length > 0) {
			$('.scrollable-container').perfectScrollbar({
				theme: "dark"
			});
		}

		// Close Card
		$('a[data-action="close"]').on('click', function () {
			$(this).closest('.card').removeClass().slideUp('fast');
		});

		// Match the height of each card in a row
		setTimeout(function () {
			$('.row.match-height').each(function () {
				$(this).find('.card').not('.card .card').matchHeight(); // Not .card .card prevents collapsible cards from taking height
			});
		}, 500);


		$('.card .heading-elements a[data-action="collapse"]').on('click', function () {
			var $this = $(this),
			    card  = $this.closest('.card');
			var cardHeight;

			if (parseInt(card[0].style.height, 10) > 0) {
				cardHeight = card.css('height');
				card.css('height', '').attr('data-height', cardHeight);
			} else {
				if (card.data('height')) {
					cardHeight = card.data('height');
					card.css('height', cardHeight).attr('data-height', '');
				}
			}
		});

		var menuType = $body.data('menu'), mainMenuContent = $(".main-menu-content");

		// Add active class to all list subitem which has the current url
		var currenctUrl = window.location.href.split('#')[0].split('?')[0];
		var activeLink = mainMenuContent.find('a[href="' + currenctUrl + '"]');

		if (activeLink.length) {
			activeLink.closest('li').addClass('active');
		}

		// Add open class to parent list item if subitem is active except compact menu
		if (menuType !== 'vertical-compact-menu' && menuType !== 'horizontal-menu' && compactMenu === false) {
			if ($body.data('menu') === 'vertical-menu-modern') {
				if (localStorage.getItem("menuLocked") === "true") {
					mainMenuContent.find('li.active').parents('li').addClass('open');
				}
			} else {
				mainMenuContent.find('li.active').parents('li').addClass('open');
			}
		}
		if (menuType === 'vertical-compact-menu' || menuType === 'horizontal-menu') {
			mainMenuContent.find('li.active').parents('li:not(.nav-item)').addClass('open');
			mainMenuContent.find('li.active').parents('li').addClass('active');
		}

		//card heading actions buttons small screen support
		$(".heading-elements-toggle").on("click", function () {
			$(this).parent().children(".heading-elements").toggleClass("visible");
		});

		//  Dynamic height for the chartjs div for the chart animations to work
		var chartjsDiv = $('.chartjs'), canvasHeight = chartjsDiv.children('canvas').attr('height');

		chartjsDiv.css('height', canvasHeight);

		if ($body.hasClass('boxed-layout')) {
			if ($body.hasClass('vertical-overlay-menu') || $body.hasClass('vertical-compact-menu')) {

				var contentPosition = $('.app-content').position().left;
				var menuWidth = $('.main-menu').width();
				var menuPositionAdjust = contentPosition - menuWidth;

				if ($body.hasClass('menu-flipped')) {
					$('.main-menu').css('right', menuPositionAdjust + 'px');
				} else {
					$('.main-menu').css('left', menuPositionAdjust + 'px');
				}
			}
		}

		$('.nav-link-search').on('click', function () {
			var $this       = $(this),
			    searchInput = $(this).siblings('.search-input');

			if (searchInput.hasClass('open')) {
				searchInput.removeClass('open');
			} else {
				searchInput.addClass('open');
			}
		});
	});


	$(document).on('click', '.menu-toggle, .modern-nav-toggle', function (e) {
		e.preventDefault();

		// Toggle menu
		$.app.menu.toggle();

		setTimeout(function () {
			$(window).trigger("resize");
		}, 200);

		if ($('#collapsed-sidebar').length > 0) {
			setTimeout(function () {
				if ($body.hasClass('menu-expanded') || $body.hasClass('menu-open')) {
					$('#collapsed-sidebar').prop('checked', false);
				} else {
					$('#collapsed-sidebar').prop('checked', true);
				}
			}, 1000);
		}

		return false;
	});

	$(document).on('click', '.open-navbar-container', function (e) {

		var currentBreakpoint = Unison.fetch.now();

		// Init drilldown on small screen
		$.app.menu.drillDownMenu(currentBreakpoint.name);

		// return false;
	});

	$(document).on('click', '.main-menu-footer .footer-toggle', function (e) {
		e.preventDefault();

		$(this).find('i').toggleClass('pe-is-i-angle-down pe-is-i-angle-up');

		$('.main-menu-footer').toggleClass('footer-close footer-open');

		return false;
	});

	// Add Children Class
	$('.navigation').find('li').has('ul').addClass('has-sub');

	$('.carousel').carousel({
		interval: 2000
	});

	// Page full screen
	$('.nav-link-expand').on('click', function (e) {
		if (typeof screenfull != 'undefined') {
			if (screenfull.enabled) {
				screenfull.toggle();
			}
		}
	});

	if (typeof screenfull != 'undefined') {
		if (screenfull.enabled) {
			$(document).on(screenfull.raw.fullscreenchange, function () {
				if (screenfull.isFullscreen) {
					$('.nav-link-expand').find('i').toggleClass('ft-minimize ft-maximize');
				} else {
					$('.nav-link-expand').find('i').toggleClass('ft-maximize ft-minimize');
				}
			});
		}
	}

	$(document).on('click', '.mega-dropdown-menu', function (e) {
		e.stopPropagation();
	});

	$(document).ready(function () {

		/**********************************
		 *   Form Wizard Step Icon
		 **********************************/
		$('.step-icon').each(function () {
			var $this = $(this);

			if ($this.siblings('span.step').length > 0) {
				$this.siblings('span.step').empty();

				$(this).appendTo($(this).siblings('span.step'));
			}
		});
	});

	// Update manual scroller when window is resized
	$(window).resize(function () {
		$.app.menu.manualScroller.updateHeight();
	});

	// TODO : Tabs dropdown fix, remove this code once fixed in bootstrap 4.
	$('.nav.nav-tabs a.dropdown-item').on('click', function () {
		var $this = $(this), href = $this.attr('href'), tabs = $this.closest('.nav');

		tabs.find('.nav-link').removeClass('active');

		$this.closest('.nav-item').find('.nav-link').addClass('active');
		$this.closest('.dropdown-menu').find('.dropdown-item').removeClass('active');

		$this.addClass('active');

		tabs.next().find(href).siblings('.tab-pane').removeClass('active in').attr('aria-expanded', false);
		$(href).addClass('active in').attr('aria-expanded', 'true');
	});

	$('#sidebar-page-navigation').on('click', 'a.nav-link', function (e) {
		e.preventDefault();
		e.stopPropagation();

		var $this = $(this), href = $this.attr('href'), offset = $(href).offset(),
		    scrollto                                           = offset.top - 80; // minus fixed header height

		$('html, body').animate({scrollTop: scrollto}, 0);

		setTimeout(function () {
			$this.parent('.nav-item').siblings('.nav-item').children('.nav-link').removeClass('active');
			$this.addClass('active');
		}, 100);
	});

	/**********************************
	 *      Browser Pre Loader        *
	 **********************************/
	var $preload = $('#preloader');

	if ($preload.length > 0) {
		$(window).on('load', function () {
			$preload.children().fadeOut(300);
			$preload.delay(150).fadeOut(500);

			$('body').delay(100).css({
				'overflow': 'visible'
			});
		});
	}


	/**********************************
	 *      Set DataTable Defaults    *
	 **********************************/
	(function (factory) {
		if (typeof define === "function" && define.amd) {
			define(["jquery", "moment", "datatables.net"], factory);
		} else {
			factory(jQuery, moment);
		}
	}(function ($, moment) {

		$.fn.dataTable.moment = function (format, locale) {
			var types = $.fn.dataTable.ext.type;

			// Add type detection
			types.detect.unshift(function (d) {
				if (d) {
					// Strip HTML tags and newline characters if possible
					if (d.replace) {
						d = d.replace(/(<.*?>)|(\r?\n|\r)/g, '');
					}

					// Strip out surrounding white space
					d = $.trim(d);
				}

				// Null and empty values are acceptable
				if (d === '' || d === null) {
					return 'moment-' + format;
				}

				return moment(d, format, locale, true).isValid() ?
					'moment-' + format :
					null;
			});

			// Add sorting method - use an integer for the sorting
			types.order['moment-' + format + '-pre'] = function (d) {
				if (d) {
					// Strip HTML tags and newline characters if possible
					if (d.replace) {
						d = d.replace(/(<.*?>)|(\r?\n|\r)/g, '');
					}

					// Strip out surrounding white space
					d = $.trim(d);
				}

				return !moment(d, format, locale, true).isValid() ?
					Infinity :
					parseInt(moment(d, format, locale, true).format('x'), 10);
			};
		};

	}));

	$.fn.dataTable.moment("MMM D, YYYY");

	$.extend($.fn.dataTable.defaults, {
		rowId: 'id',

		dom: "<'row'<'col-sm-6 d-none d-sm-block'l><'col-sm-6'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 d-none d-sm-inline col-md-5'i><'col-sm-12 col-md-7'p>>",

		"autoWidth": false,

		responsive: {
			details: {
				type: 'column',
				target: 0
			}
		},

		"language": window.tableLanguage,

		processing: false,
		serverSide: true,

		columnDefs: [{
			orderable: false,
			className: 'control',
			targets: 0,
			searchable: false,
		}],

		"order": [
			[1, 'desc']
		],

		"lengthMenu": [
			[10, 50, 100, 500, -1],
			[10, 50, 100, 500, "All"] // change per page values here
		],

		"pageLength": 10,
	});


	/**********************************
	 *    Bootstrap Tabs Settings     *
	 **********************************/
	$(document).ready(function () {
		let activePill = location.pathname + '#active_pill',
		    activeTab  = location.pathname + '#active_tab', hash;

		$('a[data-toggle="pill"]').on('click', function (e) {
			let hash = $(e.target).attr('href');

			sessionStorage.setItem(activePill, hash);
		});

		if (hash = sessionStorage.getItem(activePill)) {
			$('.nav-link[href="' + hash + '"]').tab('show');
		}

		$('a[data-toggle="tab"]').on('click', function (e) {
			let hash = $(e.target).attr('href');

			sessionStorage.setItem(activeTab, hash);
		});

		if (hash = sessionStorage.getItem(activeTab)) {
			$('.nav-link[href="' + hash + '"]').tab('show');
		}
	});

	/**********************************
	 *      Set Raty Default Options  *
	 **********************************/
	$.fn.raty.defaults.path = '/images/ratings';


	/********************************
	 *    Admin Menu                *
	 ********************************/
	let admin = $('.admin');

	$('.admin-toggle').on('click', function () {
		admin.toggleClass('open');
	});

	$('.admin-close').on('click', function () {
		admin.removeClass('open');
	});

	let adminContent = $('.admin-content');

	if (adminContent.length > 0) {
		adminContent.perfectScrollbar({
			theme: "dark"
		});
	}

	/********************************
	 *      Sweet Alerts            *
	 ********************************/
	$(document).ready(function () {
		let title;

		$("body").on('click', '[data-swal]', function (e) {
			e.preventDefault();

			let button = $(this);
			button.removeData();

			title = button.data('title') ? button.data('title') : "Are you sure?";

			switch (button.data('swal')) {
				case 'confirm-ajax':
					swal({
						title: title,
						text: button.data('text'),
						icon: button.data('icon') ? button.data('icon') : "info",
						closeOnClickOutside: false,
						buttons: {
							cancel: {
								text: button.data('btnCancelText') ?
									button.data('btnCancelText') : "No",
								visible: true,
								value: null,
							},
							confirm: {
								text: button.data('btnConfirmText') ?
									button.data('btnConfirmText') : "Yes",
								visible: true,
								value: true,
								closeModal: false
							}
						}
					}).then(confirm => {
						if (confirm) {
							$.ajax({
								url: button.data('link') ? button.data('link') : button.attr('href'),
								type: button.data('ajaxType') ? button.data('ajaxType') : 'GET',
								data: button.data('ajaxData') ? button.data('ajaxData') : {},

							}).done(function (res) {
								swal({
									title: "Successful!",
									text: res,
									icon: "success"
								}).then(confirm => {
									let successAction = button
										.data('ajaxSuccessAction');

									if (successAction) {
										window[successAction](button, res);
									} else {
										window.location.reload();
									}
								});
							}).fail(function (xhr) {
								swal({
									title: "Oops!",
									text: xhr.responseText,
									icon: "error"
								})
							});
						}
					});

					break;

				case 'prompt-ajax':
					swal({
						title: title,
						text: button.data('text'),
						icon: button.data('icon') ? button.data('icon') : "info",
						content: {
							element: "input",
							attributes: {
								value: button.data('value') ? button.data('value') : '',
								placeholder: button.data('placeholder') ? button.data('placeholder') : '',
								type: 'text',
							},
						},
						closeOnClickOutside: false,
						buttons: {
							cancel: {
								text: button.data('btnCancelText') ?
									button.data('btnCancelText') : "No",
								visible: true,
								value: null,
							},
							confirm: {
								text: button.data('btnConfirmText') ?
									button.data('btnConfirmText') : "Yes",
								visible: true,
								value: true,
								closeModal: false
							}
						}
					}).then(value => {
						if (value) {
							let data = button.data('ajaxData') ? button.data('ajaxData') : {};

							$.ajax({
								url: button.data('link') ? button.data('link') : button.attr('href'),
								type: button.data('ajaxType') ? button.data('ajaxType') : 'GET',
								data: Object.assign(data, {prompt: value}),
							}).done(function (res) {
								swal({
									title: "Successful!",
									text: res,
									icon: "success"
								}).then(confirm => {
									let successAction = button
										.data('ajaxSuccessAction');

									if (successAction) {
										window[successAction](button, res, value);
									} else {
										window.location.reload();
									}
								});
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

					break;
			}
		});
	});


	/********************************
	 *      Form Elements           *
	 ********************************/
	$(document).ready(function () {
		$("form[data-ajax]").each(function (i, e) {
			$(e).submit(function (e) {
				e.preventDefault();
				let form = $(this), ladda, btn;

				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: new FormData(form[0]),

					// Important
					contentType: false,
					processData: false,
					cache: false,
					beforeSend: function () {
						btn = form.find(':submit')[0];
						ladda = Ladda.create(btn);
						ladda.start();
					}
				}).done(function (res) {
					toastr.success(res);
				}).fail(function (xhr) {
					let response = xhr.responseJSON;

					if (response.errors !== undefined) {
						var timeout = 1000;

						$.each(response.errors, function (k, v) {
							// let input = form.find("[name='" + k + "']");
							//
							// input.closest('div.form-group')
							//      .removeClass('validate')
							//      .addClass('error');
							//
							// input.jqBootstrapValidation();

							$.each(v, function (i, d) {
								setTimeout(function () {
									toastr.error(d);
								}, timeout);
							});

							timeout += 1000;
						});

						// $('html, body').stop().animate({
						//     scrollTop: form.offset().top - 50
						// }, 1000);
					} else {
						toastr.error(response);
					}
				}).always(function () {
					ladda.stop();
				});
			});
		});
	});

	/**********************************
	 *         Side Bar Sticky        *
	 **********************************/

	$(document).ready(function () {
		let sidebar = $(".sidebar-sticky");

		if (sidebar.length > 0) {
			var headerNavbarHeight,
			    footerNavbarHeight;

			// Header & Footer offset only for right & left sticky sidebar
			if ($body.hasClass('content-right-sidebar') || $body.hasClass('content-left-sidebar')) {
				headerNavbarHeight = $('.header-navbar').height();
				footerNavbarHeight = $('footer.footer').height();
			}
			// Header & Footer offset with padding for detached right & left dsticky sidebar
			else {
				headerNavbarHeight = $('.header-navbar').height() + 24;
				footerNavbarHeight = $('footer.footer').height() + 10;
			}

			sidebar.sticky({
				topSpacing: headerNavbarHeight,
				bottomSpacing: footerNavbarHeight
			});
		}
	});

	/**********************************
	 *          Init Select2          *
	 **********************************/
	$.fn.select2.defaults.set("width", "100%");
};
