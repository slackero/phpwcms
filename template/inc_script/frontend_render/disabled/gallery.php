<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// load phpwcmsImageGallery class
include_once PHPWCMS_ROOT.'/include/inc_lib/imagegallery.inc.php';

$gallery = new phpwcmsImageGallery();
//$gallery->setQuerySeparator('?');
$gallery->setAlias( 'aid=116' );
$gallery->image_sort            = 'DESC';
$gallery->image_limit           = 3;

$gallery->setGallerySort('NAME-DESC');
$gallery->list_thumbnail_crop   = 1;
$gallery->list_image_width      = 70;
$gallery->list_image_height     = 70;
$gallery->list_image_crop       = 1;
$gallery->list_template         = ' <div class="gallery">
    {TITLE}
    {THUMBNAIL}
    {DESCRIPTION}
    <div class="clear_both"></div>
</div>';
$gallery->list_title_prefix     = '<h3>[LINK]<strong>{DATE:d.m.Y}</strong> &#8211; ';
$gallery->list_thumbnail_prefix = '<div class="thumbnail">[LINK]';
$gallery->list_thumbnail_suffix = '[/LINK]</div>';
$gallery->list_thumbnail        = 0;
$gallery->list_thumbnail_width  = 50;
$gallery->list_thumbnail_height = 50;
$gallery->gallery_only = true;

if( strpos($content['all'], '{GALLERY}') !== FALSE ) {

    $galleries = array();

    // List the galleries in selected sub gallery
    if( isset($_getVar['subgallery']) ) {

        $_getVar['subgallery'] = intval($_getVar['subgallery']);

        $sql  = 'SELECT pf.*, pj.f_id AS f_root_id, pj.f_name AS f_root_name ';
        $sql .= 'FROM '.DB_PREPEND.'phpwcms_file pf ';
        $sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_file pj ';
        $sql .= 'ON pf.f_pid=pj.f_id ';
        $sql .= 'WHERE pf.f_id='.$_getVar['subgallery'];

        $subgallery  = _dbQuery($sql);

        $gallery_breadcrumb = array();

        if(isset($subgallery[0])) {

            $subgallery             = $subgallery[0];
            $subgallery_get         = '';
            $subgallery_class       = 'root';
            $gallery_breadcrumb_url = rel_url(array(), array('gallery', 'subgallery'), $gallery->alias);

            // check if parent directory exists
            if(!empty($subgallery['f_root_id'])) {
                $gallery_breadcrumb[]   = '<a href="'.$gallery_breadcrumb_url.'" class="root">'.html_specialchars($subgallery['f_root_name']).'</a>';
                $subgallery_get         = '&amp;subgallery='.$_getVar['subgallery'];
                $subgallery_class       = 'sub';
            }
            $gallery_breadcrumb[] = '<a href="'.$gallery_breadcrumb_url.$subgallery_get.'" class="'.$subgallery_class.'">'.html_specialchars($subgallery['f_name']).'</a>';

        }

        // show gallery
        if( isset($_getVar['gallery']) ) {

            $gallery->thumb_width           = 160;
            $gallery->thumb_height          = 160;
            $gallery->width                 = 750;
            $gallery->height                = 700;
            $gallery->detail_thumbnail_crop = 1;
            $gallery->detail_title_prefix   = '<h2><strong>{DATE:d.m.Y}</strong> &#8211; ';
            $gallery->detail_gallery_back   = '<div class="backlink">[LINK]Go back[/LINK]</div>';
            $gallery->image_limit           = 0;

            $gallery->detail_thumbnail_prefix   = '<div class="thumbnail">';
            $gallery->detail_thumbnail_suffix   = '<hr />{DOWNLOAD}</div>';

            $gallery->download              = true;
            $gallery->download_direct       = false;
            $galleries[] = $gallery->showGallery( $_getVar['gallery'] );

            // show title of selected gallery
            if(isset($gallery->gallery['f_name'])) {
                $gallery_breadcrumb[] = '<span class="active">'.html_specialchars($gallery->gallery['f_name']).'</span>';
            }

        } else {

            $galleries[] = $gallery->listGalleries( $_getVar['subgallery'] );

        }

        // render current gallery path
        if(count($gallery_breadcrumb)) {
            array_unshift($galleries, '<div class="gallery_breadcrumb">' . LF . '   ' . implode(' / ', $gallery_breadcrumb) . LF . '</div>');
        }

        $gallery = implode(LF, $galleries);


    // list sub galleries in root
    } else {

        $gallery_root = $gallery->getGalleryTree();

        $g = 0;

        foreach($gallery_root as $row) {

            $subgalleries   = $gallery->getGallerySub($row['f_id']);

            $subgallery     = '';
            if(is_array($subgalleries) && count($subgalleries)) {

                $subgallery  = '        <ul class="sub">' . LF;

                foreach($subgalleries as $sub) {

                    $subgallery .= '            <li class="sub">' . LF;
                    $subgallery .= '                <h3><a href="'.$gallery->url.'&amp;subgallery='.$sub['f_id'].'">';
                    $subgallery .=                  html_specialchars($sub['f_name']).'</a></h3>' . LF;
                    if($row['f_longinfo'] != '') {
                        $subgallery .= '        ' . plaintext_htmlencode($sub['f_longinfo']) . LF;
                    }
                    $subgallery .= '            </li>' . LF;
                }

                $subgallery .= '        </ul>' . LF;
            }

            $galleries[$g]  = ' <li class="root">' . LF;
            $galleries[$g] .= '     <h2>';

            if($subgallery != '') {
                $galleries[$g] .= html_specialchars($row['f_name']);
            } else {
                $galleries[$g] .= '<a href="'.$gallery->url.'&amp;subgallery='.$row['f_id'].'">' . html_specialchars($row['f_name']) . '</a>';
            }

            $galleries[$g] .= '</h2>' . LF;
            if($row['f_longinfo'] != '') {
                $galleries[$g] .= '     ' . plaintext_htmlencode($row['f_longinfo']) . LF;
            }
            $galleries[$g] .= $subgallery;
            $galleries[$g] .= ' </li>';
            $g++;

        }

        $gallery = count($galleries) ? '<ul class="gallery">' . LF . implode(LF, $galleries) . LF . '</ul>' : '';

    }

    $content['all']     = str_replace('{GALLERY}',              '<hr /><h1>Gallery</h1>'.LF.$gallery.LF.'<hr />',           $content['all']);
}
