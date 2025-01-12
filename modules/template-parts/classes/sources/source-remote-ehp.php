<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Sources;

class Source_Remote_Ehp extends \Elementor\TemplateLibrary\Source_Remote {

	const API_TEMPLATES_URL = 'https://my.stg.elementor.red/api/connect/v1/library/templates?products=ehp';

	const TEMPLATES_DATA_TRANSIENT_KEY_PREFIX = 'elementor_remote_templates_ehp_data_';

	public function get_id() {
		return 'remote-ehp';
	}

	protected function get_templates( string $editor_layout_type ): array {
		$templates_data_cache_key = static::TEMPLATES_DATA_TRANSIENT_KEY_PREFIX . HELLO_PLUS_VERSION;

		$templates_data = $this->get_templates_remotely( $editor_layout_type );

		if ( empty( $templates_data ) ) {
			return [];
		}

		set_transient( $templates_data_cache_key, $templates_data, 12 * HOUR_IN_SECONDS );

		return $templates_data;
	}
}
