<?php

namespace HelloPlus\Modules\Settings;

use HelloPlus\Includes\Module_Base;

class Module extends Module_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return 'settings';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Settings'
		];
	}

	protected function __construct() {
		$this->register_components();
	}
}
