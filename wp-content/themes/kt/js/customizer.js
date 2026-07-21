/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

/* global wp */

(function () {

	// Helper: run after DOM is ready
	const ready = (fn) => {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	};

	ready(function () {

		// Site Title
		wp.customize('blogname', function (value) {
			value.bind(function (to) {
				const el = document.querySelector('.site-title a');
				if (el) el.textContent = to;
			});
		});

		// Tagline
		wp.customize('blogdescription', function (value) {
			value.bind(function (to) {
				const el = document.querySelector('.site-description');
				if (el) el.textContent = to;
			});
		});

		// Header Text Color
		wp.customize('header_textcolor', function (value) {
			value.bind(function (to) {

				const title = document.querySelector('.site-title');
				const desc = document.querySelector('.site-description');
				const link = document.querySelector('.site-title a');

				if (!title || !desc) return;

				if (to === 'blank') {
					[title, desc].forEach(el => {
						el.style.clip = 'rect(1px, 1px, 1px, 1px)';
						el.style.position = 'absolute';
					});
				} else {
					[title, desc].forEach(el => {
						el.style.clip = 'auto';
						el.style.position = 'relative';
					});

					if (link) link.style.color = to;
					desc.style.color = to;
				}
			});
		});

	});

})();

// (function ($) {
// 	// Site title and description.
// 	wp.customize('blogname', function (value) {
// 		value.bind(function (to) {
// 			$('.site-title a').text(to);
// 		});
// 	});
// 	wp.customize('blogdescription', function (value) {
// 		value.bind(function (to) {
// 			$('.site-description').text(to);
// 		});
// 	});

// 	// Header text color.
// 	wp.customize('header_textcolor', function (value) {
// 		value.bind(function (to) {
// 			if ('blank' === to) {
// 				$('.site-title, .site-description').css({
// 					clip: 'rect(1px, 1px, 1px, 1px)',
// 					position: 'absolute',
// 				});
// 			} else {
// 				$('.site-title, .site-description').css({
// 					clip: 'auto',
// 					position: 'relative',
// 				});
// 				$('.site-title a, .site-description').css({
// 					color: to,
// 				});
// 			}
// 		});
// 	});
// }(jQuery));
