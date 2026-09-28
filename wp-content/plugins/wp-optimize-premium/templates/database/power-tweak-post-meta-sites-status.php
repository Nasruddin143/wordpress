<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<table class="wpo-post-meta-sites-table widefat striped">
	<thead>
	<tr>
		<th><?php esc_html_e('Site', 'wp-optimize'); ?></th>
		<th><?php esc_html_e('Status', 'wp-optimize'); ?></th>
		<th><?php esc_html_e('Last indexed', 'wp-optimize'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($sites_status as $site) : ?>
		<tr>
			<td><a href="<?php echo esc_url($site['url']); ?>" target="_blank"><?php echo esc_html($site['name']); ?></a></td>
			<td class="wpo-site-status-<?php echo esc_attr($site['status']); ?>"><?php echo esc_html($site['status_label']); ?></td>
			<td><?php echo $site['last_run'] ? esc_html(WP_Optimize()->format_date_time($site['last_run'])) : '&mdash;'; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
