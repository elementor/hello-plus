<?php

namespace HelloPlus\Testing;

use ElementorEditorTesting\Elementor_Test_Base;

class Elementor_Test_First extends Elementor_Test_Base {

	public function test_truthness() {
		$this->assertTrue( defined( 'HELLOPLUS_VERSION' ) );
	}
}
