<?php

namespace HelloPlus\Modules\Admin;

use HelloPlus\Includes\Module_Base;

class Module extends Module_Base {
	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return 'admin';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Plugin_Notice'
		];
	}

	public function register_hooks() {}
	protected function __construct() {
		$this->register_components();
		$this->register_hooks();
	}
}
