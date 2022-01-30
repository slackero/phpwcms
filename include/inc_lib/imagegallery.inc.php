<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

/*
 * phpwcmsImageGallery
 *
 * A file center driven gallery for phpwcms
 *
 */

class phpwcmsImageGallery {

	var $url				= '';
	var $alias				= '';
	var $images				= array();
	var $query_separator	= '/';
	var $download			= false;
	var $download_direct	= false;

	// string defines the sorting mode
	// ASC/DESC = get images sorted by date
	// RAND = get images by random)
	var $image_sort		= 'DESC';

	var $image_src		= '';

	// integer the limit of how many images should be retrieved
	var $image_limit	= 0;

	var $thumb_width	= 200;
	var $thumb_height	= 150;
	var $width			= 600;
	var $height			= 400;
	var $zoom			= true;
	var $lightbox		= true;

	var $galleries		= array();
	var $gallery		= array();

	// define what kind of file storage folders should be taken
	// true = only folders marked as gallery root or sub directory
	var $gallery_only	= true;

	// define the root ID of gallery = where to start recursive search
	// by default root ID = 0 which is top level
	var $gallery_rootid	= 0;

	// integer the max depth of recursive folder search, THREAD mode only
	var $depth			= 0;

	// string defines the sorting mode:
	// NAME-ASC, NAME-DESC, CREATE-ASC, CREATE-DESC
	// get images sorted by name or create date
	var $gallery_sort	= 'NAME-DESC';
	var $_sort			= '';

	// string the search mode - FLAT/THREAD (FLAT = default)
	var $gallery_mode	= 'FLAT';

	// gallery name can be prefixed by date YYYY-MM-DD,
	// use sort by NAME-ASC or NAME-DESC,
	// this date will be used as gallery date then and
	// is stripped from name if $use_name_date = true
	var $use_name_date	= true;

	// template definitions
	var $list_header				= '	<div class="galleries">';
	var $list_footer				= '</div>';
	var $list_template				= '[TAB]<div class="gallery">[LF][TAB][TAB]{TITLE} [LF][TAB][TAB]{THUMBNAIL} [LF][TAB][TAB] {DESCRIPTION}[LF][TAB]</div>';
	var $list_title_prefix			= '<h3>[LINK]';
	var $list_title_suffix			= '[/LINK]</h3>';
	var $list_descr_prefix			= '<div><strong>{DATE:m/d/y lang=EN}</strong> &#8211; ';
	var $list_descr_suffix			= '</div>';
	// define if an gallery thumbnail should be shown
	// 0 = show none, 1 show first ...
	var $list_thumbnail				= 1;
	var $list_thumbnail_width		= 150;
	var $list_thumbnail_height		= 150;
	var $list_thumbnail_crop		= 0;
	var $list_thumbnail_prefix		= '<div class="thumbnail">';
	var $list_thumbnail_suffix		= '</div>';

	var $detail_header				= '<div class="gallery">';
	var $detail_footer				= '</div>';
	var $detail_title_prefix		= '<h2><strong>{DATE:m/d/y lang=EN}</strong> &#8211; ';
	var $detail_title_suffix		= '</h2>';
	var $detail_descr_prefix		= '<div>';
	var $detail_descr_suffix		= '</div>';
	var $detail_thumbnail_crop		= 0;
	var $detail_thumbnail_prefix	= '<div class="thumbnail">';
	var $detail_thumbnail_suffix	= '{DOWNLOAD}</div>';
	var $detail_thumbnail_caption	= false;
	var $detail_zoom_crop			= false;
	var $detail_caption_prefix		= '<div class="caption">';
	var $detail_caption_suffix		= '</div>';
	var $detail_gallery_back		= '<div class="backlink">[LINK]Show all galleries[/LINK]</div>';


	var $item_template				= '[TAB]{TITLE} [LF][TAB][TAB]{THUMBNAIL} [LF][TAB][TAB] {DESCRIPTION}';
	var $item_header				= '<div class="gallery">';
	var $item_footer				= '</div>';
	var $item_title_prefix			= '<h3>[LINK]';
	var $item_title_suffix			= '[/LINK]</h3>';
	var $item_descr_prefix			= '<div><strong>{DATE:m/d/y lang=EN}</strong> &#8211; ';
	var $item_descr_suffix			= '</div>';
	var $item_download_template		= 'default';
	// define if an gallery thumbnail should be shown
	// 0 = show none, 1 show first ...
	var $item_thumbnail				= 1;
	var $item_thumbnail_width		= 150;
	var $item_thumbnail_height		= 150;
	var $item_thumbnail_crop		= 0;
	var $item_thumbnail_prefix		= '<div class="thumbnail">';
	var $item_thumbnail_suffix		= '</div>';

