window.CKEDITOR_BASEPATH = '/node_modules/ckeditor4/';

import('ckeditor4')

CKEDITOR_BASEPATH.replace( 'editor', {
    extraPlugins: 'iframe'
} );
