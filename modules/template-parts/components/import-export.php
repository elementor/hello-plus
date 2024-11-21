<?php
namespace HelloPlus\Modules\TemplateParts\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\TemplateParts\Processes\Import;
use Elementor\App\Modules\TemplateParts\Processes\Export;
use Elementor\App\Modules\TemplateParts\Processes\Revert;
use HelloPlus\Modules\TemplateParts\Classes\Runners\Import as Ehp_Import;
use HelloPlus\Modules\TemplateParts\Classes\Runners\Export as Ehp_Export;
use HelloPlus\Modules\TemplateParts\Classes\Runners\Revert as Ehp_Revert;

class Import_Export {
	public function register_import_runners( Import $import ) {
		$import->register( new Ehp_Import() );
	}

	public function register_export_runners( Export $export ) {
		$export->register( new Ehp_Export() );
	}

	public function register_revert_runners( Revert $revert ) {
		$revert->register( new Ehp_Revert() );
	}

	public function __construct() {
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return;
		}
		add_action( 'elementor/import-export/import-kit', [ $this, 'register_import_runners' ] );
		add_action( 'elementor/import-export/export-kit', [ $this, 'register_export_runners' ] );
		add_action( 'elementor/import-export/revert-kit', [ $this, 'register_revert_runners' ] );
	}
}
