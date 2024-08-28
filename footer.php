<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloPlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( \HelloPlus\Modules\Theme\Module::display_header_footer() ) {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<?php wp_footer(); ?>

</body>
</html>