	var $list_image_header			= '<div class="gallery-image-list">';
	var $list_image_footer			= '</div>';
	var $list_image_prefix			= '<div class="image">[LINK]';
	var $list_image_suffix			= '[/LINK]</div>';
	var $list_image_width			= 100;
	var $list_image_height			= 100;
	var $list_image_crop			= 0;

	var $line_break					= "\n\t";

	/*
	 * Initialize some defaults
	 */
	function __construct() {

		$this->_getGallerySortString();

		if($this->alias === '') {
			global $aktion;
			$this->alias = defined('PHPWCMS_ALIAS') ? PHPWCMS_ALIAS : ( $aktion[1] == 0 ? 'id='.$aktion[0] : 'aid='.$aktion[1] );
		}

		$this->setAlias( $this->alias );

		$this->image_src = PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.$this->query_separator;

	}

	function setQuerySeparator($query_separator) {

		$this->image_src = PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.$query_separator;

	}

	function setAlias($alias='') {
		if($alias !== '') {
			$this->alias = $alias;
		}
		$this->url = abs_url(array(), array('gallery'), $this->alias);
	}

	function setGallerySort($sort='') {

		$this->gallery_sort = $sort;
		$this->_getGallerySortString();

	}


	/*
	 * get images
	 *
	 * Dive into phpwcms's db file storage and get all
	 * images based on given mode, starting at folder ID.
	 *
	 * @param mixed use integer value for 1 folder or array for multiple
	 */
	function getImages($folder=0) {

		$folders		= array();
		$this->images	= array();

		if( is_array($folder) ) {
			foreach($folder as $item) {
				$item = intval($item);
				$folders[ $item ] = $item;
			}
		} else {
			$folders[] = intval($folder);
		}

		if(count($folders)) {
			$folders = implode(',', $folders);
		}

		// check if folder(s) is(are) live
		$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_file WHERE ';
		$sql .= 'f_id IN (' . $folders . ') AND f_kid=0 AND f_trash=0 AND f_aktiv=1 AND f_public=1';
		if(_dbCount($sql) > 0) {

			$this->image_limit	= intval($this->image_limit);
			$this->image_sort	= strtoupper($this->image_sort);

			switch($this->image_sort) {
				case 'ASC':					$order_by = ' ORDER BY f_created ASC';					break;
				case 'RAND':				$order_by = ' ORDER BY RAND()';							break;
				case 'SORT-ASC':			$order_by = ' ORDER BY f_sort ASC';						break;
				case 'SORT-DESC':			$order_by = ' ORDER BY f_sort DESC';					break;
				case 'SORT-NAME-ASC':		$order_by = ' ORDER BY f_sort ASC, f_name ASC';			break;
				case 'SORT-NAME-DESC':		$order_by = ' ORDER BY f_sort DESC, f_name DESC';		break;
				case 'SORT-CREATE-ASC':		$order_by = ' ORDER BY f_sort ASC, f_created ASC';		break;
				case 'SORT-CREATE-DESC':	$order_by = ' ORDER BY f_sort DESC, f_created DESC';	break;
				default:					$order_by = ' ORDER BY f_created DESC';
			}
			$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE ';
			$sql .= 'f_pid IN (' . $folders . ') AND f_kid=1 AND ';
			$sql .= 'f_trash=0 AND f_aktiv=1 AND f_public=1 AND ';
			$sql .= "f_ext IN ('jpg', 'jpeg', 'gif', 'png', 'webp')";
			$sql .= $order_by;
			if($this->image_limit > 0) {
				$sql .= ' LIMIT ' . $this->image_limit;
			}
			$result = _dbQuery($sql);
			if(isset($result[0])) {
				$this->images = $result;
			}
		}
	}


	/*
	 * get galleries
	 *
	 * Starting with given folder ID an array with all folder
	 * information inside are returned.
	 *
	 */
	function getGalleries($folder_id = 0) {

		$folder_id			= intval($folder_id);

		/* yet unsupported
		$this->depth		= intval($this->depth);

		if(strtoupper($this->gallery_mode) === 'THREAD' && $this->depth > 0) {
			$this->gallery_mode	= 'THREAD';
		} else {
			$this->gallery_mode	= 'FLAT';
			$this->depth		= 0;
		}
		*/

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE ';
		$sql .= 'f_pid='.$folder_id.' AND f_kid=0 AND f_trash=0 AND f_aktiv=1 AND f_public=1';
		$sql .= $this->gallery_sort;

		$this->galleries = _dbQuery($sql);
		$this->proofGallery();
	}


