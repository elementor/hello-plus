<?php

use HelloPlus\Modules\TemplateParts\Documents\Footer_Document;
use HelloPlus\Includes\Utils as Theme_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer_doc_post = Footer_Document::get_document_post();
$footer = Theme_Utils::elementor()->documents->get( $footer_doc_post );

$footer->print_content();

wp_footer();
?>
</body>
</html>
