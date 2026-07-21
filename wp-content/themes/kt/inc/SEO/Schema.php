<?php
namespace KT\SEO;

class Schema {

    public function __construct() {
        //add_action('wp_head', [$this, 'output_schema']);
    }

    public function output_schema() {
        // if (is_singular('product')) {
        //     global $post;

        //     echo '<script type="application/ld+json">';
        //     echo json_encode([
        //         "@context" => "https://schema.org",
        //         "@type" => "Product",
        //         "name" => get_the_title($post),
        //         "description" => get_the_excerpt($post),
        //     ]);
        //     echo '</script>';
        // }
    }
}