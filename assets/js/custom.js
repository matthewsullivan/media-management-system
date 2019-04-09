/*!
 *	Template Functions
*/

(function($){

	"use strict";

	/* ---------------------------------------------- /*
	 * Preloader
	/* ---------------------------------------------- */

	$(window).load(function() {
		$('.page-loader').delay(350).fadeOut('slow');
	});

	$(document).ready(function() {

		/* ---------------------------------------------- /*
		 * Mobile detect
		/* ---------------------------------------------- */

		var mobileTest;

		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			mobileTest = true;
		} else {
			mobileTest = false;
		}

		/* ---------------------------------------------- /*
		 * Header animation
		/* ---------------------------------------------- */

		var header = $('.header');

		if ($('.module-header').length <= 0) {
			header.addClass('header-small');
		};

		$(window).scroll(function() {
			var scroll = $(window).scrollTop();
			if ( scroll >= $('.module-header').outerHeight() - 80 ) {
				header.addClass('header-small');
			} else {
				header.removeClass('header-small');
			}
		}).scroll();

		/* ---------------------------------------------- /*
		 * Light/dark header
		/* ---------------------------------------------- */

		var module_header = $('.module-header');

		if ( module_header.length >= 0 ) {
			if ( module_header.hasClass('bg-dark') ) {
				header.addClass('header-light');
			} else {
				header.removeClass('header-light');
			}
		}

		/* ---------------------------------------------- /*
		 * Show/Hide menu
		/* ---------------------------------------------- */

		$('.show-menu-btn').on('click', function() {
			$('.overlay-menu').toggleClass('active');
			$('body').toggleClass('menu-opened');
			return false;
		});

		$('.close-menu').on('click', function() {
			setTimeout(function() {
				$('.overlay-menu').removeClass('active');
				$('body').removeClass('menu-opened');
				$('ul.navigation li').removeClass('sub-menu-open');
			}, 500);
			return false;
		});

		$(window).keydown(function(e) {
			if ($('.overlay-menu').hasClass('active')) {
				if (e.which === 27) {
					setTimeout(function() {
						$('.overlay-menu').removeClass('active');
						$('body').removeClass('menu-opened');
						$('ul.navigation li').removeClass('sub-menu-open');
					}, 500);
					return false;
				}
			}
		});

		/* ---------------------------------------------- /*
		 * Setting background of modules
		/* ---------------------------------------------- */

		$('[data-background]').each(function() {
			$(this).css('background-image', 'url(' + $(this).attr('data-background') + ')');
		});

		/* ---------------------------------------------- /*
		 * Parallax
		/* ---------------------------------------------- */

		$('.parallax').jarallax({
			speed: 0.6
		});

		/* ---------------------------------------------- /*
		 * Portfolio masonry
		/* ---------------------------------------------- */

		var filters   = $('#filters'),
			worksgrid = $('.row-portfolio');

		$('a', filters).on('click', function() {
			var selector = $(this).attr('data-filter');
			$('.current', filters).removeClass('current');
			$(this).addClass('current');
			worksgrid.isotope({
				filter: selector
			});
			return false;
		});

		$(window).on('resize', function() {
			$('.row-portfolio').imagesLoaded(function() {
				$('.row-portfolio').isotope({
					layoutMode: 'masonry',
					itemSelector: '.portfolio-item',
					transitionDuration: '0.8s',
					masonry: {
						columnWidth: '.grid-sizer',
					},
				});
			});
		}).resize();

		/* ---------------------------------------------- /*
		 * Blog masonry
		/* ---------------------------------------------- */

		$(window).on('resize', function() {
			setTimeout(function() {
				$('.blog-masonry').isotope({
					layoutMode: 'masonry',
					transitionDuration: '0.8s',
				});
			}, 1000);
		}).resize();

		/* ---------------------------------------------- /*
		 * Slides
		/* ---------------------------------------------- */

		$('.tms-slides').owlCarousel({
			paginationSpeed: 2000,
			nav:             false,
			singleItem:      true,
			autoPlay:        true,
			pagination:      true,
		});

		$('.clients-carousel').owlCarousel({
			nav:        false,
			items:      6,
			autoplay:   true,
			pagination: false,
		});

		$('.image-slider').owlCarousel({
			autoPlay:   2000,
			navigation: true,
			loop:       true,
			nav:        true,
			singleItem: true,
			pagination: false,
			center:     true,
			navigationText: [
				'<i class="ti-angle-left"></i>',
				'<i class="ti-angle-right"></i>'
			]
		});

		/* ---------------------------------------------- /*
		 * Progress bars, counters, pie charts animations
		/* ---------------------------------------------- */

		$('.progress-bar').each(function() {
			$(this).appear(function() {
				var percent = $(this).attr('aria-valuenow');
				$(this).animate({'width' : percent + '%'});
				$(this).parent('.progress').prev('.progress-title').find('.p-count').countTo({
					from: 0,
					to: percent,
					speed: 900,
					refreshInterval: 30
				});
			});
		});

		$('.counter-timer').each(function() {
			$(this).appear(function() {
				var number = $(this).attr('data-to');
				$(this).countTo({
					from: 0,
					to: number,
					speed: 1500,
					refreshInterval: 10,
					formatter: function (number, options) {
						number = number.toFixed(options.decimals);
						number = number.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
						return number;
					}
				});
			});
		});

		$('.chart').each(function() {
			$(this).appear(function() {
				$(this).easyPieChart($.extend({
					barColor:   "#252525",
					trackColor: "#eee",
					scaleColor: false,
					lineCap:    'square',
					lineWidth:  3,
					size:       180,
				}, $(this).data('chart-options')));
			});
		});

		$('.appear-childer > .container > .row, .appear-childer > .container-fluid > .row').each(function() {
			var animate_delay = 0.25;
			$(this).appear(function() {
				$(this).children().each(function() {
					$(this).css({ 'transition-delay' : animate_delay + 's' });
					$(this).addClass('anim-end');
					animate_delay = animate_delay + 0.25;
				});
			});
		});

		/* ---------------------------------------------- /*
		 * Twitter
		/* ---------------------------------------------- */

		$('.twitter-feed').each(function (index) {
			$(this).attr('id', 'twitter-' + index);

			var twitterID      = $(this).data('twitter');
			var twitterMax     = $(this).data('number');
			var twitter_config = {
				'id':             twitterID,
				'domId':          'twitter-' + index,
				'maxTweets':      twitterMax,
				'enableLinks':    true,
				'showPermalinks': false
			};
			twitterFetcher.fetch(twitter_config);
		});

		/* ---------------------------------------------- /*
		 * Lightbox, Gallery
		/* ---------------------------------------------- */

		$('.lightbox').magnificPopup({
			type: 'image'
		});

		$('[rel=gallery]').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1]
			},
			image: {
				titleSrc: 'title',
				tError: 'The image could not be loaded.',
			}
		});

		$('.lightbox-video').magnificPopup({
			type: 'iframe',
		});

		/* ---------------------------------------------- /*
		 * A jQuery plugin for fluid width video embeds
		/* ---------------------------------------------- */

		$('body').fitVids();


		/* ---------------------------------------------- /*
		 * Ajax options
		/* ---------------------------------------------- */

		var pageNumber = 0,
			workNumberToload = 5;

		var doneText    = 'Done',
			loadText    = 'More works',
			loadingText = 'Loading...',
			errorText   = 'Error! Check the console for more information.';

		/* ---------------------------------------------- /*
		 * Ajax portfolio
		/* ---------------------------------------------- */

		$('#show-more').on('click', function() {
			$(this).text(loadingText);

			setTimeout(function() {
				ajaxLoad(workNumberToload, pageNumber);
			}, 300);

			pageNumber++;

			return false;
		});

		function ajaxLoad(workNumberToload, pageNumber) {
			var $loadButton = $('#show-more');
			var dataString = 'numPosts=' + workNumberToload + '&pageNumber=' + pageNumber;

			$.ajax({
				type: 'GET',
				data: dataString,
				dataType: 'html',
				url: 'portfolio.html',
				success: function(data) {
					var $data = $(data);
					var start_index = (pageNumber - 1) * workNumberToload;
					var end_index = + start_index + workNumberToload;

					if ($data.find('.portfolio-item').slice(start_index).length) {
						var work = $data.find('.portfolio-item').slice(start_index, end_index);

						$('.row-portfolio').append(work).isotope('appended', work).resize();

						setTimeout(function() {
							$loadButton.text(loadText);
						}, 300);

						$('a.gallery').magnificPopup({
							type: 'image',
							gallery: {
								enabled: true,
								navigateByImgClick: true,
								preload: [0,1]
							},
							image: {
								titleSrc: 'title',
								tError: 'The image could not be loaded.',
							}
						});

						$('.portfolio-item').each(function() {
							$('.portfolio-img-wrap', this).css('background-image', 'url(' + $('.portfolio-img-wrap', this).attr('data-background') + ')');
						});

					} else {
						setTimeout(function() {
							$loadButton.text(doneText);
						}, 300);

						setTimeout(function () {
							$('#show-more').animate({
								opacity: 0,
							}).css('display', 'none');
						}, 1500);

						$('a.gallery').magnificPopup({
							type: 'image',
							gallery: {
								enabled: true,
								navigateByImgClick: true,
								preload: [0,1]
							},
							image: {
								titleSrc: 'title',
								tError: 'The image could not be loaded.',
							}
						});
					}
				},

				error: function (jqXHR, textStatus, errorThrown) {
					console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);

					setTimeout(function() {
						$loadButton.removeClass('ss-loading');
						$loadButton.text(errorText);
					}, 300);
				}
			});
		}

		/* ---------------------------------------------- /*
		 * Scroll Animation
		/* ---------------------------------------------- */
		/*
		$('.smoothscroll').on('click', function(e) {
			var target  = this.hash;
			var $target = $(target);

			$('html, body').stop().animate({
				'scrollTop': $target.offset().top - header.height()
			}, 600, 'swing');

			e.preventDefault();
		});
		*
		/* ---------------------------------------------- /*
		 * Scroll top
		/* ---------------------------------------------- */

		$('a[href="#top"]').on('click', function() {
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			return false;
		});

		/* ---------------------------------------------- /*
		 * Disable hover on scroll
		/* ---------------------------------------------- */

		var body = document.body,
			timer;
		window.addEventListener('scroll', function() {
			clearTimeout(timer);
			if (!body.classList.contains('disable-hover')) {
				body.classList.add('disable-hover')
			}
			timer = setTimeout(function() {
				body.classList.remove('disable-hover')
			}, 100);
		}, false);

	});

})(jQuery);
