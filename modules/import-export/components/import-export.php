<?php

namespace HelloPlus\Modules\ImportExport\Components;

use Elementor\App\Modules\ImportExport\Processes\Import;
use Elementor\App\Modules\ImportExport\Processes\Revert;
use HelloPlus\Modules\ImportExport\Runners\Import\Templates_Import;
use HelloPlus\Modules\ImportExport\Runners\Revert\Templates_Revert;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Import_Export {

	public function __construct() {
		add_action( 'elementor/import-export/import-kit', function ( Import $import ) {
			$this->register_import_kit_runners( $import );
		} );

		add_action( 'elementor/import-export/revert-kit', function ( Revert $revert ) {
			$this->register_revert_kit_runners( $revert );
		} );
	}

	private function register_import_kit_runners( Import $import ) {
		$import->register( new Templates_Import() );
	}

	private function register_revert_kit_runners( Revert $revert ) {
		$revert->register( new Templates_Revert() );
	}
}
