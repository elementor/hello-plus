<?php
use HelloPlus\Modules\TemplateParts\Classes\Footer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer = new Footer();
$footer->print_content();
wp_footer();
?>
</body>
</html>
