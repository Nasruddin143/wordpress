<?php
/**
 * WooShop Footer Widgets
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$config = $args['config'] ?? [];

if (!is_array($config)) {
    return;
}

$widget_areas = $config['widget_areas'] ?? [];

if (!is_array($widget_areas)) {
    return;
}

$footer_areas = [];

foreach ($widget_areas as $id => $widget_area) {
    if (is_string($id) && str_starts_with($id, 'footer-')) {
        $footer_areas[] = $id;
    }
}

if ($footer_areas === []) {
    return;
}

$column_count = count($footer_areas);

$column_class = match (true) {
    $column_count === 1 => 'col-12',
    $column_count === 2 => 'col-12 col-md-6',
    $column_count === 3 => 'col-12 col-md-6 col-lg-4',
    default => 'col-12 col-md-6 col-lg-3',
};
?>

<div class="row g-4">

    <?php foreach ($footer_areas as $sidebar_id) : ?>

        <div class="<?php echo esc_attr($column_class); ?>">

            <?php
            if (is_active_sidebar($sidebar_id)) {
                dynamic_sidebar($sidebar_id);
            }
            ?>

        </div>

    <?php endforeach; ?>

</div>
