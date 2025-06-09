<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Sources;

use HelloPlus\Includes\Utils;

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

	protected function filter_templates_data_by_theme( array $templates_data ): array {
		return array_filter( $templates_data, function ( $template ) {
			return in_array( Utils::get_theme_slug(), json_decode( $template['tags'] ), true );
		} );
	}

	protected function get_templates_data_cache_key(): string {
		return static::TEMPLATES_DATA_TRANSIENT_KEY_PREFIX . HELLO_PLUS_VERSION;
	}

	protected function get_templates_data( bool $force_update ): array {
		$templates_data_cache_key = $this->get_templates_data_cache_key();

		$editor_layout_type = 'container_flexbox';

		$templates_data = get_transient( $templates_data_cache_key );

		if ( $force_update || empty( $templates_data ) ) {
			$templates_data = $this->get_templates_remotely( $editor_layout_type );
		}

		if ( empty( $templates_data ) ) {
			return [];
		}

		set_transient( $templates_data_cache_key, $templates_data, 12 * HOUR_IN_SECONDS );

		return $this->filter_templates_data_by_theme( $templates_data );
	}

	/**
	 * @inheritDoc
	 */
	protected function get_templates_remotely( string $editor_layout_type ) {
		$query_args = $this->get_url_params();
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

	protected function get_url_params(): array {
		return [
			'products' => 'ehp',
			'editor_layout_type' => 'container_flexbox',
		];
	}
}
