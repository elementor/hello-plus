<?php
use HelloPlus\Modules\TemplateParts\Documents\Footer_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer = new Footer_Document();
$footer->print_content();
wp_footer();
?>
</body>
</html>
