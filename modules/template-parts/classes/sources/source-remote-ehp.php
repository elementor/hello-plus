<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Sources;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Source_Remote_Ehp extends \Elementor\TemplateLibrary\Source_Remote {
	const API_TEMPLATES_URL = 'https://my.elementor.com/api/connect/v1/library/templates/';
	const TEMPLATES_DATA_TRANSIENT_KEY_PREFIX = 'elementor_remote_templates_ehp_data_';

	public function get_id(): string {
		return 'remote-ehp';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Remote-Ehp', 'hello-plus' );
	}

	/**
	 * @inheritDoc
	 */
	protected function get_templates_remotely( string $editor_layout_type ) {
		$query_args = $this->get_url_params( $editor_layout_type );
		$url = add_query_arg( $query_args, static::API_TEMPLATES_URL );

		$response = wp_remote_get( $url, [
			'headers' => apply_filters( 'stg-cf-headers', [] ),
		] );

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$templates_data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $templates_data ) || ! is_array( $templates_data ) ) {
			return [];
		}

		return $templates_data;
	}

	protected function get_url_params( string $editor_layout_type ): array {
		return [
			'products' => 'ehp',
			'editor_layout_type' => $editor_layout_type,
		];
	}
}
