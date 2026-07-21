<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WooShop
 */

$custom_logo_id = get_theme_mod('custom_logo');
$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
?>

<footer class="footer bg-light">
	<div class="container py-4 py-md-5">

		<div class="row">
			<div class="col-12 col-lg-4 col-md-4">
				<div class="widget mb-3">
					<h4 class="widget-title mb-4 text-second">Get in Touch</h4>
					<address class="mb-4">
						<?php echo get_theme_mod('shop_address'); ?>
					</address>
					<p class="mb-1">
						<a class="link-dark link-offset-1 link-opacity-75 link-opacity-100-hover link-underline-opacity-0 link-underline-opacity-100-hover"
							href="tel:<?php echo get_theme_mod('mobile_contact_number') ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
								class="main-grid-item-icon me-2" fill="none" stroke="currentColor"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
								<path
									d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
							</svg>

							<?php echo get_theme_mod('mobile_contact_number'); ?>
						</a>
					</p>
					<p class="mb-0">
						<a class="link-dark link-offset-1 link-opacity-75 link-opacity-100-hover link-underline-opacity-0 link-underline-opacity-100-hover"
							href="mailto:<?php echo get_theme_mod('email_contact_address') ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
								class="main-grid-item-icon me-2" fill="none" stroke="currentColor"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
								<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
								<polyline points="22,6 12,13 2,6" />
							</svg>

							<?php echo get_theme_mod('email_contact_address') ?>
						</a>
					</p>
				</div>
			</div>

			<div class="col-12 col-lg-4 col-md-4">
				<div class="widget p-0 px-md-5">
					<h4 class="widget-title mb-4 text-second">Services</h4>
					<?php if (is_active_sidebar('footer-sidebar-1')): ?>
						<div class="footer-column footer-column-1">
							<?php dynamic_sidebar('footer-sidebar-1'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-12 col-lg-4 col-md-4">
				<div class="widget">
					<h4 class="widget-title mb-4 text-second">Opening Hours</h4>
					<p class="mb-4">We always aim to provide a welcoming environment to deliver exceptional
						service.</p>
					<div>
						<div class="mb-2">
							<span class="fw-bold me-2"><?php echo get_theme_mod('shop_opening_days') ?>:</span>
							<span class="text-dark"><?php echo get_theme_mod('shop_opening_time') ?></span>
						</div>
						<!-- <div class="row mb-1">
							<div class="col-5 col-xl-4">
								<span class="fw-bold">Sat:</span>
							</div>
							<div class="col-7 col-xl-8">
								<span class="text-secondary">9am - 2pm</span>
							</div>
						</div> -->
						<div class="mb-2">
							<span class="fw-bold"><?php echo get_theme_mod('shop_holiday') ?>:</span>
							<span class="text-dark">We're Closed</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container border-top">
		<div class="row">
			<div class="col">
				<div class="">
					<div class="row gy-4 gy-lg-0 py-3 py-md-4 py-xxl-5 align-items-md-center">
						<div class="col-xs-12 col-sm-6 col-lg-4 order-0 order-lg-0">
							<div class="footer-logo-wrapper text-center text-sm-start">
								<a href="#!">
									<?php if (has_custom_logo()): ?>

										<img src="<?php echo esc_url($logo[0]); ?>"
											alt="<?php echo get_bloginfo('name'); ?>" class="custom-logo img-fluid" />

									<?php else: ?>

										<p class="fs-4 fw-bold mb-0 text-uppercase"><?php bloginfo('name'); ?></p>

									<?php endif; ?>
								</a>
							</div>
						</div>

						<div class="col-xs-12 col-lg-4 order-2 order-lg-1">
							<div class="colophon-wrapper">
								<div class="footer-copyright-wrapper text-center">
									&copy; 2024. All Rights Reserved.
								</div>
								<div class="credits text-dark text-center mt-2 fs-8">
									Built by <a href="http://sknasruddin.indevs.in/" target="_blank"
										class="text-decoration-none">Nasruddin Shaikh</a> with <span
										class="text-primary">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14"
											height="14" class="main-grid-item-icon" fill="none" stroke="currentColor"
											stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path
												d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
										</svg>

									</span>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-lg-4 order-1 order-lg-2">
							<div class="social-media-wrapper">
								<ul class="list-unstyled m-0 p-0 d-flex justify-content-center justify-content-sm-end">
									<li class="me-3">
										<a href="<?php echo get_theme_mod('facebook_url') ?>"
											class="link-opacity-75-hover" data-bs-toggle="tooltip"
											data-bs-placement="top" data-bs-title="Follow us on Facebook">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
												height="24" class="main-grid-item-icon" fill="none"
												stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2">
												<path
													d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
											</svg>
										</a>
									</li>
									<li class="me-3">
										<a href="<?php echo get_theme_mod('twitter_url') ?>"
											class="link-opacity-75-hover" data-bs-toggle="tooltip"
											data-bs-placement="top" data-bs-title="Follow us on x-twitter">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
												height="24" class="main-grid-item-icon" fill="none"
												stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2">
												<path
													d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
											</svg>

										</a>
									</li>
									<li class="me-3">
										<a href="<?php echo get_theme_mod('instagram_url') ?>"
											class="link-opacity-75-hover" data-bs-toggle="tooltip"
											data-bs-placement="top" data-bs-title="Follow us on Instagram">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
												height="24" class="main-grid-item-icon" fill="none"
												stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2">
												<rect height="20" rx="5" ry="5" width="20" x="2" y="2" />
												<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
												<line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
											</svg>

										</a>
									</li>
									<li class="">
										<a href="" class="link-opacity-75-hover" data-bs-toggle="tooltip"
											data-bs-placement="top" data-bs-title="Follow us on YouTube">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
												height="24" class="main-grid-item-icon" fill="none"
												stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2">
												<path
													d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" />
												<polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" />
											</svg>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>