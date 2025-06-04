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
