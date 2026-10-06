<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Settings {
	protected static $instance = null;
	protected $dropdown_class = 'vi-ui dropdown fluid wvr-dropdown';

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function __construct() {
		add_action( 'wvr_admin_field_rating_rate', [ $this, 'rating_rate_field' ], 10, 2 );
		add_action( 'wvr_admin_field_bought_quantity', [ $this, 'bought_quantity_field' ], 10, 2 );
		add_action( 'wvr_admin_field_names', [ $this, 'names_field' ], 10, 2 );
		add_action( 'wvr_admin_field_review_rules', [ $this, 'review_rules_field' ], 10, 2 );
		add_action( 'wvr_admin_field_reply_content', [ $this, 'reply_content_field' ], 10, 2 );
		add_action( 'wvr_admin_field_cmt_frontend', [ $this, 'cmt_frontend_field' ], 10, 2 );
		add_action( "wvr_after_field_auto_update_key", [ $this, 'auto_update_key_field' ] );

		add_action( 'admin_init', [ $this, 'save_settings' ] );
	}

	public function settings_page() {
		$tabs      = $this->define_tabs();
		$first_tab = array_key_first( $tabs );
		?>
        <h1>
			<?php echo esc_html( WVR_CONST['plugin_name'] ) . ' ' . esc_html__( 'Settings', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </h1>
        <form method="post" id="wvr-settings-form" class="vi-ui small form">
			<?php wp_nonce_field( 'wvr-settings' ); ?>

            <div class="vi-ui top attached tabular menu">
				<?php
				foreach ( $tabs as $slug => $text ) {
					$active = $first_tab == $slug ? 'active' : '';
					printf( ' <a class="item %s" data-tab="%s">%s</a>', esc_attr( $active ), esc_attr( $slug ), esc_html( $text ) );
				}
				?>
            </div>
			<?php
			foreach ( $tabs as $slug => $text ) {
				$active = $first_tab == $slug ? 'active' : '';
				$method = $slug . '_options';

				printf( '<div class="vi-ui bottom attached %s tab segment" data-tab="%s">', esc_attr( $active ), esc_attr( $slug ) );

				if ( method_exists( $this, $method ) ) {
					$options = $this->$method();
					Settings_Helper::output_fields( $options );
				} else {
					do_action( 'wvr_settings_tab', $slug );
				}

				echo '</div>';
			}
			?>
            <p class="wvr-save-settings-container">
                <button type="submit" class="vi-ui button labeled icon primary wvr-save-settings" name="wvr_save_settings" value="save_setting">
                    <i class="save icon"> </i>
					<?php esc_html_e( 'Save Settings', 'faview-virtual-reviews-for-woocommerce' ); ?>
                </button>
                <button type="submit" class="vi-ui button labeled icon" name="wvr_save_settings" value="save_n_check_key">
                    <i class="send icon"> </i>
					<?php esc_html_e( 'Save & Check key', 'faview-virtual-reviews-for-woocommerce' ); ?>
                </button>
            </p>
        </form>
		<?php
		do_action( 'villatheme_support_faview-virtual-reviews-for-woocommerce' );
	}

	public function define_tabs() {
		return [
			'review'      => esc_html__( 'Review', 'faview-virtual-reviews-for-woocommerce' ),
			'reply'       => esc_html__( 'Reply', 'faview-virtual-reviews-for-woocommerce' ),
			'frontend'    => esc_html__( 'Review form', 'faview-virtual-reviews-for-woocommerce' ),
			'pro_support' => esc_html__( 'Update', 'faview-virtual-reviews-for-woocommerce' ),
		];
	}

	public function review_options() {
		$options = [
			[ 'type' => 'section_start' ],
			[
				'id'    => 'rating_rate',
				'title' => esc_html__( 'Rating', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'  => esc_html__( 'Sum of all rating must be equal 100%', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'bought_quantity',
				'title' => esc_html__( 'Quantity of bought product', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'  => esc_html__( 'Random quantity in this range', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'unique_name',
				'title' => esc_html__( 'Unique author', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
				'desc'  => esc_html__( 'Check author name is exist. If exist, random other name', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'unique_cmt',
				'title' => esc_html__( 'Unique comment content', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
				'desc'  => esc_html__( 'Check comment content is exist. If exist, random other comment content', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'      => 'names',
				'title'   => esc_html__( 'Author', 'faview-virtual-reviews-for-woocommerce' ),
				'require' => true
			],
			[
				'id'    => 'review_rules',
				'title' => esc_html__( 'Review comment rules', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'add_empty_comment',
				'title' => esc_html__( 'Add review with no comment', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
				'desc'  => esc_html__( 'If no rule is matched, add review with no comment. Or you need to add a blank review into review list', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'verified_owner',
				'title' => esc_html__( 'Add verified owner', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
			],
			[ 'type' => 'section_end' ],
		];

		if ( function_exists( 'icl_get_languages' ) ) {
			$langs        = icl_get_languages();
			$lang_options = wp_list_pluck( $langs, 'native_name', 'language_code' );

			array_splice( $options, 5, 0, [
				[
					'id'          => 'add_to_other_langs',
					'title'       => esc_html__( 'Add to languages', 'epoi-wp-point-reward' ),
					'type'        => 'multiselect',
					'options'     => $lang_options,
					'class'       => $this->dropdown_class,
					'placeholder' => esc_html__( 'All languages', 'faview-virtual-reviews-for-woocommerce' ),
				]
			] );
		}

		return $options;
	}

	public function reply_options() {
		$user_options  = [];
		$reply_user_id = Data::instance()->get_param( 'reply_author' );
		if ( $reply_user_id ) {
			$user                           = get_user_by( 'id', $reply_user_id );
			$user_options[ $reply_user_id ] = $user->display_name;
		}

		return [
			[ 'type' => 'section_start' ],

			[
				'id'    => 'enable_reply_virtual_review',
				'title' => esc_html__( 'Use for virtual review', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
			],

			[
				'id'    => 'enable_reply_real_review',
				'title' => esc_html__( 'Use for real review', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
			],

			[
				'id'      => 'reply_author',
				'require' => true,
				'title'   => esc_html__( 'Reply author', 'faview-virtual-reviews-for-woocommerce' ),
				'type'    => 'select',
				'options' => $user_options,
				'class'   => 'wvr-reply-author',
			],

			[
				'id'    => 'reply_content',
				'title' => esc_html__( 'Reply content', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[ 'type' => 'section_end' ],
		];
	}

	public function frontend_options() {
		$style_option = [ 'select' => esc_html__( 'Dropdown', 'faview-virtual-reviews-for-woocommerce' ), 'slide' => esc_html__( 'Slide', 'faview-virtual-reviews-for-woocommerce' ) ];

		return [
			[ 'type' => 'section_start' ],

			[
				'id'    => 'auto_rating',
				'title' => esc_html__( 'Auto select 5 star', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
				'desc'  => esc_html__( 'Auto select 5-star rating', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'auto_fill_review',
				'title' => esc_html__( 'Auto fill review', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'text',
			],

			[ 'type' => 'section_end' ],

			[
				'type'  => 'section_start',
				'title' => esc_html__( 'Canned review', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[
				'id'    => 'show_canned',
				'title' => esc_html__( 'Show canned reviews', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
			],

			[
				'id'    => 'cmt_frontend',
				'title' => esc_html__( 'Canned reviews', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'      => 'canned_style_desktop',
				'title'   => esc_html__( 'Style for Desktop', 'faview-virtual-reviews-for-woocommerce' ),
				'type'    => 'select',
				'options' => $style_option,
				'class'   => $this->dropdown_class,
			],

			[
				'id'      => 'canned_style_mobile',
				'title'   => esc_html__( 'Style for Mobile', 'faview-virtual-reviews-for-woocommerce' ),
				'type'    => 'select',
				'options' => $style_option,
				'class'   => $this->dropdown_class,
			],

			[
				'id'    => 'canned_text_color',
				'title' => esc_html__( 'Text color', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'color',
				'desc'  => esc_html__( 'Text color of canned reviews on the slider', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'canned_bg_color',
				'title' => esc_html__( 'Background color', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'color',
				'desc'  => esc_html__( 'Background color of canned reviews on the slider', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'canned_text_hover_color',
				'title' => esc_html__( 'Text color on hover', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'color',
				'desc'  => esc_html__( 'Text color on hover of canned reviews on the slider', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'canned_hover_color',
				'title' => esc_html__( 'Background color on hover', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'color',
				'desc'  => esc_html__( 'Background color on hover of canned reviews on the slider', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[ 'type' => 'section_end' ],

			[
				'type'  => 'section_start',
				'title' => esc_html__( 'Purchased label', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'show_purchased_label',
				'title' => esc_html__( 'Show purchased label', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'checkbox',
			],
			[
				'id'      => 'purchased_label_icon',
				'title'   => esc_html__( 'Purchased icon', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'    => esc_html__( 'Purchased icon for front-end display', 'faview-virtual-reviews-for-woocommerce' ),
				'type'    => 'radio',
				'options' => [
					[
						'value' => '',
						'icon'  => 'wvr-icon-no-icon'
					],
					[
						'value' => 'e900',
						'icon'  => 'wvr-icon-shopping-bag'
					],
					[
						'value' => 'e902',
						'icon'  => 'wvr-icon-cart-arrow-down'
					],
					[
						'value' => 'e93f',
						'icon'  => 'wvr-icon-credit-card'
					],
					[
						'value' => 'e903',
						'icon'  => 'wvr-icon-currency-dollar'
					],
					[
						'value' => 'e904',
						'icon'  => 'wvr-icon-location-shopping'
					],
				]
			],

			[
				'id'    => 'purchased_icon_color',
				'type'  => 'color',
				'title' => esc_html__( 'Purchased icon color', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'  => esc_html__( 'Purchased icon color for front-end display', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'purchased_text_color',
				'type'  => 'color',
				'title' => esc_html__( 'Purchased label text color', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'  => esc_html__( 'Purchased label text color for front-end display', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'purchased_bg_color',
				'type'  => 'color',
				'title' => esc_html__( 'Purchased label background color', 'faview-virtual-reviews-for-woocommerce' ),
				'desc'  => esc_html__( 'Purchased label background color for front-end display', 'faview-virtual-reviews-for-woocommerce' ),
			],

			[
				'id'    => 'custom_css',
				'type'  => 'textarea',
				'title' => esc_html__( 'Custom CSS', 'faview-virtual-reviews-for-woocommerce' ),
			],
			[ 'type' => 'section_end' ],

		];
	}

	public function pro_support_options() {
		$options = [
			[ 'type' => 'section_start' ],
			[
				'id'    => 'auto_update_key',
				'title' => esc_html__( 'Auto update key', 'faview-virtual-reviews-for-woocommerce' ),
				'type'  => 'text',
				'desc'  => sprintf(
					'%s <a target="_blank" href="https://villatheme.com/my-download">https://villatheme.com/my-download</a>
                            . %s 
                            <a target="_blank" href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/">%s</a>',
					esc_html__( 'Please fill your key what you get from', 'faview-virtual-reviews-for-woocommerce' ),
					esc_html__( 'You can auto update this plugin.', 'faview-virtual-reviews-for-woocommerce' ),
					esc_html__( 'See guide', 'faview-virtual-reviews-for-woocommerce' ) ),

				'unit' => sprintf( '<div class="wvr-get-auto-update-key villatheme-get-key-button vi-ui button small green" 
                                                data-href="https://api.envato.com/authorization?response_type=code&client_id=villatheme-download-keys-6wzzaeue&redirect_uri=https://villatheme.com/update-key"
                                                data-id="%s">%s</div>', esc_attr( WVR_CONST['codecanyon_pid'] ), esc_html__( 'Get key', 'faview-virtual-reviews-for-woocommerce' ) )
			],
			[ 'type' => 'section_end' ],
		];

		return $options;
	}

	public function rating_rate_field( $value, $option ) {
		?>
        <div class="six fields">
			<?php
			for ( $i = 1; $i <= 5; $i ++ ) {
				$percentage = $value[ $i ] ?? 0;
				?>
                <div class="field">
                    <div class="vi-ui right labeled input ">
                        <label class="vi-ui basic label"><?php echo esc_html( $i . '&#9733;' ) ?></label>
						<?php
						printf( "<input type='number' name='wvr_rating_rate[%d]' class='wvr-rating-rate' min='0' max='100' value='%d'/>", esc_attr( $i ), esc_attr( $percentage ) );
						?>
                        <div class="vi-ui basic label">%</div>
                    </div>
                </div>
				<?php
			}
			?>
            <div class="field">
                <div class="wvr-rating-rate-total"><span>=</span> <span class="wvr-rating-rate-total-value"> </span><span>%</span></div>
            </div>
        </div>
		<?php
	}

	public function bought_quantity_field( $value, $option ) {
		$from = $value['from'] ?? 1;
		$to   = $value['to'] ?? 1;
		?>
        <div class="inline  two fields">
            <div class=" eight wide field">
                <div class="vi-ui labeled input">
                    <div class="vi-ui label">
						<?php esc_html_e( 'From', 'faview-virtual-reviews-for-woocommerce' ) ?>
                    </div>
                    <input type="number" min="1" name="wvr_bought_quantity[from]" value="<?php echo esc_html( $from ) ?>">
                </div>
            </div>
            <div class=" eight wide field">
                <div class="vi-ui labeled input">
                    <div class="vi-ui label">
						<?php esc_html_e( 'To', 'faview-virtual-reviews-for-woocommerce' ) ?>
                    </div>
                    <input type="number" min="1" name="wvr_bought_quantity[to]" value="<?php echo esc_html( $to ) ?>">
                </div>
            </div>
        </div>
		<?php
	}

	public function names_field( $value, $option ) {
		?>
        <div class="wvr-names-field"></div>
		<?php
		if ( function_exists( 'icl_get_languages' ) ) {
			$translations = icl_get_languages();
			$default_lang = icl_get_default_language();
			if ( ! empty( $translations ) ) {
				?>
                <div class="vi-ui action input">
                    <select class="vi-ui compact search dropdown fluid wvr-names-language-list">
                        <option value=""><?php esc_html_e( 'Add author names in other language', 'faview-virtual-reviews-for-woocommerce' ) ?></option>
						<?php
						foreach ( $translations as $code => $data ) {
							if ( $code == $default_lang ) {
								continue;
							}
							printf( '<option value="%s" >%s</option>', esc_attr( $code ), esc_html( $data['native_name'] ) );
						}
						?>
                    </select>
                    <div class="vi-ui button icon wvr-add-language-author"><i class="icon plus"> </i></div>
                </div>
				<?php
			}
		}
	}

	public function review_rules_field() {
		?>
        <div class="wvr-review-rules-section"></div>
        <span class="vi-ui button small labeled icon wvr-add-review-rule">
            <i class="icon plus"> </i>
            <?php esc_html_e( 'Add rule', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </span>
        <p class="wvr-description">
			<?php esc_html_e( 'Priority: Top to bottom', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </p>
        <p class="wvr-description">
			<?php esc_html_e( "In case the rule contains empty condition fields, leave it at the bottom as it applies to all products. If there's another rule underneath, it will be ignored", 'faview-virtual-reviews-for-woocommerce' ); ?>
        </p>
		<?php
	}

	public function reply_content_field() {
		?>
        <div class="wvr-reply-content-section wvr-translatable-section"></div>
		<?php
		if ( function_exists( 'icl_get_languages' ) ) {
			$translations = icl_get_languages();
			$default_lang = icl_get_default_language();
			if ( ! empty( $translations ) ) {
				?>
                <div class="vi-ui action input">
                    <select class="vi-ui compact search dropdown fluid wvr-replys-language-list">
                        <option value=""><?php esc_html_e( 'Add reply in other language', 'faview-virtual-reviews-for-woocommerce' ) ?></option>
						<?php
						foreach ( $translations as $code => $data ) {
							if ( $code == $default_lang ) {
								continue;
							}
							printf( '<option value="%s" >%s</option>', esc_attr( $code ), esc_html( $data['native_name'] ) );
						}
						?>
                    </select>
                    <div class="vi-ui button icon wvr-add-language-reply"><i class="icon plus"> </i></div>
                </div>
				<?php
			}
		}
	}

	public function cmt_frontend_field() {
		?>
        <div class="wvr-cmt-frontend-section wvr-translatable-section"></div>
		<?php
		if ( function_exists( 'icl_get_languages' ) ) {
			$translations = icl_get_languages();
			$default_lang = icl_get_default_language();
			if ( ! empty( $translations ) ) {
				?>
                <div class="vi-ui action input">
                    <select class="vi-ui compact search dropdown fluid wvr-cmt-frontend-language-list">
                        <option value=""><?php esc_html_e( 'Add reply in other language', 'faview-virtual-reviews-for-woocommerce' ) ?></option>
						<?php
						foreach ( $translations as $code => $data ) {
							if ( $code == $default_lang ) {
								continue;
							}
							printf( '<option value="%s" >%s</option>', esc_attr( $code ), esc_html( $data['native_name'] ) );
						}
						?>
                    </select>
                    <div class="vi-ui button icon wvr-add-language-cmt"><i class="icon plus"> </i></div>
                </div>
				<?php
			}
		}
	}

	public function auto_update_key_field() {
		do_action( 'faview-virtual-reviews-for-woocommerce_key' );
	}

	public function save_settings() {
		if ( isset( $_POST['wvr_save_settings'], $_POST['_wpnonce'] ) && $_POST['wvr_save_settings']
		     && wp_verify_nonce( $_POST['_wpnonce'], 'wvr-settings' ) && current_user_can( 'manage_options' ) ) {

			try {
				$tabs    = $this->define_tabs();
				$options = [];
				foreach ( $tabs as $slug => $text ) {
					$method = $slug . '_options';
					if ( method_exists( $this, $method ) ) {
						$options = array_merge( $options, $this->$method() );
					} else {
						$options = array_merge( $options, apply_filters( 'wvr_save_setting_option', [], $slug ) );
					}
				}

				$options = apply_filters( 'wvr_settings_before_save', $options );

				add_filter( 'wvr_admin_settings_sanitize_option_names', [ $this, 'sanitize_textarea_to_array' ], 10, 3 );
				add_filter( 'wvr_admin_settings_sanitize_option_cmt_frontend', [ $this, 'sanitize_textarea_to_array' ], 10, 3 );
				add_filter( 'wvr_admin_settings_sanitize_option_reply_content', [ $this, 'sanitize_reply_content' ], 10, 3 );
				add_filter( 'wvr_admin_settings_sanitize_option_review_rules', [ $this, 'sanitize_review_rules' ], 10, 3 );

				if ( 'save_n_check_key' == $_POST['wvr_save_settings'] ) {
					delete_site_transient( 'update_plugins' );
					delete_transient( 'villatheme_item_21055' );
					delete_option( 'faview-virtual-reviews-for-woocommerce_messages' );
					do_action( 'villatheme_save_and_check_key_faview-virtual-reviews-for-woocommerce', sanitize_text_field( $_POST['wvr_auto_update_key'] ) );
				}

				Settings_Helper::save_fields( $options );

			} catch ( \Exception $e ) {
				echo esc_html( $e->getMessage() );
			}
		}
	}

	public function sanitize_textarea_to_array( $value, $option, $raw_value ) {
		if ( empty( $raw_value ) || ! is_array( $raw_value ) ) {
			return [];
		}

		foreach ( $raw_value as $key => $names ) {
			$names         = explode( "\n", wp_kses_post( $names ) );
			$value[ $key ] = array_map( 'trim', $names );
		}

		return $value;
	}

	public function sanitize_review_rules( $value, $option, $raw_value ) {
		if ( empty( $raw_value ) || ! is_array( $raw_value ) ) {
			return [];
		}

		$ids = [];

		foreach ( $raw_value as $rule_key => $rule ) {
			if ( empty( $rule['comments'] ) || ! is_array( $rule['comments'] ) ) {
				continue;
			}

			$cmt_array = [];

			foreach ( $rule['comments'] as $lang => $comments ) {
				if ( empty( $comments ) || ! is_array( $comments ) ) {
					continue;
				}

				foreach ( $comments as $rate => $comment ) {
					if ( empty( $comment ) ) {
						continue;
					}
					$cmt_list = explode( "\n", wp_kses_post( $comment ) );
//					$cmt_list = array_values($cmt_list);
					$cmt_array[ $lang ][ $rate ] = $cmt_list;
				}
			}

			if ( empty( $cmt_array ) ) {
				unset( $value[ $rule_key ] );
			} else {
				$rule             = wc_clean( $rule );
				$rule['comments'] = $cmt_array;
				$ids[]            = $rule['rule_id'];
				update_option( "wvr_cmt_rule_{$rule['rule_id']}", $rule );
			}
		}

		return $ids;
	}

	public function sanitize_reply_content( $value, $option, $raw_value ) {
		if ( empty( $raw_value ) || ! is_array( $raw_value ) ) {
			return [];
		}

		foreach ( $raw_value as $lang => $content ) {
			if ( empty( $content ) ) {
				continue;
			}

			foreach ( $content as $rate => $replies ) {
				$value[ $lang ][ $rate ] = explode( "\n", wp_kses_post( $replies ) );
			}
		}

		return $value;
	}
}
