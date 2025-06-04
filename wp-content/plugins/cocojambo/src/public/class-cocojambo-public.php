<?php

class Cocojambo_Public {
	public function __construct(  ) {
		add_action( 'wp_enqueue_scripts', [$this, 'cocojambo_scripts_front'] );
		add_filter( 'the_content', [$this, 'add_slide'] );
	}



	function cocojambo_scripts_front() {
		if (is_single()) {
			wp_enqueue_script( 'cocojambo-public-slidebox',
				COCOJAMBO_PLUGIN_URL .'assets/public/slidebox/js/jquery.slidebox.js',  [ 'jquery' ] );
		}
		wp_enqueue_style( 'cocojambo-public',
			COCOJAMBO_PLUGIN_URL .'assets/public/public.css', 20 );
		wp_enqueue_script( 'cocojambo-public',
			COCOJAMBO_PLUGIN_URL .'assets/public/public.js',  [ 'jquery', 'cocojambo-public-slidebox' ], false, true );

	}

	public function add_slide( $content ) {
		if (! is_single()) {
			return $content;
		}
		global  $post;
		$slide_id = get_post_meta($post->ID, 'cocojambo_panel', true);
		if (!$slide_id) {
			return $content;
		}

		$slide = $this->get_slide($slide_id);
		$this->requireTemplate('slide-template', $content, [
			'slide' => $slide,
		]);
	}

	public function get_slide( $slide_id ) {
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cocojambo_panel WHERE id = $slide_id", ARRAY_A);
	}

	protected function requireTemplate($fileName, $content, $data = []) {
		ob_start();
		extract($data);
		require plugin_dir_path( __FILE__ ) .'../../templates/public/'. $fileName.'.php';
		$slide_html = ob_get_clean();
		echo $content . $slide_html;
	}
}