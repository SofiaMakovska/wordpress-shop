<?php

class CocojamboStudy {

	public function convertTitle() {
		add_filter( 'the_title', function ( $title, $post_id ) {
			return mb_convert_case( $title, MB_CASE_TITLE );
		}, 10, 2 );
	}

	public function addPrefixToPostTitle() {
		add_filter( 'the_title', function ( $title, $post_id = null ) {
			if ( ! is_admin() && 'post' === get_post_type( $post_id ) ) {
				$prefix = 'Префікс: '; // вкажіть свій префікс
				$title  = $prefix . $title;
			}

			return $title;
		}, 10, 2 );

	}


}



