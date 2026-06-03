<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// wysiwyg editor



if(!isset($wysiwyg_editor['editor'])) {
	$wysiwyg_editor['editor'] = 1;
	if(isset($_SESSION["WYSIWYG_EDITOR"])) {
		$wysiwyg_editor['editor'] = $_SESSION["WYSIWYG_EDITOR"];
	}
}

if($wysiwyg_editor['editor']) {

    if(empty($wysiwyg_editor['value'])) {
        $wysiwyg_editor['value'] = '';
    }
    if(!isset($wysiwyg_editor['field'])) {
        $wysiwyg_editor['field'] = 'wysiwyg_editor';
    }
    if(empty($wysiwyg_editor['height'])) {
        $wysiwyg_editor['height'] = '400px';
    }
    if(empty($wysiwyg_editor['width'])) {
        $wysiwyg_editor['width'] = '100%';
    }
    if(empty($wysiwyg_editor['rows'])) {
        $wysiwyg_editor['rows'] = '15';
    }

    $wysiwyg_editor['lang']	= isset($_SESSION["wcs_user_lang"]) ? $_SESSION["wcs_user_lang"] : 'en';
    $wysiwyg_editor['id'] = trim(preg_replace('/[^a-z0-9\-\_]/', '_', $wysiwyg_editor['field']), '_');
    $wysiwyg_editor['is_tab'] = !empty($wysiwyg_editor['config']) && $wysiwyg_editor['config'] === 'tabs';


	$BE['HEADER']['tinymce.js'] = getJavaScriptSourceLink('include/vendor/tinymce/tinymce/tinymce.min.js');

	// simple textarea - no WYSIWYG editor
	echo '<textarea class="tinymce-editor" name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'].'" id="'.$wysiwyg_editor['id'].'">';
	echo html($wysiwyg_editor['value'], true).'</textarea>';

	echo '<script type="text/javascript">' . LF;
	echo 'tinymce.init({' . LF;
	echo '	license_key: "gpl",' . LF;
	echo '	selector: "#' . $wysiwyg_editor['id'] . '",' . LF;
	echo '	width: "' . $wysiwyg_editor['width'] . '",' . LF;
	echo '	height: "' . $wysiwyg_editor['height'] . '",' . LF;
	echo '	plugins: "advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount",' . LF;
	echo '	menubar: false,' . LF;
	echo '	toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media table | code fullscreen",' . LF;
	$tinymce_lang = $wysiwyg_editor['lang'];
	$tinymce_lang_file = 'include/vendor/mklkj/tinymce-i18n/langs/' . $tinymce_lang . '.js';
	if ($tinymce_lang !== 'en' && is_file(CMSGO_ROOT . '/' . $tinymce_lang_file)) {
		echo '	language: "' . $tinymce_lang . '",' . LF;
		echo '	language_url: "' . $tinymce_lang_file . '",' . LF;
	} else {
		echo '	language: "en",' . LF;
	}
	echo '	convert_urls: false,' . LF;
	if (!empty($cmsgo['FCK_FileBrowser'])) {
		echo '	file_picker_callback: function (callback, value, meta) {' . LF;
		echo '		window.activeTinyMceCallback = callback;' . LF;
		echo '		let url = "filebrowser.php?opt=17";' . LF;
		echo '		if (meta.filetype === "file" || meta.filetype === "link") {' . LF;
		echo '			url = "articlebrowser.php?opt=16";' . LF;
		echo '		}' . LF;
		echo '		window.open(url, "FileBrowser", "width=800,height=600,resizable=yes,scrollbars=yes");' . LF;
		echo '	},' . LF;
	}
	echo '	branding: false,' . LF;
	echo '	promotion: false' . LF;
	echo '});' . LF;
	echo '</script>';

} else {

	// simple textarea - no WYSIWYG editor
	echo '<textarea name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'];
	echo '" class="v12 editor-textarea" id="'.$wysiwyg_editor['id'].'" ';
	echo 'style="width:'.$wysiwyg_editor['width'].';height:'.$wysiwyg_editor['height'].';">';
	echo html($wysiwyg_editor['value'], true).'</textarea>';

}
