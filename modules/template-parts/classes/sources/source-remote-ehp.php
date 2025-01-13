<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Sources;

use Elementor\Api as Elementor_Api;
use Elementor\Core\Common\Modules\Connect\Module as Elementor_Connect_Module;
use HelloPlus\Includes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Source_Remote_Ehp extends \Elementor\TemplateLibrary\Source_Base {

	const API_TEMPLATES_URL = 'https://ba-templates-api.platform-prod.elementor.red/v1/templates/';

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
	public function register_data() {}

	/**
	 * @inheritDoc
	 */
	public function get_items( $args = [] ) {
		$force_update = ! empty( $args['force_update'] ) && is_bool( $args['force_update'] );

		$templates_data = $this->get_templates_data( $force_update );

		$templates = [];

		foreach ( $templates_data as $template_data ) {
			$templates[] = $this->prepare_template( $template_data );
		}

		return $templates;
	}


	protected function prepare_template( array $template_data ) {
		$favorite_templates = $this->get_user_meta( 'favorites' );

		// BC: Support legacy APIs that don't have access tiers.
		if ( isset( $template_data['access_tier'] ) ) {
			$access_tier = $template_data['access_tier'];
		} else {
			$access_tier = 0 === $template_data['access_level']
				? Elementor_Connect_Module::ACCESS_TIER_FREE
				: Elementor_Connect_Module::ACCESS_TIER_ESSENTIAL;
		}

		return [
			'template_id' => $template_data['id'],
			'source' => $this->get_id(),
			'type' => $template_data['type'],
			'subtype' => $template_data['subtype'],
			'title' => $template_data['title'],
			'thumbnail' => $template_data['thumbnail'],
			'date' => $template_data['tmpl_created'],
			'author' => $template_data['author'],
			'tags' => json_decode( $template_data['tags'] ),
			'isPro' => ( '1' === $template_data['is_pro'] ),
			'accessLevel' => $template_data['access_level'],
			'accessTier' => $access_tier,
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex' => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
			'url' => $template_data['url'],
			'favorite' => ! empty( $favorite_templates[ $template_data['id'] ] ),
		];
	}


	/**
	 * @inheritDoc
	 */
	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	/**
	 * @inheritDoc
	 */
	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	/**
	 * @inheritDoc
	 */
	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	/**
	 * @inheritDoc
	 */
	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_data( array $args, $context = 'display' ) {
		$data = Elementor_Api::get_template_content( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		// Set the Request's state as an Elementor upload request, in order to support unfiltered file uploads.
		Utils::elementor()->uploads_manager->set_elementor_upload_state( true );

		// BC.
		$data = (array) $data;

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id = $args['editor_post_id'];
		$document = Utils::elementor()->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		// After the upload complete, set the elementor upload state back to false
		Utils::elementor()->uploads_manager->set_elementor_upload_state( false );

		return $data;
	}

	protected function get_template_data_transient_key(): string {
		return static::TEMPLATES_DATA_TRANSIENT_KEY_PREFIX . HELLO_PLUS_VERSION;
	}

	/**
	 * @inheritDoc
	 */
	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}

	/**
	 * @inheritDoc
	 */
	protected function get_templates( string $editor_layout_type ): array {
		$templates_data_cache_key = $this->get_template_data_transient_key();

		$templates_data = $this->get_templates_remotely( $editor_layout_type );

		if ( empty( $templates_data ) ) {
			return [];
		}

		set_transient( $templates_data_cache_key, $templates_data, 12 * HOUR_IN_SECONDS );

		return $templates_data;
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

	protected function get_templates_body_args( string $editor_layout_type ): array {
		return [];
	}
	protected function get_url_params( string $editor_layout_type ): array {
		return [
			'products' => 'ehp',
			'editor_layout_type' => $editor_layout_type,
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_templates_data( bool $force_update ) : array {
		$templates_data_cache_key = $this->get_template_data_transient_key();

		$editor_layout_type = 'container_flexbox';

		if ( $force_update ) {
			return $this->get_templates( $editor_layout_type );
		}

		$templates_data = get_transient( $templates_data_cache_key );

		if ( empty( $templates_data ) ) {
			return $this->get_templates( $editor_layout_type );
		}

		return $templates_data;
	}
}
