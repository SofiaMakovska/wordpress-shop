<?php

function my_customize_register( $wp_customize ) {
    // нова секція
    $wp_customize->add_section('my_section', [
        'title'    => __('Моя секція', 'woostify-child'),
        'priority' => 30,
    ]);

    // нове налаштування
    $wp_customize->add_setting('my_setting', [
        'default'   => '',
        'transport' => 'refresh',
    ]);

    // контрол
    $wp_customize->add_control('my_setting', [
        'label'   => __('Мій текст', 'woostify-child'),
        'section' => 'my_section',
        'type'    => 'text',
    ]);
}
add_action('customize_register', 'my_customize_register');

function woostify_footer_custom_text() {

    $content = __( 'Copyright &copy; [site_title] | Powered by [theme_author]', 'woostify' );

    if ( apply_filters( 'woostify_credit_info', true ) ) {

        if ( apply_filters( 'woostify_privacy_policy_link', true ) && function_exists( 'the_privacy_policy_link' ) ) {
            $content .= get_the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
        }
    }

    return $content;

}
add_action( 'after_setup_theme', function () {
    require_once get_stylesheet_directory() . '/inc/class-my-get-css.php';

    // Видаляємо дію, яка додає метод оригінального класу
    remove_all_actions( 'enqueue_block_editor_assets' );

    // Ініціалізуємо свій клас
    $my_woostify_css = new My_Woostify_Get_CSS();
    add_action( 'enqueue_block_editor_assets', [ $my_woostify_css, 'woostify_guten_block_editor_assets' ] );
});

add_action( 'woostify_after_header', 'my_custom_product_cats_menu' );

add_action( 'woostify_after_header', 'my_custom_product_cats_menu' );

function my_custom_product_cats_menu() {
    $terms = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
    ] );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<nav class="my-product-cats-nav">';
        echo '<ul class="my-product-cats">';

        foreach ( $terms as $term ) {
            $image_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
            $image_url = $image_id ? wp_get_attachment_url( $image_id ) : get_stylesheet_directory_uri() . '/assets/img/placeholder-cat.webp';

            echo '<li class="my-cat-item">';
            echo '<a href="' . esc_url( get_term_link( $term ) ) . '">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $term->name ) . '" class="cat-thumb" />';
            echo '<span class="cat-title">' . esc_html( $term->name ) . '</span>';
            echo '</a>';

            $subterms = get_terms( [
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => $term->term_id,
            ] );

            if ( ! empty( $subterms ) && ! is_wp_error( $subterms ) ) {
                echo '<ul class="my-sub-cats">';
                foreach ( $subterms as $subterm ) {
                    $sub_image_id  = get_term_meta( $subterm->term_id, 'thumbnail_id', true );
                    $sub_image_url = $sub_image_id ? wp_get_attachment_url( $sub_image_id ) : get_stylesheet_directory_uri() . '/assets/img/placeholder-cat.webp';

                    echo '<li>';
                    echo '<a href="' . esc_url( get_term_link( $subterm ) ) . '">';
                    echo '<img src="' . esc_url( $sub_image_url ) . '" alt="' . esc_attr( $subterm->name ) . '" class="cat-thumb sub-thumb" />';
                    echo '<span class="cat-title">' . esc_html( $subterm->name ) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }

            echo '</li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
}



add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'my-custom-header-cats',
        get_stylesheet_directory_uri() . '/header-cats.css',
        [],
        filemtime( get_stylesheet_directory() . '/header-cats.css' )
    );
});




