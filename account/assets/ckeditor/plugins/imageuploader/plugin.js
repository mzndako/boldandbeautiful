// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md

CKEDITOR.plugins.add( 'imageuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = 'assets/ckeditor/plugins/imageuploader/imgbrowser.php';
       editor.config.filebrowserUploadUrl = 'uploads/SCHOOL_1/lesson';
    }
});
