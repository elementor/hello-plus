<?php
use HelloPlus\Modules\TemplateParts\Documents\Footer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer = new Footer();
$footer->print_content();
wp_footer();
?>
</body>
</html>