	/*
	 * check gallery array and set names
	 */
	 function proofGallery() {
		if(!isset($this->galleries[0])) {
			$this->galleries = array();
			return;
		}
		if($this->use_name_date === true) {
			foreach($this->galleries as $key => $row) {
				$this->_stripDateFromName( $this->galleries[$key] );
			}
		}
	}


	/*
	 * search for gallery name date and strip it.
	 * if some date is found set gallery create date to it
	 */
	function _stripDateFromName(&$gallery) {

		// search gallery name for matching date YYYY-MM-DD
		if( preg_match('/^([0-9]{2}|[0-9]{4})\-([0]{0,1}[0-9])\-([0-9]{1,2})(.+)/', $gallery['f_name'], $match) ) {

			$name				= trim($match[4]);
			$gallery['f_name']	= $name;

			$year	= intval($match[1]);
			$month	= intval($match[2]);
			$day	= intval($match[3]);

			if($month > 12 || $month == 0) {
				$month = 1;
			}
			if($day > 31 || $day == 0) {
				$day = 1;
			}

			$date = mktime(11 , 59, 59, $month, $day, $year);
			if($date) {
				$gallery['f_created'] = $date;
			}
		}
	}


	function getGalleriesFolderId($folder=NULL) {

		if( $folder !== NULL ) {
			$this->getGalleries( intval($folder) );
		}

		$folderId = array();

		foreach($this->galleries as $item) {

			$folderId[] = $item['f_id'];

		}

		return $folderId;

	}


	/*
	 * get gallery information
	 */
	function getGallery($folder_id=0) {

		$folder_id			= intval($folder_id);

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE ';
		$sql .= 'f_id='.$folder_id.' AND f_kid=0 AND f_trash=0 AND f_aktiv=1 AND f_public=1';
		$sql .= $this->gallery_sort;

		$this->galleries = _dbQuery($sql);
		$this->proofGallery();

		if( count($this->galleries) && isset($this->galleries[0]['f_id']) ) {

			$this->gallery = $this->galleries[0];

		} else {

			$this->gallery = array();

		}

	}


	function _getGallerySortString() {

		if( $this->_sort === '' || !is_string( $this->_sort ) ) {

			$this->gallery_sort	= strtoupper($this->gallery_sort);

			switch($this->gallery_sort) {

				case 'NAME-DESC':
					$this->gallery_sort = ' ORDER BY f_name DESC';
					break;

				case 'SORT-DESC':
					$this->gallery_sort = ' ORDER BY f_sort DESC';
					break;

				case 'SORT-ASC':
					$this->gallery_sort = ' ORDER BY f_sort ASC';
					break;

				case 'SORT-NAME-DESC':
					$this->gallery_sort = ' ORDER BY f_sort DESC, f_name DESC';
					break;

				case 'SORT-NAME-ASC':
					$this->gallery_sort = ' ORDER BY f_sort ASC, f_name ASC';
					break;

				case 'SORT-CREATE-DESC':
					$this->gallery_sort = ' ORDER BY f_sort DESC, f_created DESC';
					break;

				case 'SORT-CREATE-ASC':
					$this->gallery_sort = ' ORDER BY f_sort ASC, f_created ASC';
					break;

				case 'CREATE-ASC':
					$this->gallery_sort = ' ORDER BY f_created ASC';
					break;

				case 'CREATE-DESC':
					$this->gallery_sort = ' ORDER BY f_created DESC';
					break;

				default: // NAME-ASC
					$this->gallery_sort = ' ORDER BY f_name ASC';

			}
		}
	}


	function listGalleries($folder=NULL) {

		if( $folder !== NULL) {
			$this->getGalleries( intval($folder) );
		}

		$listing = array();

		// loop galleries
		foreach($this->galleries as $item) {

			$listing[] = $this->parse($item);

		}

		if(count($listing)) {

			// add gallery listing header
			array_unshift($listing, $this->list_header);

			// add gallery listing footer
			array_push($listing, $this->list_footer);

			return implode($this->line_break, $listing);
		}

		// return empty string
		return '';
	}

