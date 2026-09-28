<?php

namespace Blocksy\Extensions\WoocommerceExtra;

if (! defined('ABSPATH')) {
	exit;
}

class ProductWaitlistDb {
    public static $db_name = 'blocksy_waitlists';
	public static $cookie_name = 'blc_products_waitlist';
	public static $cookie_version = 2;

    public function __construct() {
        self::define_tables();

        add_action('admin_init', [$this, 'install_db'], 100);
		add_action('admin_init', [$this, 'upgrade_db'], 100);
    }

	public static function remove_not_confirmed_emails() {
		global $wpdb;

		$wpdb->query( // phpcs:ignore.
			"DELETE FROM $wpdb->blocksy_waitlists
			WHERE created_date_gmt < (NOW() - INTERVAL 2 DAY) AND confirmed = 0"
		);
	}

	public static function get_total_number_of_rows() {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM $wpdb->blocksy_waitlists";

		return $wpdb->get_var($query); // phpcs:ignore.
	}

	public static function confirm_subscription($token) {
		global $wpdb;

		$db_row = $wpdb->get_row( // phpcs:ignore.
			$wpdb->prepare("SELECT user_email, product_id, variation_id FROM $wpdb->blocksy_waitlists WHERE confirm_token = %s", $token)
		);

		if (! $db_row) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$wpdb->blocksy_waitlists,
			['confirmed' => 1],
			['confirm_token' => $token]
		);

