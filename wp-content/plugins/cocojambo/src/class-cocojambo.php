<?php

class Cocojambo_Panel {
	public function __construct(  ) {
		$this->load_dependencies();
		$this->initHooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		require_once COCOJAMBO_PLUGIN_DIR . 'src/admin/class-cocojambo-admin.php';
		require_once COCOJAMBO_PLUGIN_DIR . 'src/public/class-cocojambo-public.php';
	}

	private function define_admin_hooks() {
		$plugin_admin = new Cocojambo_Admin();
	}
	private function define_public_hooks() {
		$plugin_public = new Cocojambo_Public();
	}

	private function initHooks() {
		add_action( 'plugins_loaded', [$this, 'loaded_textdomain'] );
	}

	function loaded_textdomain() {
		load_plugin_textdomain( 'cocojambo' );
	}
}
