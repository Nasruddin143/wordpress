<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<h3><?php esc_html_e('Optimize <head> with Capo.js rules', 'wp-optimize'); ?></h3>
<div class="wpo-fieldgroup">
	<fieldset>
		<?php esc_html_e('An unoptimized <head> can slow down your site.', 'wp-optimize'); ?> <?php esc_html_e('Apply Capo.js rules to quickly fix these issues and boost performance.', 'wp-optimize'); ?>
		<div class="wpo-fieldgroup__subgroup">
			<h4><label>
				<input
					name="enable_capo_js"
					type="checkbox" 
					value="true"
					<?php checked($enable_capo_js, true); ?>
				>
				<?php
					// translators: %s is a link to Capo.js website
					printf(esc_html__('Apply %s rules', 'wp-optimize'), '<a href="https://rviscomi.github.io/capo.js/" target="_blank">Capo.js</a>');
				?>
			</label>
			</h4>
		</div>
	</fieldset>
</div>