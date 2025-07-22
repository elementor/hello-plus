<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Runners;

use Elementor\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\ImportExport\Runners\Import\Import_Runner_Base;
use Elementor\App\Modules\ImportExport\Utils as ImportExportUtils;

class Import_Floating_Elements extends Import_Runner_Base {

	public static function get_name(): string {
		return 'floating-elements';
	}

	public function should_import( array $data ): bool {
		return (
			isset( $data['include'] ) &&
			in_array( 'content', $data['include'], true ) &&
			! empty( $data['manifest']['content']['e-floating-buttons'] ) &&
			! empty( $data['extracted_directory_path'] )
		);
	}

	public function import( array $data, array $imported_data ) {

		//error_log( print_r( $data, true ) );
		error_log( print_r( $imported_data, true ) );

		$post_type = 'e-floating-buttons';
		$posts_settings = $data['manifest']['content'][ $post_type ];
		$path = $data['extracted_directory_path'] . 'content/' . $post_type . '/';

		foreach ( $posts_settings as $id => $post_settings ) {
			$post_data = ImportExportUtils::read_json_file( $path . $id );
			error_log( print_r( $post_data, true ) );
		}

		return true;
	}
}
