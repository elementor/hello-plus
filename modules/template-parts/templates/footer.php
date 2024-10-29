<?php
use HelloPlus\Modules\TemplateParts\Documents\Footer;
use HelloPlus\Includes\Utils as Theme_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$footer_doc_post = Footer::get_document_post();
$footer = Theme_Utils::elementor()->documents->get( $footer_doc_post );
$footer->print_content();
wp_footer();
?>
</body>
</html>
