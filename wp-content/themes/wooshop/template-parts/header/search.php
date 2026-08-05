<?php
/**
 * Header Search
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="ws-header-search">

    <form role="search"
          method="get"
          class="ws-search-form"
          action="<?php echo esc_url(home_url('/')); ?>">

        <input
                type="search"
                class="form-control ws-search-input"
                placeholder="<?php esc_attr_e('Search products...', 'wooshop'); ?>"
                value="<?php echo esc_attr(get_search_query()); ?>"
                name="s"
        />

        <input type="hidden" name="post_type" value="product">

        <button
                class="btn btn-primary ws-search-button"
                type="submit"
                aria-label="<?php esc_attr_e('Search', 'wooshop'); ?>">

            <!-- Search Icon -->
            <svg class="ws-icon"
                 width="18"
                 height="18"
                 viewBox="0 0 24 24"
                 fill="none"
                 xmlns="http://www.w3.org/2000/svg">

                <circle cx="11" cy="11" r="7"
                        stroke="currentColor"
                        stroke-width="2"/>

                <line x1="20"
                      y1="20"
                      x2="16.5"
                      y2="16.5"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"/>

            </svg>

        </button>

    </form>

</div>