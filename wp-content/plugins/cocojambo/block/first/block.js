( function ( blocks, element ) {
    var el = element.createElement;

    blocks.registerBlockType( 'gutenberg-examples/example-01-basic', {
        edit: function () {
            return el( 'p', {
                className: 'block-editor-rich-text__editable block-editor-block-list__block wp-block is-selected wp-block-paragraph rich-text'
            }, 'Hello World (from the editor).' );
        },
        save: function () {
            return el( 'h3', {
                className: 'block-test-class'
            }, 'Hola mundo (from the frontend).' );
        },
    } );
} )( window.wp.blocks, window.wp.element );
