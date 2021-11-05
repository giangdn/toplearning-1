/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.extraPlugins = 'colorbutton';
    config.toolbar = [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'insert', items: ['Image'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'videos', items: ['Video'] }
    ];
};
CKEDITOR.config.entities = false;