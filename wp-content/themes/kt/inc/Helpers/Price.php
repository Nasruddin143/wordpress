<?php
namespace KT\Helpers;

defined('ABSPATH') || exit;

class Price
{
    /**
     * ----------------------------------------
     * GET PRICES (RAW)
     * ----------------------------------------
     */
    public static function get($post_id)
    {
        if (!function_exists('get_field')) {
            return [
                'actual' => 0,
                'final'  => 0,
            ];
        }

        return [
            'actual' => (float) get_field('actual_price', $post_id),
            'final'  => (float) get_field('final_price', $post_id),
        ];
    }

    /**
     * ----------------------------------------
     * DISCOUNT %
     * ----------------------------------------
     */
    public static function get_discount_percent($actual, $final)
    {
        if ($actual > 0 && $final > 0 && $actual > $final) {
            return round((($actual - $final) / $actual) * 100);
        }
        return 0;
    }


    public static function show_discount_percent($post_id = null) {

        $post_id = $post_id ?: get_the_ID();

        if (!function_exists('get_field')) return 0;

        $actual = (float) get_field('actual_price', $post_id);
        $final  = (float) get_field('final_price', $post_id);

        if ($actual > $final && $actual > 0) {
            return round((($actual - $final) / $actual) * 100);
        }

        return 0;
    }

    /**
     * ----------------------------------------
     * SAVE AMOUNT
     * ----------------------------------------
     */
    public static function get_saving($actual, $final)
    {
        if ($actual > $final) {
            return $actual - $final;
        }
        return 0;
    }

    /**
     * ----------------------------------------
     * FORMAT PRICE
     * ----------------------------------------
     */
    public static function format($amount, $currency = '₹')
    {
        return $currency . number_format($amount, 0);
    }

    /**
     * ----------------------------------------
     * MAIN RENDER (REPLACES YOUR FUNCTION)
     * ----------------------------------------
     */
    public static function render($post_id = null, $args = [])
    {
        $post_id = $post_id ?: get_the_ID();

        $defaults = [
            'show_discount' => true,
            'show_diff'     => true,
            'currency'      => '₹',
        ];

        $args = wp_parse_args($args, $defaults);

        $price = self::get($post_id);

        $actual = $price['actual'];
        $final  = $price['final'];

        if (!$final) return '';

        $discount = self::get_discount_percent($actual, $final);
        $saving   = self::get_saving($actual, $final);

        ob_start();
        ?>

        <div class="kt-product-price">

            <span class="kt-price-final text-danger fw-bold me-1">
                <?php echo esc_html(self::format($final, $args['currency'])); ?>
            </span>

            <?php if ($args['show_discount'] && $discount): ?>
                <span class="kt-price-actual fw-bold text-secondary text-decoration-line-through">
                    <?php echo esc_html(self::format($actual, $args['currency'])); ?>
                </span>

                <span class="kt-discount-badge text-success fw-bold ms-2">
                    <?php echo esc_html($discount . '% OFF'); ?>
                </span>
            <?php endif; ?>

            <?php if ($args['show_diff'] && $saving): ?>
                <span class="kt-price-save fw-bold text-secondary">
                    <?php
                    echo sprintf(
                        esc_html__('| You save %s', 'kt'),
                        self::format($saving, $args['currency'])
                    );
                    ?>
                </span>
            <?php endif; ?>

        </div>

        <?php
        return ob_get_clean();
    }
}