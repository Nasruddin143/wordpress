<?php

// ============================================================
// FAQ META BOX — Dynamic per product/page
// ============================================================
add_action('add_meta_boxes', 'kt_register_faq_meta_box');
function kt_register_faq_meta_box()
{
    add_meta_box(
        'kt_faq_box',
        'FAQs for this Page',
        'kt_faq_meta_box_html',
        ['post', 'page', 'front-page', 'sewing_machine', 'air_cooler', 'service', 'accessory'], // add your CPTs here
        'normal',
        'default'
    );
}

function kt_faq_meta_box_html($post)
{
    wp_nonce_field('kt_faq_save', 'kt_faq_nonce');
    $faqs = get_post_meta($post->ID, '_kt_faqs', true);
    $faqs = is_array($faqs) ? $faqs : [];
?>
    <div id="kt-faq-wrapper">
        <?php foreach ($faqs as $i => $faq) : ?>
            <div class="kt-faq-row" style="border:1px solid #ddd;padding:10px;margin-bottom:8px;">
                <label>Q:</label>
                <input type="text" name="kt_faqs[<?= $i ?>][q]" value="<?= esc_attr($faq['q']) ?>" style="width:100%;margin-bottom:6px;">
                <label>A:</label>
                <textarea name="kt_faqs[<?= $i ?>][a]" rows="3" style="width:100%;"><?= esc_textarea($faq['a']) ?></textarea>
                <button type="button" class="button kt-remove-faq" style="margin-top:4px;color:red;">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="kt-add-faq" class="button button-primary">+ Add FAQ</button>

    <script>
        (function() {
            let count = <?= count($faqs) ?>;
            document.getElementById('kt-add-faq').addEventListener('click', function() {
                const w = document.getElementById('kt-faq-wrapper');
                w.insertAdjacentHTML('beforeend',
                    `<div class="kt-faq-row" style="border:1px solid #ddd;padding:10px;margin-bottom:8px;">
                    <label>Q:</label>
                    <input type="text" name="kt_faqs[${count}][q]" style="width:100%;margin-bottom:6px;">
                    <label>A:</label>
                    <textarea name="kt_faqs[${count}][a]" rows="3" style="width:100%;"></textarea>
                    <button type="button" class="button kt-remove-faq" style="margin-top:4px;color:red;">Remove</button>
                </div>`
                );
                count++;
            });
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('kt-remove-faq')) {
                    e.target.closest('.kt-faq-row').remove();
                }
            });
        })();
    </script>
<?php
}


add_action('save_post', 'kt_save_faq_meta');
function kt_save_faq_meta($post_id)
{
    if (
        ! isset($_POST['kt_faq_nonce']) ||
        ! wp_verify_nonce($_POST['kt_faq_nonce'], 'kt_faq_save') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        ! current_user_can('edit_post', $post_id)
    ) return;

    $faqs = [];
    if (! empty($_POST['kt_faqs']) && is_array($_POST['kt_faqs'])) {
        foreach ($_POST['kt_faqs'] as $faq) {
            $q = sanitize_text_field($faq['q'] ?? '');
            $a = sanitize_textarea_field($faq['a'] ?? '');
            if ($q && $a) {
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }
    }
    update_post_meta($post_id, '_kt_faqs', $faqs);

    // 🔥 Bust transient cache for this post
    delete_transient('kt_faq_schema_' . $post_id);
}

add_filter('rank_math/json_ld', 'kt_inject_faq_schema', 99, 2);
function kt_inject_faq_schema($data, $jsonld)
{

    if (! is_singular()) return $data;

    $post_id = get_the_ID();

    // ✅ TRANSIENT CACHE — prevents repeated DB hits on every page load
    $cache_key = 'kt_faq_schema_' . $post_id;
    $schema    = get_transient($cache_key);

    if (false === $schema) {
        $faqs = get_post_meta($post_id, '_kt_faqs', true);

        if (empty($faqs) || ! is_array($faqs)) {
            set_transient($cache_key, 'empty', 12 * HOUR_IN_SECONDS);
            return $data;
        }

        $entities = [];
        foreach ($faqs as $faq) {
            $entities[] = [
                '@type'          => 'Question',
                'name'           => wp_strip_all_tags($faq['q']),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags($faq['a']),
                ],
            ];
        }

        $schema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];

        // Cache for 12 hours; busted on save_post above
        set_transient($cache_key, $schema, 12 * HOUR_IN_SECONDS);
    }

    if ($schema !== 'empty') {
        $data['kt_faqpage'] = $schema;
    }

    return $data;
}


// Put this where you want FAQ to appear in your template:
// <?php kt_render_faq_section();

function kt_render_faq_section()
{
    $post_id = get_the_ID();
    $faqs    = get_post_meta($post_id, '_kt_faqs', true);

    if (empty($faqs) || !is_array($faqs)) return;

    $accordion_id = 'ktFaqAccordion-' . $post_id;
    echo '<div class="card border-0 shadow">';
    echo '<div class="card-header bg-white px-4 py-3">';
    echo '<h4 class="mb-0 fw-bold">Frequently Asked Questions</h4>';
    echo '</div>';
    echo '<div class="card-body p-0">';

    echo '<section class="kt-faq-section">';

    echo '<div class="accordion accordion-flush" id="' . $accordion_id . '">';

    foreach (array_slice($faqs, 0, 10) as $index => $faq) {

        $heading_id = 'faqHeading-' . $post_id . '-' . $index;
        $collapse_id = 'faqCollapse-' . $post_id . '-' . $index;

        echo '<div class="accordion-item">';

        echo '<h2 class="accordion-header" id="' . $heading_id . '">';
        echo '<button class="fw-bold accordion-button ' . ($index !== 0 ? 'collapsed' : '') . '" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#' . $collapse_id . '" 
                aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" 
                aria-controls="' . $collapse_id . '">';
        echo esc_html('Q: ' . $faq['q']);
        echo '</button>';
        echo '</h2>';

        echo '<div id="' . $collapse_id . '" 
                class="accordion-collapse collapse ' . ($index === 0 ? 'show' : '') . '" 
                aria-labelledby="' . $heading_id . '" 
                data-bs-parent="#' . $accordion_id . '">';

        echo '<div class="accordion-body">';
        echo esc_html('A: ' . $faq['a']);
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</section>';
    echo '</div>';
    echo '</div>';
}
