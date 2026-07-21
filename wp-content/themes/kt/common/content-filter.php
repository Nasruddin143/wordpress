<?php

$post_type = get_query_var('post_type');

if (empty($post_type)) {
    $post_type = 'post';
}

if (is_array($post_type)) {
    $post_type = reset($post_type);
}

$taxonomies = get_object_taxonomies($post_type, 'objects');

if ($taxonomies):
    ?>

    <div class="card archive-filters">
        <div class="card-body px-0 pb-0">

            <h5 class="card-title fw-bold px-3 pb-3 mb-0 border-bottom">FILTERS</h5>

            <div class="accordion accordion-flush" id="taxonomyAccordion">

                <?php
                $i = 1;

                foreach ($taxonomies as $taxonomy):

                    if (!$taxonomy->public) {
                        continue;
                    }

                    $terms = get_terms([
                        'taxonomy' => $taxonomy->name,
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ]);

                    if (!empty($terms) && !is_wp_error($terms)):

                        $heading_id = 'heading' . $i;
                        $collapse_id = 'collapse' . $i;
                        ?>

                        <div class="accordion-item">

                            <h2 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                                <button class="accordion-button <?php echo ($i != 1) ? 'collapsed' : ''; ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr($collapse_id); ?>"
                                    aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>"
                                    aria-controls="<?php echo esc_attr($collapse_id); ?>">
                                    <strong>
                                        <?php echo esc_html($taxonomy->labels->name); ?>
                                    </strong>
                                </button>
                            </h2>

                            <div id="<?php echo esc_attr($collapse_id); ?>"
                                class="accordion-collapse collapse <?php echo ($i == 1) ? 'show' : ''; ?>"
                                data-bs-parent="#taxonomyAccordion">

                                <div class="accordion-body p-0">

                                    <div class="list-group list-group-flush">

                                        <a class="list-group-item list-group-item-action"
                                            href="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-app me-2" viewBox="0 0 16 16">
                                                <path
                                                    d="M11 2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3zM5 1a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4V5a4 4 0 0 0-4-4z" />
                                            </svg>
                                            All Products
                                        </a>

                                        <?php foreach ($terms as $term): ?>

                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo esc_url(get_term_link($term)); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-app me-2" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3zM5 1a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4V5a4 4 0 0 0-4-4z" />
                                                </svg>
                                                <?php echo esc_html($term->name); ?>
                                            </a>

                                        <?php endforeach; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <?php
                        $i++;
                    endif;

                endforeach;
                ?>

            </div>

        <?php endif; ?>

    </div>
</div>