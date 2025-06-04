<?php

/**
 * @method have_posts()
 * @method the_post()
 * @property $found_posts
 * @property $max_num_pages
 */
class Cocojambo_Admin {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'cocojambo_scripts_admin' ] );
		add_action( 'admin_post_save_slide', [ $this, 'save_slide' ] );
		add_action( 'wp_ajax_cocojambo_change_slide', [ $this, 'change_slide' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
        add_action( 'save_post', [ $this, 'save_metabox' ] );
	}

	public function change_slide() {
		if ( ! isset( $_POST['cocojambo_change_slide'] )
		     || ! wp_verify_nonce( $_POST['cocojambo_change_slide'], 'cocojambo_action' ) ) {
			echo json_encode( [
				'answer' => 'error',
				'text' => __( 'Security error', 'cocojambo' )
			] );
			wp_die();
		}

		$slide_id = isset( $_POST['slide_id'] ) ? (int) $_POST['slide_id'] : 0;
		$article_id = isset( $_POST['article_id'] ) ? (int) $_POST['article_id'] : 0;

		if ( ! $article_id ) {
			echo json_encode( array(
				'answer' => 'error',
				'text' => __( 'Error article ID', 'cocojambo' )
			) );
			wp_die();
		}

		if ( $slide_id ) {
			if ( update_post_meta( $article_id, 'cocojambo_panel', $slide_id ) ) {
				echo json_encode( array(
					'answer' => 'success',
					'text' => __( 'Saved successfully', 'cocojambo' )
				) );
			} else {
				echo json_encode( array(
					'answer' => 'error',
					'text' => __( 'Save error', 'cocojambo' )
				) );
			}
		} else {
			delete_post_meta( $article_id, 'cocojambo_panel' );
			echo json_encode( array(
				'answer' => 'success',
				'text' => __( 'Saved successfully', 'cocojambo' )
			) );
		}
		wp_die();
	}

	public function cocojambo_scripts_admin( $hook_suffix ) {

		wp_enqueue_style( 'cocojambo-admin',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/jquery-ui-accordion/jquery-ui.min.css' );
		wp_enqueue_style( 'cocojambo-admin-main',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/admin.css' );
		wp_enqueue_style( 'cocojambo-admin-sweet',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/sweet/sweet-alert.css' );

		wp_enqueue_script( 'cocojambo-admin',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/jquery-ui-accordion/jquery-ui.min.js', [ 'jquery' ] );
		wp_enqueue_script( 'cocojambo-admin-main',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/admin.js', [ 'jquery' ] );
		wp_enqueue_script( 'cocojambo-admin-sweet',
			COCOJAMBO_PLUGIN_URL . 'assets/admin/sweet/sweet-alert.js' );

		wp_localize_script(
			'cocojambo-admin-main',
			'cocojamboSlide',
			[
				'nonce' => wp_create_nonce( 'cocojambo_action' )
			]
		);
	}

	public function get_posts(): WP_Query {
		return new WP_Query( [
			'post_type' => 'post',
			'order_by'  => 'ID',
			'order'     => 'DESC',
			'paged'     => $_GET['paged'] ?? 1
		] );
	}

	public function save_slide() {
		if ( ! isset( $_POST['cocojambo_add_slide'] ) ||
		     ! wp_verify_nonce( $_POST['cocojambo_add_slide'], 'cocojambo_action' ) ) {
			wp_die( __( 'Error!', 'cocojambo' ) );
		}

		$slide_title   = isset( $_POST['slide_title'] ) ? trim( $_POST['slide_title'] ) : '';
		$slide_url     = isset( $_POST['slide_url'] ) ? trim( $_POST['slide_url'] ) : '';
		$slide_content = isset( $_POST['slide_content'] ) ? trim( $_POST['slide_content'] ) : '';
		$slide_id      = isset( $_POST['slide_id'] ) ? (int) $_POST['slide_id'] : 0;

		if ( empty( $slide_title ) || empty( $slide_content ) || empty( $slide_url ) ) {
			set_transient( 'cocojambo_form_errors', __( 'Form fields are required', 'cocojambo' ), 30 );
		} else {
			$slide_title   = wp_unslash( $slide_title );
			$slide_content = wp_unslash( $slide_content );
			$slide_url     = wp_unslash( $slide_url );
			global $wpdb;

			if ( $slide_id ) {
				$query = "UPDATE {$wpdb->prefix}cocojambo_panel SET title = %s, content = %s, url = %s WHERE id = $slide_id";
			} else {
				$query = "INSERT INTO {$wpdb->prefix}cocojambo_panel (title, content, url) VALUES (%s, %s, %s)";
			}

			if ( false !== $wpdb->query( $wpdb->prepare(
					$query, $slide_title, $slide_content, $slide_url
				) ) ) {
				set_transient( 'cocojambo_form_success', __( 'Slide saved', 'cocojambo' ), 30 );
			} else {
				set_transient( 'cocojambo_form_errors', __( 'Error saving slide', 'cocojambo' ), 30 );
			}
		}

		wp_redirect( $_POST['_wp_http_referer'] );
	}

	public function get_slides( $all = false ) {
		global $wpdb;
		if ( $all ) {
			return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cocojambo_panel ORDER BY title ASC", ARRAY_A );
		}
		$slides = $wpdb->get_results( "SELECT id, title FROM {$wpdb->prefix}cocojambo_panel ORDER BY title ASC", ARRAY_A );
		$data   = array();
		foreach ( $slides as $slide ) {
			$data[ $slide['id'] ] = $slide['title'];
		}

		return $data;
	}

    public function add_metabox() {
        add_meta_box(
            'cocojambo_panel_box',
            __( 'Cocojambo Panel', 'cocojambo' ),
            [ $this, 'render_metabox' ],
            [ 'post', 'page' ], // або тільки 'post'
            'side',
            'default'
        );
    }

    public function render_metabox( $post ) {
        wp_nonce_field( 'cocojambo_panel_nonce_action', 'cocojambo_panel_nonce' );

        $current = get_post_meta( $post->ID, 'cocojambo_panel', true );
        $slides = $this->get_slides(); // уже є в твоєму класі

        echo '<label for="cocojambo_panel">' . esc_html__( 'Select Slide Panel', 'cocojambo' ) . '</label>';
        echo '<select name="cocojambo_panel" id="cocojambo_panel" style="width:100%;">';
        echo '<option value="">' . esc_html__( '— None —', 'cocojambo' ) . '</option>';

        foreach ( $slides as $id => $title ) {
            printf(
                '<option value="%d"%s>%s (ID: %d)</option>',
                $id,
                selected( $current, $id, false ),
                esc_html( $title ),
                $id
            );
        }

        echo '</select>';
    }

    public function save_metabox( $post_id ) {
        if ( ! isset( $_POST['cocojambo_panel_nonce'] ) ||
            ! wp_verify_nonce( $_POST['cocojambo_panel_nonce'], 'cocojambo_panel_nonce_action' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( isset( $_POST['cocojambo_panel'] ) ) {
            $panel_id = intval( $_POST['cocojambo_panel'] );
            if ( $panel_id > 0 ) {
                update_post_meta( $post_id, 'cocojambo_panel', $panel_id );
            } else {
                delete_post_meta( $post_id, 'cocojambo_panel' );
            }
        }
    }

}
