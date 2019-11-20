/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'widget';
    config.extraPlugins = 'uploadwidget';
    config.extraPlugins = 'uploadimage';
    config.extraPlugins = 'clipboard';
    config.extraPlugins = 'widgetselection';
    config.extraPlugins = 'notification';
    config.extraPlugins = 'toolbar';
    config.extraPlugins = 'button';
    config.extraPlugins = 'lineutils';
};