		return true;
	}

    public static function get_subscription_by_token($token) {
		global $wpdb;

		$db_row = $wpdb->get_row( // phpcs:ignore.
			$wpdb->prepare("SELECT user_email, product_id, variation_id FROM $wpdb->blocksy_waitlists WHERE confirm_token = %s", $token)
		);

		return $db_row;
	}

	public static function unsubscribe_by_token($token) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->delete(
			$wpdb->blocksy_waitlists,
			[
				'unsubscribe_token' => $token
			]
		);
	}

	public static function get_waitlist($product = '', $email = '', $confirmed = true, $page = false) {
		if (! is_user_logged_in()) {
			return self::get_waitlists_from_cookies($product);
		}

		return self::get_waitlists_from_db($product, $email, get_current_user_id(), $confirmed, [], $page);
	}

	public static function get_waitlists_from_cookies($product = '') {
		if (! isset($_COOKIE[self::$cookie_name])) {
			return [];
		}

		if (! is_string($_COOKIE[self::$cookie_name])) {
			return [];
		}

		$waitlist = json_decode(
			sanitize_text_field(wp_unslash($_COOKIE[self::$cookie_name])),
			true
		);

		if (! is_array($waitlist)) {
			return [];
		}

		$tokens = self::normalize_cookie_tokens($waitlist);

		if (empty($tokens)) {
			return [];
		}

		return self::get_waitlists_from_db($product, '', '', false, [], false, $tokens);
	}

	// v1 stored subscription ids, v2 stores unsubscribe tokens. A v1 cookie
	// cannot be upgraded: turning its ids into tokens would mean handing a
	// secret to whoever guessed the id, which is what the token format exists
	// to prevent. So v1 resolves to nothing and the next write replaces it.
	private static function normalize_cookie_tokens($waitlist) {
		if (
			! isset($waitlist['v'])
			||
			! isset($waitlist['items'])
			||
			! is_array($waitlist['items'])
		) {
			return [];
		}

		if (intval($waitlist['v']) !== self::$cookie_version) {
			return [];
		}

		$tokens = [];

		foreach ($waitlist['items'] as $token) {
			if (! is_string($token)) {
				continue;
			}

			// wp_generate_password($len, false) only ever produces
			// alphanumerics, so anything else was not written by us.
			if (! preg_match('/^[A-Za-z0-9]+$/', $token)) {
				continue;
			}

			$tokens[] = $token;
		}

		return $tokens;
	}

    public static function get_waitlists_from_db($product = '', $email = '', $user_id = '', $confirmed = true, $lists = [], $page = false, $tokens = []) {
		global $wpdb;

		// '' is the explicit "no product filter" default, used by the admin
		// side listings and by the current visitor's own waitlist. Anything
		// else that is not a resolved product means the caller asked for a
		// specific product and wc_get_product() returned false, in which case
		// the lookup must match nothing instead of silently widening into a
		// SELECT over the whole table.
		if ('' !== $product && ! $product instanceof \WC_Product) {
			return [];
		}

		$where = [];

		if ('' !== $product) {
			$where_product_key = 'product_id';

			if ('variation' === $product->get_type()) {
				$where_product_key = 'variation_id';
			}

			$where[] = $wpdb->prepare('%i = %d', $where_product_key, $product->get_id());
		}

		if (is_email($email)) {
			$where[] = $wpdb->prepare('user_email = %s', $email);
		}

		if (! empty($user_id)) {
			$where[] = $wpdb->prepare('user_id = %d', $user_id);
		}

		if ($confirmed) {
			$where[] = $wpdb->prepare('confirmed = %d', 1);
		}

		if (! empty($lists)) {
			$ids_placeholders = implode(', ', array_fill(0, count($lists), '%d'));

			$where[] = $wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				"subscription_id IN ($ids_placeholders)",
				...array_map('intval', $lists)
			);
		}

		if (! empty($tokens)) {
			$tokens_placeholders = implode(', ', array_fill(0, count($tokens), '%s'));

			$where[] = $wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				"unsubscribe_token IN ($tokens_placeholders)",
				...array_values($tokens)
			);
		}

		$query = "SELECT * FROM $wpdb->blocksy_waitlists";

		if (! empty($where)) {
			$query .= ' WHERE ' . implode(' AND ', $where);
		}

		if ($page) {
			$items_per_page = abs(20);
			$offset  = ($page - 1) * $items_per_page;
			$query .= $wpdb->prepare(
				' LIMIT %d OFFSET %d',
				$items_per_page,
				$offset
			);
		}

		$waitlists = $wpdb->get_results($query); // phpcs:ignore.

		$waitlists = array_filter($waitlists, function ($waitlist) {
			if ($waitlist->variation_id) {
				$product = wc_get_product($waitlist->variation_id);
			} else {
				$product = wc_get_product($waitlist->product_id);
			}

			return !!$product;
		});

		return array_values($waitlists);
	}

    public static function create_subscription($email, $product, $confirmed) {
		global $wpdb;

		$product = wc_get_product($product);

		if (! $product) {
			return false;
		}

		$data = array_merge(
			self::get_product_ids_by_type($product),
			[
				'user_id' => get_current_user_id(),
				'user_email' => $email,
				'confirm_token' => wp_generate_password(24, false),
				'unsubscribe_token' => wp_generate_password(24, false),
				'created_date_gmt' => current_time('mysql', 1),
				'confirmed' => $confirmed
			]
		);

		$wpdb->insert($wpdb->blocksy_waitlists, $data); // phpcs:ignore.

		return [
			'subscription_id' => $wpdb->insert_id,
			'unsubscribe_token' => $data['unsubscribe_token'],
		];
	}

	public static function update_waitlist_data($product, $email, $data) {
		global $wpdb;

		$where = [
			'user_email' => $email,
		];

		$where_product_key = 'variation' === $product->get_type() ? 'variation_id' : 'product_id';
		$where[ $where_product_key ] = $product->get_id();

		$db_row = $wpdb->update( // phpcs:ignore.
			$wpdb->blocksy_waitlists,
			$data,
			$where
		);

		return $db_row ? $db_row : false;
	}

	public static function bulk_update_waitlist_data(
		$waitlist_ids,
		$data
	) {
		global $wpdb;

		$set = [];
		$params = [];

		foreach ($data as $key => $value) {
			$set[] = '%i = %s';
			$params[] = $key;
			$params[] = $value;
		}

		$ids_placeholders = implode(',', array_fill(0, count($waitlist_ids), '%d'));

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$wpdb->query(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				"UPDATE $wpdb->blocksy_waitlists SET " . implode(', ', $set) . " WHERE subscription_id IN ($ids_placeholders)",
				...array_merge($params, array_map('intval', $waitlist_ids))
			)
		);

		return $wpdb->last_error ? false : true;
	}

	public static function update_for_user($user_id) {
		$waitlists = ProductWaitlistDb::get_waitlists_from_cookies();

		if (empty($waitlists)) {
			return;
		}

		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$wpdb->blocksy_waitlists,
			['user_id' => $user_id],
			['user_email' => get_userdata($user_id)->user_email]
		);

		setcookie(ProductWaitlistDb::$cookie_name, false);
	}

    private static function get_product_ids_by_type($product) {
        $product = wc_get_product($product);

		$product_id = $product->get_id();

		if ($product->is_type('variation')) {
			$variation_id = $product_id;
			$product_id = $product->get_parent_id();
		} else {
			$variation_id = null;
		}

		return [
            'product_id' => $product_id,
			'variation_id' => $variation_id,
        ];
	}

    public static function define_tables() {
		global $wpdb;

		$wpdb->blocksy_waitlists = $wpdb->prefix . self::$db_name;
		$wpdb->tables[] = self::$db_name;
	}

    public static function install_db() {
		global $wpdb;

		if (! function_exists('dbDelta')) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . self::$db_name;
		$sql             = "CREATE TABLE $table_name (
					subscription_id bigint(20) NOT NULL AUTO_INCREMENT,
					user_id bigint(20),
					user_email VARCHAR(100) NOT NULL,
					product_id bigint(20) NOT NULL,
					variation_id bigint(20),
					confirmed tinyint(1) NOT NULL DEFAULT 0,
					confirm_token VARCHAR(100),
					unsubscribe_token VARCHAR(100),
					created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					created_date_gmt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					state VARCHAR(100) DEFAULT 'new',
					state_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (subscription_id),
					UNIQUE (confirm_token),
					UNIQUE (unsubscribe_token)
					) $charset_collate;";

        maybe_create_table($table_name, $sql);
	}

	public function upgrade_db() {
		global $wpdb;

		$column = 'state';
		$column_updated = 'state_updated';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$wpdb->query("SHOW COLUMNS FROM $wpdb->blocksy_waitlists LIKE '$column'");

		if (! $wpdb->num_rows) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$wpdb->query("ALTER TABLE $wpdb->blocksy_waitlists ADD COLUMN $column VARCHAR(100) DEFAULT 'new'");
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$wpdb->query("ALTER TABLE $wpdb->blocksy_waitlists ADD COLUMN $column_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		}

		self::cleanup_exported_csv_files();
	}

	// Exports used to be written to
	// uploads/[<yyyy>/<mm>/]blc-waitlist-export/waitlist-<product-slug>.csv and
	// the URL handed back to the browser. The name is predictable and nothing
	// protects the directory, so those files stay anonymously fetchable long
	// after the export happened. Gating the handler does not remove what is
	// already on disk, so remove it once.
	public static function cleanup_exported_csv_files() {
		$option_name = 'blocksy_waitlist_exports_cleaned_up';

		if (get_option($option_name)) {
			return;
		}

		update_option($option_name, true, false);

		$upload_dir = wp_upload_dir(null, false);

		if (! empty($upload_dir['error'])) {
			return;
		}

		$patterns = [
			// uploads_use_yearmonth_folders off.
			$upload_dir['basedir'] . '/blc-waitlist-export',

			// uploads_use_yearmonth_folders on, possibly several months.
			$upload_dir['basedir'] . '/*/*/blc-waitlist-export',
		];

		foreach ($patterns as $pattern) {
			$dirs = glob($pattern, GLOB_ONLYDIR);

			if (! is_array($dirs)) {
				continue;
			}

			foreach ($dirs as $dir) {
				$files = glob($dir . '/*.csv');

				if (is_array($files)) {
					foreach ($files as $file) {
						wp_delete_file($file);
					}
				}

				// Only succeeds when the directory ended up empty, which is
				// exactly when we want it gone.
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
				@rmdir($dir);
			}
		}
	}

	public static function export_users_by_product($product) {
		$waitlists = self::get_waitlists_from_db($product);

		// Built in memory and streamed back through the AJAX response. Nothing
		// is persisted under uploads/ any more.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$csv = fopen('php://temp', 'r+');

		if (! $csv) {
			return false;
		}

		fputcsv($csv, [
			'Email',
			'First Name',
			'Last Name',
			'Confirmed',
			'Subscription Date',
		]);

		foreach ($waitlists as $waitlist) {
			$first_name = '';
			$last_name = '';

			if ($waitlist->user_id) {
				$user = get_userdata($waitlist->user_id);

				if ($user) {
					$first_name = $user->first_name;
					$last_name = $user->last_name;
				}
			}

			$confirmed = 'No';

			if ($waitlist->confirmed) {
				$confirmed = 'Yes';
			}

			fputcsv($csv, array_map(
				[__CLASS__, 'escape_csv_value'],
				[
					$waitlist->user_email,
					$first_name,
					$last_name,
					$confirmed,
					$waitlist->created_date,
				]
			));
		}

		rewind($csv);
		$contents = stream_get_contents($csv);

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose($csv);

		return $contents;
	}

	// Emails and names land in the table through an unauthenticated endpoint
	// and through user profiles, so a value starting with a formula trigger
	// would execute when the CSV is opened in a spreadsheet.
	private static function escape_csv_value($value) {
		$value = (string) $value;

		if ('' === $value) {
			return $value;
		}

		if (! in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
			return $value;
		}

		return "'" . $value;
	}

	public static function unsubscribe_by_product($product_id, $user_email = '') {
		global $wpdb;

		$product = wc_get_product($product_id);

		if (! $product) {
			return;
		}

		$where_product_key = 'product_id';

		if ('variation' === $product->get_type()) {
			$where_product_key = 'variation_id';
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->delete(
			$wpdb->blocksy_waitlists,
			array_merge(
				[
					$where_product_key => $product_id
				],
				! empty($user_email) ? ['user_email' => $user_email] : []
			)
		);
	}
}
