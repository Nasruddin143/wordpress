<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div class="wpo_section wpo_group">
	<h3 class="wpo-first-child"><?php esc_html_e('LCP Optimization', 'wp-optimize');?> <span><?php esc_html_e('(Experimental)', 'wp-optimize');?></span></h3>
	<p>
		<?php esc_html_e("Largest Contentful Paint (LCP) is an important, Core Web Vital.", 'wp-optimize'); ?>
	</p>
	<div class="wpo-fieldgroup">
		<div class="switch-container">
			<label class="switch">
				<input name="lcp_preload_enable" id="lcp_preload_enable" class="cache-settings" type="checkbox" value="true" <?php checked($lcp_preload_enable); ?>>
				<span class="slider round"></span>
			</label>
			<label for="lcp_preload_enable">
				<?php esc_html_e('Enable LCP (Largest Contentful Paint) preload', 'wp-optimize'); ?>
			</label>
		</div>
		<p class="description">
			<?php esc_html_e('Automatically detect and preload LCP elements to improve page load performance.', 'wp-optimize'); ?>
			<a href="https://web.dev/lcp/" target="_blank" rel="noopener"><?php esc_html_e('Learn more', 'wp-optimize'); ?></a>
		</p>
	</div>
</div>