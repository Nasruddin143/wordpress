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

<div class="pb-4 pb-md-5 bg-light">
	<div class="container overflow-hidden">
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
								<div class="credits text-secondary text-center mt-2 fs-8">
									Built by <a href="http://sknasruddin.indevs.in/" target="_blank"
										class="link-secondary text-decoration-none">Nasruddin Shaikh</a> with <span
										class="text-primary">&#9829;</span>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-lg-4 order-1 order-lg-2">
							<div class="social-media-wrapper">
								<ul class="list-unstyled m-0 p-0 d-flex justify-content-center justify-content-sm-end">
									<li class="me-3">
										<a href="#!" class="link-dark link-opacity-75-hover">
											<!-- https://feathericons.dev/?search=facebook&iconset=feather -->
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
										<a href="#!" class="link-dark link-opacity-75-hover">
											<!-- https://feathericons.dev/?search=twitter&iconset=feather -->
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
										<a href="#!" class="link-dark link-opacity-75-hover">
											<!-- https://feathericons.dev/?search=instagram&iconset=feather -->
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
										<a href="#!" class="link-dark link-opacity-75-hover">
											<!-- https://feathericons.dev/?search=youtube&iconset=feather -->
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
</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>