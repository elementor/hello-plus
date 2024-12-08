<?php
namespace HelloPlus\TemplateParts\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Utils as Theme_Utils;
use Elementor\Utils as Elementor_Utils;
use HelloPlus\Modules\TemplateParts\Documents\Ehp_Header;

// Header template is validated earlier, so if we got this far, there is only one template-document post:
$header_doc_post = Ehp_Header::get_document_post();
$header = Theme_Utils::elementor()->documents->get( $header_doc_post );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php

	echo wp_kses( Elementor_Utils::get_meta_viewport( Theme_Utils::get_theme_slug() ), [
		'meta' => [
			'name' => true,
			'content' => true,
			'charset' => true,
			'http-equiv' => true,
		],
	] );
	?>
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title>
			<?php
			echo esc_html( wp_get_document_title() );
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
