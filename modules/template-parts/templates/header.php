<?php
namespace HelloPlus\TemplateParts\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Utils as Theme_Utils;
use Elementor\Utils as Elementor_Utils;
use HelloPlus\Modules\TemplateParts\Documents\Header_Document;

// Header template is validated earlier, so if we got this far, there is only one template-document post:
$header_doc_post = Header_Document::get_document_post();
$header = Theme_Utils::elementor()->documents->get( $header_doc_post );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	// PHPCS - not a user input.
	echo Elementor_Utils::get_meta_viewport( 'hello-plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title>
			<?php
			// PHPCS - already escaped by WordPress.
			echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();
$header->print_content();
?>
