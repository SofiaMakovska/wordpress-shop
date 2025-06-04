<?php

class Cocojambo_Left_Menu {

	public function __construct() {
		$this->addMenuItem();
	}

	public function addMenuItem() {
		add_action( 'admin_menu', [ $this, 'addPrefixMenuItem' ] );
	}

	public function addPrefixMenuItem() {
		add_menu_page(
			__('Create Slide','cocojambo'),
			__('Create Slide','cocojambo'),
			'manage_options',
			'add-new-slide',
			[ $this, 'addNewSlide'],
			'dashicons-embed-photo'
		);
		add_submenu_page(
			'add-new-slide',
			__('Set Slide','cocojambo'),
			__('Set Slide','cocojambo'),
			'manage_options',
			'add-new-slide',
			[ $this, 'addNewSlide' ]
		);

		add_submenu_page(
			'add-new-slide',
			__('Slide Settings','cocojambo'),
			__('Slide Settings','cocojambo'),
			'manage_options',
			'slide-settings',
			[ $this, 'addSettings' ]
		);
	}

	public function addNewSlide() {
		$cocojambo_admin = new Cocojambo_Admin();
		$posts = $cocojambo_admin->get_posts();
		$slides = $cocojambo_admin->get_slides();
		$page = $_GET['paged'] ?? 1;

		$this->requireTemplate('add-new-slide', [
			'posts' => $posts,
			'slides' => $slides,
			'page' => $page
		]);
	}

	public function addSettings() {
		$cocojambo_admin = new Cocojambo_Admin();
		$slides = $cocojambo_admin->get_slides(true);

		$this->requireTemplate('slide-settings', [
			'slides' => $slides,
		]);
	}

	protected function requireTemplate($fileName, $data = []) {
		ob_start();
		extract($data);
		require plugin_dir_path( __FILE__ ) .'../templates/'. $fileName.'.php';
		echo ob_get_clean();
	}

}


