<?php

namespace HelloPlus\Modules\Content\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait Widget_Repeater_Editable {

	public function public_add_inline_editing_attributes( $attribute, $toolbar = 'basic' ) {
		return $this->add_inline_editing_attributes( $attribute, $toolbar );
	}

	public function public_get_repeater_setting_key( $setting_key, $repeater_key, $item_key ) {
		return $this->get_repeater_setting_key( $setting_key, $repeater_key, $item_key );
	}
}