	function showGallery($folder=NULL) {

		if( $folder !== NULL ) {
			$this->getGallery( $folder );
		}

		if( count($this->gallery) ) {

			$gallery	= array();

			$gallery[]	= $this->detail_header;

			$gallery[]	= $this->detail_title_prefix . html($this->gallery['f_name']) . $this->detail_title_suffix;

			if( trim( $this->gallery['f_longinfo'] ) != '' ) {
				$gallery[]	= $this->detail_descr_prefix . ( plaintext_htmlencode($this->gallery['f_longinfo']) ) . $this->detail_descr_suffix;
			}

			$gallery[]	= $this->listGalleryImages( $this->gallery['f_id'] );

						// replaces gallery linking
			$this->detail_gallery_back	= str_replace('[/LINK]', '</a>', $this->detail_gallery_back);
			$this->detail_gallery_back	= str_replace('[LINK]', '<a href="' . $this->url . '">', $this->detail_gallery_back);
			$gallery[]	= $this->detail_gallery_back;

			$gallery[]	= $this->detail_footer;

			$gallery	= implode($this->line_break, $gallery);

			$gallery	= render_cnt_date($gallery, $this->gallery['f_created']);

			return $this->parseGeneral( $gallery );

		}

		return '';
	}

	function listGalleryImages($folder, $sort=NULL) {

		if($sort !== NULL) {
			$temp_sort	= $this->image_sort;
			$this->image_sort	= $sort;
		}

		$this->getImages( $folder );

		if($sort !== NULL) {
			$this->image_sort	= $temp_sort;
		}

		$images = array();
		$lang = $GLOBALS['phpwcms']['default_lang'];

		foreach($this->images as $image) {

			if($image['f_vars'] && count($GLOBALS['phpwcms']['allowed_lang']) > 1) {

				$image['f_vars'] = @unserialize($image['f_vars']);

				if(!empty($image['f_vars'][$lang]['longinfo'])) {
					$image['f_longinfo'] = $image['f_vars'][$lang]['longinfo'];
				}
				if(!empty($image['f_vars'][$lang]['copyright'])) {
					$image['f_copyright'] = $image['f_vars'][$lang]['copyright'];
				}

				$image['f_vars'] = '';
			}

			$name = html( $image['f_name'] );
			$image['f_longinfo'] = trim($image['f_longinfo']);

			$img  = '<img src="';
			$img .= $this->image_src . $this->thumb_width . 'x' . $this->thumb_height;
			if($this->detail_thumbnail_crop == 1) {
				$img .= 'x1';
			}
			$img .= '/' . $image['f_hash'] . '.' . $image['f_ext'] . '/' . rawurlencode($image['f_name']);
			$img .= '" alt="' . $name . '" />';

			// create zoom
			if($this->zoom === true || $this->lightbox === true) {

				$a = '<a href="' . $this->image_src . $this->width . 'x' . $this->height;
				if($this->detail_zoom_crop == 1) {
					$a .= 'x1';
				}
				$a .= '/' . $image['f_hash'] . '.' . $image['f_ext'] . '/' . rawurlencode($image['f_name']) . '" target="_blank"';

				if($this->lightbox === true) {
                    $a .= ' rel="lightbox[gallery'.$folder.']"'.get_attr_data_gallery('gallery'.$folder, ' ', '');
				}
				if($image['f_longinfo'] != '') {

					$a .= ' title="' . parseLightboxCaption($image['f_longinfo']) . '"';

				}

				$a .= '>';

				$img = $a . $img .'</a>';

			}

			if( $this->detail_thumbnail_caption === true && $image['f_longinfo'] != '' ) {

				$img .= $this->line_break;
				$img .= $this->detail_caption_prefix . ( plaintext_htmlencode($image['f_longinfo']) ) . $this->detail_caption_suffix;

			}

			$img = $this->detail_thumbnail_prefix . $img . $this->detail_thumbnail_suffix;

			// prepare item download
			if($this->download) {

				if(!isset($phpwcms)) {
					global $phpwcms;
				}

				$IS_NEWS_CP							= true;
				$crow								= array();
				$value								= array();
				$value['cnt_object']				= array();
				$value['cnt_object']['cnt_files']	= array( 'id' => array($image['f_id']), 'caption' => $image['f_copyright'] );

				$value['files_direct_download']		= $this->download_direct ? 1 : 0;
				$value['files_template']			= $this->item_download_template == 'default' ? '' : $this->item_download_template;

				$news 								= array('files_result' => '');
				$content							= array();
				$content['file_static_result'][0]	= $image;

				// include content part files renderer
				include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

				$img = render_cnt_template($img, 'DOWNLOAD', $news['files_result'] );

				unset($IS_NEWS_CP);

			} else {

				$img = render_cnt_template($img, 'DOWNLOAD', '' );

			}

			$images[] = $img;

		}

		if($this->lightbox === true) {

			initSlimbox();

		}

		return implode($this->line_break, $images);
	}


