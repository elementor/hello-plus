<?php
namespace HelloPlus\Modules\ImportExport;

use HelloPlus\Includes\Module_Base;
use HelloPlus\Includes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {

	public static function get_name(): string {
		return 'ehp-import-export';
	}

	protected function get_component_ids(): array {
		return [
			'Import_Export',
		];
	}

	public static function is_active(): bool {
		return Utils::has_pro();
	}
}
