<?php
/**
 * Template Name: Sewing Machine Page Template
 * Template Post Type: page
 * Description: A custom template for displaying content related to sewing machines.
 */
if (!defined('ABSPATH'))
    exit;
get_header();
?>

<?php
// Assuming you are on a taxonomy archive page or have the term ID/slug available
if ( is_tax() || is_category() || is_tag() ) {
    // Get the current term object
    $term = get_queried_object();

    // Check if a term object was successfully retrieved
    if ( $term && is_object( $term ) ) {
        // Get the taxonomy title (name)
        $taxonomy_title = $term->name;

        // Get the taxonomy description
        $taxonomy_description = $term->description;

        echo "<h1>Page: Singular.php - Taxonomy Title: " . esc_html( $taxonomy_title ) . "</h1>";
        if ( ! empty( $taxonomy_description ) ) {
            echo "<p>Taxonomy Description: " . esc_html( $taxonomy_description ) . "</p>";
        } else {
            echo "<p>No description available for this taxonomy.</p>";
        }
    }
} else {
    echo "<p>This is not a taxonomy archive page.</p>";
}
?>
<?php get_footer();