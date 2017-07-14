/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

//http://ckeditor.com/latest/samples/toolbarconfigurator/index.html#basic

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbar = [
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'VideoDetector'] },
		{ name: 'insert', items: [ 'Image'] },
	];

	config.extraPlugins = 'videodetector';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.language = 'pt-BR';

	config.filebrowserBrowseUrl = '../../../../assets/plugins/ckeditor/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '../../../../assets/plugins/ckeditor/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '../../../../assets/plugins/ckeditor/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '../../../../assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '../../../../assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '../../../../assets/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

};