	function listImages($folder, $limit=NULL, $sort=NULL) {

		if($limit !== NULL) {
			$temp_limit	= $this->image_limit;
			$this->image_limit	= $limit;
		}
		if($sort !== NULL) {
			$temp_sort	= $this->image_sort;
			$this->image_sort	= $sort;
		}

		$this->getImages( $folder );

		if($limit !== NULL) {
			$this->image_limit	= $temp_limit;
		}
		if($sort !== NULL) {
			$this->image_sort	= $temp_sort;
		}

		$images = array();

		foreach($this->images as $image) {

			$name = html( $image['f_name'] );

			$img  = '<img src="';
			$img .= $this->image_src . $this->list_image_width . 'x' . $this->list_image_height;
			if($this->list_image_crop == 1) {
				$img .= 'x1';
			}
			$img .= '/' . $image['f_hash'] . '.' . $image['f_ext'] . '/' . rawurlencode($image['f_name']);
			$img .= '" alt="' . $name . '" />';

			$images[] = $this->list_image_prefix . $img . $this->list_image_suffix;

		}

		if(count($images)) {

			// add gallery listing header
			array_unshift($images, $this->list_image_header);

			// add gallery listing footer
			array_push($images, $this->list_image_footer);

			$images = implode($this->line_break, $images);

			// replaces gallery linking
			$images = str_replace('[/LINK]', '</a>', $images);
			$images	= str_replace('[LINK]', '<a href="' . $this->url . '">', $images);

			return $images;
		}

		// nothing to return than empty string
		return '';
	}


	function parse($item, $type='GALLERY_ITEM') {

		if($type === 'GALLERY_ITEM') {

			$entry	= $this->list_template;

			$name	= html( $item['f_name'] );
			$link	= $this->url . '&amp;gallery=' . $item['f_id'];
			$title	= $this->list_title_prefix . $name . $this->list_title_suffix;
			$descr	= trim( $item['f_longinfo'] );
			if($descr !== '') {
				$descr = $this->list_descr_prefix . ( plaintext_htmlencode( $descr ) ) . $this->list_descr_suffix;
			}
			$thumbs = array();

			// get preview/thumbnail image(s) for this gallery
			if($this->list_thumbnail > 0) {

				$temp_limit	= $this->image_limit;
				$temp_sort	= $this->image_sort;

				$this->image_limit	= $this->list_thumbnail;
				$this->image_sort	= 'DESC';
				$this->getImages( $item['f_id'] );

				foreach($this->images as $image) {

					$img  = '<img src="';
					$img .= $this->image_src . $this->list_thumbnail_width . 'x' . $this->list_thumbnail_height;
					if($this->list_thumbnail_crop == 1) {
						$img .= 'x1';
					}
					$img .= '/' . $image['f_hash'] . '.' . $image['f_ext'] . '/' . rawurlencode($image['f_name']);
					$img .= '" alt="' . $name . '" />';

					$thumbs[] = $this->list_thumbnail_prefix . $img . $this->list_thumbnail_suffix;

				}

				$this->image_limit	= $temp_limit;
				$this->image_sort	= $temp_sort;

			}

			$thumbs	= implode(LF, $thumbs);

			$entry	= str_replace('{TITLE}', $title, $entry);
			$entry	= str_replace('{DESCRIPTION}', $descr, $entry);
			$entry	= str_replace('{THUMBNAIL}', $thumbs, $entry);
			$entry	= str_replace('[/LINK]', '</a>', $entry);
			$entry	= str_replace('[LINK]', '<a href="' . $link . '" title="' . $name . '">', $entry);
			$entry	= render_cnt_date($entry, $item['f_created']);

			return $this->parseGeneral( $entry );
		}

	}

	function parseGeneral($string='') {

		$string = str_replace( '[LF]', LF, $string);
		$string = str_replace( '[TAB]', "\t", $string);

		return $string;
	}


	function getGalleryTree() {

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file ';
		$sql .= 'WHERE f_kid=0 AND f_aktiv=1 AND f_public=1';
		if($this->gallery_only) {
			$sql .= ' AND f_gallerystatus=2';
		}
		$this->gallery_rootid = intval($this->gallery_rootid);
		if($this->gallery_rootid > 0) {
			$sql .= ' AND f_pid='.$this->gallery_rootid;
		}
		$sql .= $this->gallery_sort;

		return _dbQuery($sql);

	}

	function getGallerySub($parent=0) {

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file ';
		$sql .= 'WHERE f_kid=0 AND f_aktiv=1 AND f_public=1';
		if($this->gallery_only) {
			$sql .= ' AND f_gallerystatus IN(3,2)';
		}
		$sql .= ' AND f_pid='.intval($parent);
		$sql .= $this->gallery_sort;

		return _dbQuery($sql);

	}

}
