<?php

if ( ! class_exists( 'Woostify_Get_CSS' ) ) {
    return;
}

class My_Woostify_Get_CSS extends Woostify_Get_CSS {

    public function woostify_guten_block_editor_assets() {
        // Перевизначений метод — замість оригіналу

        $options = function_exists('woostify_options') ? woostify_options(false) : [];

        $block_styles = '
            .edit-post-visual-editor, .edit-post-visual-editor p {
                font-family: ' . esc_attr( $options['body_font_family'] ?? 'Comic Sans MS' ) . ';
            }

            .editor-post-title__block .editor-post-title__input,
            .wp-block-heading, .editor-rich-text__tinymce {
                font-family: ' . esc_attr( $options['heading_font_family'] ?? 'Papyrus' ) . ';
            }

            .editor-styles-wrapper .wp-block {
                max-width: ' . esc_attr( $options['container_width'] ?? '666' ) . 'px;
            }
        ';

        wp_register_style( 'woostify-block-editor', false );
        wp_enqueue_style( 'woostify-block-editor' );
        wp_add_inline_style( 'woostify-block-editor', $block_styles );
    }
}
