<?php

namespace KT\Modules;

defined('ABSPATH') || exit;

class FAQ
{

    private $meta_key = '_kt_faqs';

    public function __construct()
    {

        add_action('add_meta_boxes', [$this, 'register_meta_box']);
        add_action('save_post', [$this, 'save']);
        // add_filter('rank_math/json_ld', [$this, 'schema'], 99, 2);
    }

    /**
     * ----------------------------------------
     * META BOX
     * ----------------------------------------
     */
    public function register_meta_box()
    {

        add_meta_box(
            'kt_faq_box',
            __('FAQs for this Page', 'kt'),
            [$this, 'render_meta_box'],
            ['post', 'page', 'sewing_machine', 'air_cooler', 'service', 'accessory'],
            'normal',
            'default'
        );
    }

    public function render_meta_box($post)
    {

        wp_nonce_field('kt_faq_save', 'kt_faq_nonce');

        $faqs = get_post_meta($post->ID, $this->meta_key, true);
        $faqs = is_array($faqs) ? $faqs : [];

?>
        <div id="kt-faq-wrapper">

            <?php foreach ($faqs as $i => $faq): ?>
                <div class="kt-faq-row" style="border:1px solid #ddd;padding:10px;margin-bottom:8px;">
                    <label>Q:</label>
                    <input type="text" name="kt_faqs[<?php echo $i; ?>][q]"
                        value="<?php echo esc_attr($faq['q']); ?>" style="width:100%;margin-bottom:6px;">

                    <label>A:</label>
                    <textarea name="kt_faqs[<?php echo $i; ?>][a]" rows="3"
                        style="width:100%;"><?php echo esc_textarea($faq['a']); ?></textarea>

                    <button type="button" class="button kt-remove-faq" style="color:red;">Remove</button>
                </div>
            <?php endforeach; ?>

        </div>

        <button type="button" id="kt-add-faq" class="button button-primary">+ Add FAQ</button>

        <script>
            (function() {
                let count = <?php echo count($faqs); ?>;

                document.getElementById('kt-add-faq').addEventListener('click', function() {
                    const w = document.getElementById('kt-faq-wrapper');

                    w.insertAdjacentHTML('beforeend', `
                    <div class="kt-faq-row" style="border:1px solid #ddd;padding:10px;margin-bottom:8px;">
                        <label>Q:</label>
                        <input type="text" name="kt_faqs[${count}][q]" style="width:100%;">
                        <label>A:</label>
                        <textarea name="kt_faqs[${count}][a]" rows="3" style="width:100%;"></textarea>
                        <button type="button" class="button kt-remove-faq" style="color:red;">Remove</button>
                    </div>
                `);

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

    /**
     * ----------------------------------------
     * SAVE
     * ----------------------------------------
     */
    public function save($post_id)
    {

        if (
            !isset($_POST['kt_faq_nonce']) ||
            !wp_verify_nonce($_POST['kt_faq_nonce'], 'kt_faq_save') ||
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            !current_user_can('edit_post', $post_id)
        ) return;

        $faqs = [];

        if (!empty($_POST['kt_faqs']) && is_array($_POST['kt_faqs'])) {

            foreach ($_POST['kt_faqs'] as $faq) {

                $q = sanitize_text_field($faq['q'] ?? '');
                $a = sanitize_textarea_field($faq['a'] ?? '');

                if ($q && $a) {
                    $faqs[] = ['q' => $q, 'a' => $a];
                }
            }
        }

        update_post_meta($post_id, $this->meta_key, $faqs);
    }

    /**
     * ----------------------------------------
     * SCHEMA (NO CACHE)
     * ----------------------------------------
     */
    public function schema($data)
    {

        if (!is_singular()) return $data;

        $post_id = get_the_ID();
        $faqs = get_post_meta($post_id, $this->meta_key, true);

        if (empty($faqs) || !is_array($faqs)) return $data;

        $entities = [];

        foreach ($faqs as $faq) {
            $entities[] = [
                '@type' => 'Question',
                'name'  => wp_strip_all_tags($faq['q']),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags($faq['a']),
                ],
            ];
        }

        $data['kt_faqpage'] = [
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => $entities,
        ];

        return $data;
    }

    /**
     * ----------------------------------------
     * FRONTEND RENDER
     * ----------------------------------------
     */
    public static function render()
    {

        $post_id = get_the_ID();
        $faqs = get_post_meta($post_id, '_kt_faqs', true);

        if (empty($faqs) || !is_array($faqs)) return;

        $accordion_id = 'ktFaq-' . $post_id;
    ?>

        <div class="card border-0 shadow">
            <div class="card-header bg-white px-4 py-3">
                <h4 class="fw-bold mb-0"><?php _e('Frequently Asked Questions', 'kt'); ?></h4>
            </div>

            <div class="accordion accordion-flush" id="<?php echo esc_attr($accordion_id); ?>">

                <?php foreach ($faqs as $i => $faq): ?>

                    <div class="accordion-item">

                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $i ? 'collapsed' : ''; ?>"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-<?php echo $i; ?>">

                                <?php echo esc_html($faq['q']); ?>
                            </button>
                        </h2>

                        <div id="faq-<?php echo $i; ?>"
                            class="accordion-collapse collapse <?php echo !$i ? 'show' : ''; ?>">

                            <div class="accordion-body">
                                <?php echo esc_html($faq['a']); ?>
                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>
        </div>

<?php
    }
}
