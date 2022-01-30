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

/**
 * Image Manipulation class
 *
 * Taken from CodeIgniter and adopted for use in phpwcms.
 *
 * @author      EllisLab Dev Team
 * @copyright   Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @author      Oliver Georgi <og@phpwcms.org>
 * @copyright   Copyright (c) 2012, Oliver Georgi (http://phpwcms.org/)
 */
class Phpwcms_Image_lib {

    var $image_library = 'gd2';    // Can be: imagemagick, graphicsmagick, netpbm, gd, gd2
    var $library_path = '';
    var $dynamic_output = false;    // Whether to send to browser or write to disk
    var $source_image = '';
    var $dest_image = '';
    var $new_image = '';
    var $width = '';
    var $height = '';
    var $quality = PHPWCMS_QUALITY;
    var $create_thumb = false;
    var $thumb_marker = '_thumb';
    var $maintain_ratio = true;     // Whether to maintain aspect ratio when resizing or use hard values
    var $master_dim = 'auto';   // auto, height, or width.  Determines what to use as the master dimension
    var $rotation_angle = '';
    var $x_axis = '';
    var $y_axis = '';
    var $sharpen = false;
    var $target_ext = 'jpg';

    // Watermark Vars
    var $wm_text = '';           // Watermark text if graphic is not used
    var $wm_type = 'text';       // Type of watermarking.  Options:  text/overlay
    var $wm_x_transp = 4;
    var $wm_y_transp = 4;
    var $wm_overlay_path = '';           // Watermark image path
    var $wm_font_path = '';           // TT font
    var $wm_font_size = 17;           // Font size (different versions of GD will either use points or pixels)
    var $wm_vrt_alignment = 'B';          // Vertical alignment:   T M B
    var $wm_hor_alignment = 'C';          // Horizontal alignment: L R C
    var $wm_padding = 0;            // Padding around text
    var $wm_hor_offset = 0;            // Lets you push text to the right
    var $wm_vrt_offset = 0;            // Lets you push  text down
    var $wm_font_color = '#ffffff';    // Text color
    var $wm_shadow_color = '';           // Dropshadow color
    var $wm_shadow_distance = 2;            // Dropshadow distance
    var $wm_opacity = 50;           // Image opacity: 1 - 100  Only works with image

    // Private Vars
    var $source_folder = '';
    var $dest_folder = '';
    var $mime_type = '';
    var $orig_width = '';
    var $orig_height = '';
    var $source_ext = '';
    var $image_type = '';
    var $size_str = '';
    var $full_src_path = '';
    var $full_dst_path = '';
    var $create_fnc = 'imagecreatetruecolor';
    var $copy_fnc = 'imagecopyresampled';
    var $error_msg = array();
    var $wm_use_drop_shadow = false;
    var $wm_use_truetype = false;
    var $image_cache = array();
    var $image_current_vals = array();
    var $graphicsmagick = '';
    var $colorspace = 'RGB';
    var $animated_gif = false;

    // Language strings
    var $lang = array(
        'imglib_source_image_required'   => "You must specify a source image in your preferences.",
        'imglib_gd_required'             => "The GD image library is required for this feature.",
        'imglib_gd_required_for_props'   => "Your server must support the GD image library in order to determine the image properties.",
        'imglib_unsupported_imagecreate' => "Your server does not support the GD function required to process this type of image.",
        'imglib_gif_not_supported'       => "GIF images are often not supported due to licensing restrictions. You may have to use JPG or PNG images instead.",
        'imglib_jpg_not_supported'       => "JPG images are not supported.",
        'imglib_png_not_supported'       => "PNG images are not supported.",
        'imglib_webp_not_supported'      => "WebP images are not supported.",
        'imglib_jpg_or_png_required'     => "The image resize protocol specified in your preferences only works with JPEG or PNG image types.",
        'imglib_copy_error'              => "An error was encountered while attempting to replace the file. Please make sure your file directory is writable.",
        'imglib_rotate_unsupported'      => "Image rotation does not appear to be supported by your server.",
        'imglib_libpath_invalid'         => "The path to your image library is not correct.  Please set the correct path in your image preferences.",
        'imglib_image_process_failed'    => "Image processing failed. Please verify that your server supports the chosen protocol and that the path to your image library is correct.",
        'imglib_rotation_angle_required' => "An angle of rotation is required to rotate the image.",
        'imglib_writing_failed_gif'      => "GIF image.",
        'imglib_invalid_path'            => "The path to the image is not correct.",
        'imglib_copy_failed'             => "The image copy routine failed.",
        'imglib_missing_font'            => "Unable to find a font to use.",
        'imglib_save_failed'             => "Unable to save the image. Please make sure the image and file directory are writable.",
        'imglib_image_cannot_opened'     => 'Unable to open the image. This might happen if the image source is broken or the image is damaged.',
    );
    var $lang_localized = false;        // set to TRUE if overwritten once

    /**
     * Constructor
     *
     * @param   string
     *
     * @return  void
     */
    public function __construct($props = array()) {
        if (PHPWCMS_WEBP) {
            $this->target_ext = 'webp';
        }
        if (count($props)) {
            $this->initialize($props);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Initialize image properties
     *
     * Resets values in case this class is used in a loop.
     *
     * @return  void
     */
    function clear() {
        $props = array(
            'library_path',
            'source_image',
            'new_image',
            'width',
            'height',
            'rotation_angle',
            'x_axis',
            'y_axis',
            'wm_text',
            'wm_overlay_path',
            'wm_font_path',
            'wm_shadow_color',
            'source_folder',
            'dest_folder',
            'mime_type',
            'orig_width',
            'orig_height',
            'image_type',
            'size_str',
            'full_src_path',
            'full_dst_path',
        );
        foreach ($props as $val) {
            $this->$val = '';
        }
        $this->image_library = 'gd2';
        $this->dynamic_output = false;
        $this->quality = PHPWCMS_QUALITY;
        $this->create_thumb = false;
        $this->thumb_marker = '_thumb';
        $this->maintain_ratio = true;
        $this->master_dim = 'auto';
        $this->wm_type = 'text';
        $this->wm_x_transp = 4;
        $this->wm_y_transp = 4;
        $this->wm_font_size = 17;
        $this->wm_vrt_alignment = 'B';
        $this->wm_hor_alignment = 'C';
        $this->wm_padding = 0;
        $this->wm_hor_offset = 0;
        $this->wm_vrt_offset = 0;
        $this->wm_font_color = '#ffffff';
        $this->wm_shadow_distance = 2;
        $this->wm_opacity = 50;
        $this->create_fnc = 'imagecreatetruecolor';
        $this->copy_fnc = 'imagecopyresampled';
        $this->error_msg = array();
        $this->wm_use_drop_shadow = false;
        $this->wm_use_truetype = false;
        $this->sharpen = false;
        $this->colorspace = 'RGB';
        $this->animated_gif = false;
    }

    // --------------------------------------------------------------------

    /**
     * initialize image preferences
     *
     * @access  public
     *
     * @param   array
     *
     * @return  bool
     */
    function initialize($props = array()) {
        /*
         * Convert array elements into class variables
         */
        if (count($props) > 0) {
            foreach ($props as $key => $val) {
                if (property_exists($this, $key)) {
                    if ($key === 'wm_font_color' || $key === 'wm_shadow_color') {
                        if (preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $val, $matches)) {
                            /* $matches[1] contains our hex color value, but it might be
                             * both in the full 6-length format or the shortened 3-length
                             * value.
                             * We'll later need the full version, so we keep it if it's
                             * already there and if not - we'll convert to it. We can
                             * access string characters by their index as in an array,
                             * so we'll do that and use concatenation to form the final
                             * value:
                             */
                            $val = (strlen($matches[1]) === 6) ? '#' . $matches[1] : '#' . $matches[1][0] . $matches[1][0] . $matches[1][1] . $matches[1][1] . $matches[1][2] . $matches[1][2];
                        } else {
                            continue;
                        }
                    }
                    $this->$key = $val;
                }
            }
        }
        /*
         * Is there a source image?
         *
         * If not, there's no reason to continue
         *
         */
        if ($this->source_image == '') {
            $this->set_error('imglib_source_image_required');
            return false;
        }
        /*
         * Is getimagesize() Available?
         *
         * We use it to determine the image properties (width/height).
         * Note:  We need to figure out how to determine image
         * properties using ImageMagick and NetPBM
         *
         */
        if (!function_exists('getimagesize')) {
            $this->set_error('imglib_gd_required_for_props');
            return false;
        }
        $this->image_library = strtolower($this->image_library);
        if ($this->image_library === 'graphicsmagick' || $this->image_library === 'gm') {
            $this->graphicsmagick = 'gm ';
            $this->image_library = 'imagemagick';
        } else {
            $this->graphicsmagick = '';
        }
        /*
         * Set the full server path
         *
         * The source image may or may not contain a path.
         * Either way, we'll try use realpath to generate the
         * full server path in order to more reliably read it.
         *
         */
        if (function_exists('realpath') && @realpath($this->source_image) !== false) {
            $full_source_path = str_replace('\\', '/', realpath($this->source_image));
        } else {
            $full_source_path = $this->source_image;
        }
        $x = explode('/', $full_source_path);
        $this->source_image = end($x);
        $this->source_folder = str_replace($this->source_image, '', $full_source_path);
        // Set the Image Properties
        if (!$this->get_image_properties($this->source_folder . $this->source_image)) {
            return false;
        }
        $this->source_ext = $this->explode_name($this->source_image, true);
        /*
         * Assign the "new" image name/path
         *
         * If the user has set a "new_image" name it means
         * we are making a copy of the source image. If not
         * it means we are altering the original.  We'll
         * set the destination filename and path accordingly.
         *
         */
        if ($this->new_image === '') {
            $this->dest_image = $this->source_image;
            $this->dest_folder = $this->source_folder;
        } elseif (strpos($this->new_image, '/') === false) {
            $this->dest_folder = $this->source_folder;
            $this->dest_image = $this->new_image;
        } else {
            if (strpos($this->new_image, '/') === false && strpos($this->new_image, '\\') === false) {
                $full_dest_path = str_replace('\\', '/', realpath($this->new_image));
            } else {
                $full_dest_path = $this->new_image;
            }
            // Is there a file name?
            if (!preg_match('#\.(jpg|jpeg|gif|png|webp)$#i', $full_dest_path)) {
                $this->dest_folder = $full_dest_path . '/';
                $this->dest_image = $this->source_image;
            } else {
                $x = explode('/', $full_dest_path);
                $this->dest_image = end($x);
                $this->dest_folder = str_replace($this->dest_image, '', $full_dest_path);
            }
        }
        /*
         * Compile the finalized filenames/paths
         *
         * We'll create two master strings containing the
         * full server path to the source image and the
         * full server path to the destination image.
         * We'll also split the destination image name
         * so we can insert the thumbnail marker if needed.
         *
         */
        if ($this->create_thumb === false || $this->thumb_marker == '') {
            $this->thumb_marker = '';
        }
        $xp = $this->explode_name($this->dest_image);
        $filename = $xp['name'];
        $file_ext = $xp['ext'];
        $this->full_src_path = $this->source_folder . $this->source_image;
        $this->full_dst_path = $this->dest_folder . $filename . $this->thumb_marker . $file_ext;
        /*
         * Should we maintain image proportions?
         *
         * When creating thumbs or copies, the target width/height
         * might not be in correct proportion with the source
         * image's width/height.  We'll recalculate it here.
         *
         */
        if ($this->maintain_ratio === true && ($this->width != 0 || $this->height != 0)) {
            $this->image_reproportion();
        }
        /*
         * Was a width and height specified?
         *
         * If the destination width/height was
         * not submitted we will use the values
         * from the actual file
         *
         */
        if ($this->width == '') {
            $this->width = $this->orig_width;
        }
        if ($this->height == '') {
            $this->height = $this->orig_height;
        }
        // Set the quality
        $this->quality = trim(str_replace('%', '', $this->quality));
        if (!$this->quality || !preg_match('/^[0-9]+$/', $this->quality)) {
            $this->quality = PHPWCMS_QUALITY;
        }
        // Set the x/y coordinates
        $this->x_axis = ($this->x_axis == '' || !preg_match('/^[0-9]+$/', $this->x_axis)) ? 0 : $this->x_axis;
        $this->y_axis = ($this->y_axis == '' || !preg_match('/^[0-9]+$/', $this->y_axis)) ? 0 : $this->y_axis;
        // Watermark-related Stuff...
        if ($this->wm_font_color != '' && strlen($this->wm_font_color) == 6) {
            $this->wm_font_color = '#' . $this->wm_font_color;
        }
        if ($this->wm_shadow_color != '' && strlen($this->wm_shadow_color) == 6) {
            $this->wm_shadow_color = '#' . $this->wm_shadow_color;
        }
        if ($this->wm_overlay_path != '') {
            $this->wm_overlay_path = str_replace('\\', '/', realpath($this->wm_overlay_path));
        }
        if ($this->wm_shadow_color != '') {
            $this->wm_use_drop_shadow = true;
        } elseif ($this->wm_use_drop_shadow == true && $this->wm_shadow_color == '') {
            $this->wm_use_drop_shadow = false;
        }
        if ($this->wm_font_path != '') {
            $this->wm_use_truetype = true;
        }
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Image Resize
     *
     * This is a wrapper function that chooses the proper
     * resize function based on the protocol specified
     *
     * @access  public
     * @return  bool
     */
    function resize() {
        $protocol = 'image_process_' . ($this->image_library === 'gd2' ? 'gd' : $this->image_library);
        return $this->$protocol('resize');
    }

    /**
     * Image Centered Crop and Resize
     *
     * This is a wrapper function that chooses the proper
     * cropping and resize function based on the protocol specified
     * but only available for ImageMagick
     *
     * @return  bool
     */
    function crop_centered_resize() {
        $protocol = 'image_process_' . ($this->image_library === 'gd2' ? 'gd' : $this->image_library);
        $action = $this->image_library === 'imagemagick' ? 'crop-resize-center' : 'crop';
        return $this->$protocol($action);
    }

    // --------------------------------------------------------------------

    /**
     * Image Crop
     *
     * This is a wrapper function that chooses the proper
     * cropping function based on the protocol specified
     *
     * @access  public
     * @return  bool
     */
    function crop() {
        $protocol = 'image_process_' . ($this->image_library === 'gd2' ? 'gd' : $this->image_library);
        return $this->$protocol('crop');
    }

    // --------------------------------------------------------------------

    /**
     * Image Rotate
     *
     * This is a wrapper function that chooses the proper
     * rotation function based on the protocol specified
     *
     * @access  public
     * @return  bool
     */
    function rotate() {
        // Allowed rotation values
        $degs = array(90, 180, 270, 'vrt', 'hor');
        if ($this->rotation_angle == '' || !in_array($this->rotation_angle, $degs)) {
            $this->set_error('imglib_rotation_angle_required');
            return false;
        }
        // Reassign the width and height
        if ($this->rotation_angle == 90 || $this->rotation_angle == 270) {
            $this->width = $this->orig_height;
            $this->height = $this->orig_width;
        } else {
            $this->width = $this->orig_width;
            $this->height = $this->orig_height;
        }
        // Choose resizing function
        if ($this->image_library === 'imagemagick' || $this->image_library === 'netpbm') {
            $protocol = 'image_process_' . $this->image_library;
            return $this->$protocol('rotate');
        }
        if ($this->rotation_angle === 'hor' || $this->rotation_angle === 'vrt') {
            return $this->image_mirror_gd();
        } else {
            return $this->image_rotate_gd();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Image Process Using GD/GD2
     *
     * This function will resize or crop
     *
     * @access  public
     *
     * @param   string
     *
     * @return  bool
     */
    function image_process_gd($action = 'resize') {
        if ($this->animated_gif) {
            if (is_file($this->full_dst_path)) {
                return true;
            } elseif (!PHPWCMS_RESIZE_ANIMATED_GIF) {
                $copied = @copy($this->full_src_path, $this->full_dst_path);
                if ($copied) {
                    @chmod($this->full_dst_path, 0666);
                    return true;
                }
                return false;
            }
        }
        $v2_override = false;
        // If the target width/height match the source, AND if the new file name is not equal to the old file name
        // we'll simply make a copy of the original with the new name... assuming dynamic rendering is off.
        if ($this->dynamic_output === false && $this->colorspace != 'GRAY' && $this->orig_width == $this->width && $this->orig_height == $this->height) {
            if ($this->source_image != $this->new_image && @copy($this->full_src_path, $this->full_dst_path)) {
                @chmod($this->full_dst_path, 0666);
            }
            return true;
        }
        // Let's set up our values based on the action
        if ($action == 'crop') {
            // Reassign the source width/height if cropping
            $this->orig_width = $this->width;
            $this->orig_height = $this->height;
            // GD 2.0 has a cropping bug so we'll test for it
            if ($this->gd_version() !== false) {
                $gd_version = str_replace('0', '', $this->gd_version());
                $v2_override = $gd_version == 2;
            }
        } else {
            // If resizing the x/y axis must be zero
            $this->x_axis = 0;
            $this->y_axis = 0;
        }
        //  Create the image handle
        if (!($src_img = $this->image_create_gd())) {
            return false;
        }
        /* Create the image
         *
         * Old conditional which users report cause problems with shared GD libs who report themselves as "2.0 or greater"
         * it appears that this is no longer the issue that it was in 2004, so we've removed it, retaining it in the comment
         * below should that ever prove inaccurate.
         *
         * if ($this->image_library === 'gd2' && function_exists('imagecreatetruecolor') && $v2_override == FALSE)
         */
        if ($this->image_library === 'gd2' && function_exists('imagecreatetruecolor')) {
            $create = 'imagecreatetruecolor';
            $copy = 'imagecopyresampled';
        } else {
            $create = 'imagecreate';
            $copy = 'imagecopyresized';
        }
        $dst_img = $create($this->width, $this->height);
        if ($this->image_type === IMAGETYPE_PNG || $this->image_type === IMAGETYPE_GIF || $this->image_type === IMAGETYPE_WEBP) // png, gif and webp, preserve transparency
        {
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
        }
        if ($this->image_type === IMAGETYPE_GIF && ($transparent_index = imagecolorallocatealpha($dst_img, 255, 255, 255, 127))) // gif preserve transparency
        {
            imagefilledrectangle($dst_img, 0, 0, $this->width, $this->height, $transparent_index);
        }
        $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->orig_width, $this->orig_height);
        if ($this->colorspace == 'GRAY' && $create == 'imagecreatetruecolor') {
            imagefilter($dst_img, IMG_FILTER_GRAYSCALE);
        }
        // Show the image
        if ($this->dynamic_output == true) {
            $this->image_display_gd($dst_img);
        } elseif (!$this->image_save_gd($dst_img)) // Or save it
        {
            return false;
        }
        // Kill the file handles
        imagedestroy($dst_img);
        imagedestroy($src_img);
        // Set the file to 777
        @chmod($this->full_dst_path, 0666);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Image Process Using ImageMagick
     *
     * This function will resize, crop or rotate
     *
     * @access  public
     *
     * @param   string
     *
     * @return  bool
     */
    function image_process_imagemagick($action = 'resize') {
        //  Do we have a vaild library path?
        if ($this->library_path == '') {
            $this->set_error('imglib_libpath_invalid');
            return false;
        }
        if (!preg_match('/' . $this->graphicsmagick . 'convert$/i', $this->library_path)) {
            $this->library_path = rtrim($this->library_path, '/') . '/' . $this->graphicsmagick . 'convert';
        }
        // Execute the command
        $cmd = $this->library_path;
        $picnum = '[0]';
        if ($this->target_ext === 'jpg') {
            $cmd .= ' -colorspace ' . $this->colorspace . ' -type TrueColor';
        } elseif ($this->target_ext === 'png' || $this->target_ext === 'webp') {
            $cmd .= ' -colorspace ' . $this->colorspace;
        } elseif ($this->target_ext === 'gif') {
            // Check if it is an animated GIF an coalesce the image
            if ($this->animated_gif) {
                if (is_file($this->full_dst_path)) {
                    return true;
                } elseif (!PHPWCMS_RESIZE_ANIMATED_GIF) {
                    $copied = @copy($this->full_src_path, $this->full_dst_path);
                    if ($copied) {
                        @chmod($this->full_dst_path, 0666);
                        return true;
                    }
                    return false;
                }
                // The coalesce command
                $coalesce = $this->library_path . ' ' . escapeshellarg($this->full_src_path) . ' -coalesce ';
                if (!$this->graphicsmagick) {
                    $coalesce .= '-layers RemoveDups ';
                }
                $resized_gif = $this->full_dst_path . '.miff';
                $coalesce .= escapeshellarg($resized_gif) . ' 2>&1';
                // Run the command
                @exec($coalesce, $output, $retval);
                // Did it work?
                if ($retval > 0) {
                    // do nothing, but animation gets lost
                } else {
                    // preserve animated GIF
                    $picnum = '';
                    // Set the file to 666
                    @chmod($resized_gif, 0666);
                    // Use the coalesce image as new src
                    $this->full_src_path = $resized_gif;
                    $cmd .= ' -fuzz 3%';
                    if (!$this->graphicsmagick) {
                        $cmd .= ' -layers optimizePlus';
                    }
                }
            }
            $cmd .= ' -colorspace ' . $this->colorspace;
            $cmd .= ' +dither ';
            $cmd .= ' -colors 256';
            $this->sharpen = false;
        }
        if ($this->target_ext !== 'gif') {
            $cmd .= ' -quality ' . $this->quality;
        }
        if ($this->source_ext === 'pdf') {
            $cmd .= ' -define pdf:use-cropbox=true';
        }
        if ($picnum) {
            $cmd .= ' -antialias';
        }
        if ($this->sharpen) {
            //Sharpen Level
            switch ($this->sharpen) {
                case 1:
                    $cmd .= ' -sharpen 1x10';
                    break;
                case 2:
                    $cmd .= ' -sharpen 3x10';
                    break;
                case 3:
                    $cmd .= ' -sharpen 5x10';
                    break;
                case 4:
                    $cmd .= ' -sharpen 7x10';
                    break;
                case 5:
                    $cmd .= ' -sharpen 9x10';
                    break;
            }
        }
        if ($action == 'crop') {
            $cmd .= ' -crop ' . $this->width . 'x' . $this->height . '+' . $this->x_axis . '+' . $this->y_axis . ' ' . escapeshellarg($this->full_src_path . $picnum) . ' -strip ' . escapeshellarg($this->full_dst_path) . ' 2>&1';
        } elseif ($action == 'crop-resize-center') {
            // combined centered crop and resize
            $cmd .= ' -resize x' . ($this->height * 2) . " -resize '" . ($this->width * 2) . "x<' -resize 50% -gravity center ";
            $cmd .= ' -crop ' . $this->width . 'x' . $this->height . '+0+0 +repage ' . escapeshellarg($this->full_src_path . $picnum) . ' -strip ' . escapeshellarg($this->full_dst_path) . ' 2>&1';
        } elseif ($action == 'rotate') {
            switch ($this->rotation_angle) {
                case 'hor'  :
                    $angle = '-flop';
                    break;
                case 'vrt'  :
                    $angle = '-flip';
                    break;
                default     :
                    $angle = '-rotate ' . $this->rotation_angle;
                    break;
            }
            $cmd .= ' ' . $angle . ' ' . escapeshellarg($this->full_src_path . $picnum) . ' -strip ' . escapeshellarg($this->full_dst_path) . ' 2>&1';
        } else  // Resize
        {
            $cmd .= ' -resize ' . $this->width . 'x' . $this->height . ' ' . escapeshellarg($this->full_src_path . $picnum) . ' -strip ' . escapeshellarg($this->full_dst_path) . ' 2>&1';
        }
        $retval = 1;
        // debug commands
        //write_textfile(PHPWCMS_TEMP.'imagemagick-2.log', date('Y-m-d H:i:s').' - '.$cmd.LF, 'a');
        @exec($cmd, $output, $retval);
        if (!empty($resized_gif) && is_file($resized_gif)) {
            @unlink($resized_gif);
        }
        // Did it work?
        if ($retval > 0) {
            $this->set_error('imglib_image_process_failed');
            return false;
        }
        // Set the file to 666
        @chmod($this->full_dst_path, 0666);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Image Process Using NetPBM
     *
     * This function will resize, crop or rotate
     *
     * @access  public
     *
     * @param   string
     *
     * @return  bool
     */
    function image_process_netpbm($action = 'resize') {
        if ($this->library_path == '') {
            $this->set_error('imglib_libpath_invalid');
            return false;
        }
        // Build the resizing command
        switch ($this->image_type) {
            case IMAGETYPE_GIF:
                $cmd_in = 'giftopnm';
                $cmd_out = 'ppmtogif';
                break;
            case IMAGETYPE_JPEG:
                $cmd_in = 'jpegtopnm';
                $cmd_out = 'ppmtojpeg';
                if ($this->colorspace == 'GRAY') {
                    $cmd_out .= ' -grayscale';
                }
                break;
            case IMAGETYPE_PNG:
                $cmd_in = 'pngtopnm';
                $cmd_out = 'ppmtopng';
                if (strtoupper($this->colorspace) == 'GRAY') {
                    $cmd_out .= ' -grayscale';
                }
                break;
            case IMAGETYPE_WEBP:
                $cmd_in = 'webptopnm';
                $cmd_out = 'ppmtowebp';
                if (strtoupper($this->colorspace) == 'GRAY') {
                    $cmd_out .= ' -grayscale';
                }
                break;
        }
        if ($action == 'crop') {
            $cmd_inner = 'pnmcut -left ' . $this->x_axis . ' -top ' . $this->y_axis . ' -width ' . $this->width . ' -height ' . $this->height;
        } elseif ($action == 'rotate') {
            $angle = 'r90';
            switch ($this->rotation_angle) {
                case 90     :
                    $angle = 'r270';
                    break;
                case 180    :
                    $angle = 'r180';
                    break;
                case 270    :
                    $angle = 'r90';
                    break;
                case 'vrt'  :
                    $angle = 'tb';
                    break;
                case 'hor'  :
                    $angle = 'lr';
                    break;
            }
            $cmd_inner = 'pnmflip -' . $angle . ' ';
        } else // Resize
        {
            $cmd_inner = 'pnmscale -xysize ' . $this->width . ' ' . $this->height;
        }
        $cmd = $this->library_path . $cmd_in . ' ' . $this->full_src_path . ' | ' . $cmd_inner . ' | ' . $cmd_out . ' > ' . $this->dest_folder . 'netpbm.tmp';
        $retval = 1;
        @exec($cmd, $output, $retval);
        // Did it work?
        if ($retval > 0) {
            $this->set_error('imglib_image_process_failed');
            return false;
        }
        // With NetPBM we have to create a temporary image.
        // If you try manipulating the original it fails so
        // we have to rename the temp file.
        copy($this->dest_folder . 'netpbm.tmp', $this->full_dst_path);
        unlink($this->dest_folder . 'netpbm.tmp');
        @chmod($this->full_dst_path, 0666);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Image Rotate Using GD
     *
     * @access  public
     * @return  bool
     */
    function image_rotate_gd() {
        // Create the image handle
        if (!($src_img = $this->image_create_gd())) {
            return false;
        }
        // Set the background color
        // This won't work with transparent PNG files so we are
        // going to have to figure out how to determine the color
        // of the alpha channel in a future release.
        $white = imagecolorallocate($src_img, 255, 255, 255);
        // Rotate it!
        $dst_img = imagerotate($src_img, $this->rotation_angle, $white);
        // Show the image
        if ($this->dynamic_output == true) {
            $this->image_display_gd($dst_img);
        } elseif (!$this->image_save_gd($dst_img)) // ... or save it
        {
            return false;
        }
        // Kill the file handles
        imagedestroy($dst_img);
        imagedestroy($src_img);
        // Set the file to 666
        @chmod($this->full_dst_path, 0666);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Create Mirror Image using GD
     *
     * This function will flip horizontal or vertical
     *
     * @access  public
     * @return  bool
     */
    function image_mirror_gd() {
        if (!$src_img = $this->image_create_gd()) {
            return false;
        }
        $width = $this->orig_width;
        $height = $this->orig_height;
        if ($this->rotation_angle === 'hor') {
            for ($i = 0; $i < $height; $i++) {
                $left = 0;
                $right = $width - 1;
                while ($left < $right) {
                    $cl = imagecolorat($src_img, $left, $i);
                    $cr = imagecolorat($src_img, $right, $i);
                    imagesetpixel($src_img, $left, $i, $cr);
                    imagesetpixel($src_img, $right, $i, $cl);
                    $left++;
                    $right--;
                }
            }
        } else {
            for ($i = 0; $i < $width; $i++) {
                $top = 0;
                $bot = $height - 1;
                while ($top < $bot) {
                    $ct = imagecolorat($src_img, $i, $top);
                    $cb = imagecolorat($src_img, $i, $bot);
                    imagesetpixel($src_img, $i, $top, $cb);
                    imagesetpixel($src_img, $i, $bot, $ct);
                    $top++;
                    $bot--;
                }
            }
        }
        // Show the image
        if ($this->dynamic_output == true) {
            $this->image_display_gd($src_img);
        } elseif (!$this->image_save_gd($src_img)) // ... or save it
        {
            return false;
        }
        // Kill the file handles
        imagedestroy($src_img);
        // Set the file to 666
        @chmod($this->full_dst_path, 0666);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Image Watermark
     *
     * This is a wrapper function that chooses the type
     * of watermarking based on the specified preference.
     *
     * @access  public
     *
     * @param   string
     *
     * @return  bool
     */
    function watermark() {
        return ($this->wm_type === 'overlay') ? $this->overlay_watermark() : $this->text_watermark();
    }

    // --------------------------------------------------------------------

    /**
     * Watermark - Graphic Version
     *
     * @access  public
     * @return  bool
     */
    function overlay_watermark() {
        if (!function_exists('imagecolortransparent')) {
            $this->set_error('imglib_gd_required');
            return false;
        }
        // Fetch source image properties
        $this->get_image_properties();
        // Fetch watermark image properties
        $props = $this->get_image_properties($this->wm_overlay_path, true);
        $wm_img_type = $props['image_type'];
        $wm_width = $props['width'];
        $wm_height = $props['height'];
        // Create two image resources
        $wm_img = $this->image_create_gd($this->wm_overlay_path, $wm_img_type);
        $src_img = $this->image_create_gd($this->full_src_path);
        // Reverse the offset if necessary
        // When the image is positioned at the bottom
        // we don't want the vertical offset to push it
        // further down. We want the reverse, so we'll
        // invert the offset. Same with the horizontal
        // offset when the image is at the right
        $this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
        $this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));
        if ($this->wm_vrt_alignment == 'B') {
            $this->wm_vrt_offset = $this->wm_vrt_offset * -1;
        }
        if ($this->wm_hor_alignment == 'R') {
            $this->wm_hor_offset = $this->wm_hor_offset * -1;
        }
        // Set the base x and y axis values
        $x_axis = $this->wm_hor_offset + $this->wm_padding;
        $y_axis = $this->wm_vrt_offset + $this->wm_padding;
        //  Set the vertical position
        switch ($this->wm_vrt_alignment) {
            case 'T':
                break;
            case 'M':
                $y_axis += ($this->orig_height / 2) - ($wm_height / 2);
                break;
            case 'B':
                $y_axis += (int) $this->orig_height - (int) $wm_height;
                break;
        }
        //  Set the horizontal position
        switch ($this->wm_hor_alignment) {
            case 'L':
                break;
            case 'C':
                $x_axis += ($this->orig_width / 2) - ($wm_width / 2);
                break;
            case 'R':
                $x_axis += (int) $this->orig_width - (int) $wm_width;
                break;
        }
        //  Build the finalized image
        if ($wm_img_type == 3 && function_exists('imagealphablending')) {
            @imagealphablending($src_img, true);
        }
        // Set RGB values for text and shadow
        $rgba = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
        $alpha = ($rgba & 0x7F000000) >> 24;
        // make a best guess as to whether we're dealing with an image with alpha transparency or no/binary transparency
        if ($alpha > 0) {
            // copy the image directly, the image's alpha transparency being the sole determinant of blending
            imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
        } else {
            // set our RGB value from above to be transparent and merge the images with the specified opacity
            imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
            imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
        }
        // Output the image
        if ($this->dynamic_output == true) {
            $this->image_display_gd($src_img);
        } elseif (!$this->image_save_gd($src_img)) // ... or save it
        {
            return false;
        }
        imagedestroy($src_img);
        imagedestroy($wm_img);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Watermark - Text Version
     *
     * @access  public
     * @return  bool
     */
    function text_watermark() {
        if (!($src_img = $this->image_create_gd())) {
            return false;
        }
        if ($this->wm_use_truetype == true && !file_exists($this->wm_font_path)) {
            $this->set_error('imglib_missing_font');
            return false;
        }
        // Fetch source image properties
        $this->get_image_properties();
        // Set RGB values for text and shadow
        $this->wm_font_color = str_replace('#', '', $this->wm_font_color);
        $this->wm_shadow_color = str_replace('#', '', $this->wm_shadow_color);
        $R1 = hexdec(substr($this->wm_font_color, 0, 2));
        $G1 = hexdec(substr($this->wm_font_color, 2, 2));
        $B1 = hexdec(substr($this->wm_font_color, 4, 2));
        $R2 = hexdec(substr($this->wm_shadow_color, 0, 2));
        $G2 = hexdec(substr($this->wm_shadow_color, 2, 2));
        $B2 = hexdec(substr($this->wm_shadow_color, 4, 2));
        $txt_color = imagecolorclosest($src_img, $R1, $G1, $B1);
        $drp_color = imagecolorclosest($src_img, $R2, $G2, $B2);
        // Reverse the vertical offset
        // When the image is positioned at the bottom
        // we don't want the vertical offset to push it
        // further down. We want the reverse, so we'll
        // invert the offset. Note: The horizontal
        // offset flips itself automatically
        if ($this->wm_vrt_alignment == 'B') {
            $this->wm_vrt_offset = $this->wm_vrt_offset * -1;
        }
        if ($this->wm_hor_alignment == 'R') {
            $this->wm_hor_offset = $this->wm_hor_offset * -1;
        }
        // Set font width and height
        // These are calculated differently depending on
        // whether we are using the true type font or not
        if ($this->wm_use_truetype == true) {
            if ($this->wm_font_size == '') {
                $this->wm_font_size = 17;
            }
            $fontwidth = $this->wm_font_size - ($this->wm_font_size / 4);
            $fontheight = $this->wm_font_size;
            $this->wm_vrt_offset += $this->wm_font_size;
        } else {
            $fontwidth = imagefontwidth($this->wm_font_size);
            $fontheight = imagefontheight($this->wm_font_size);
        }
        // Set base X and Y axis values
        $x_axis = $this->wm_hor_offset + $this->wm_padding;
        $y_axis = $this->wm_vrt_offset + $this->wm_padding;
        // Set verticle alignment
        if ($this->wm_use_drop_shadow == false) {
            $this->wm_shadow_distance = 0;
        }
        $this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
        $this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));
        switch ($this->wm_vrt_alignment) {
            case     "T" :
                break;
            case "M":
                $y_axis += ($this->orig_height / 2) + ($fontheight / 2);
                break;
            case "B":
                $y_axis += ((int) $this->orig_height - $fontheight - $this->wm_shadow_distance - ($fontheight / 2));
                break;
        }
        $x_shad = $x_axis + $this->wm_shadow_distance;
        $y_shad = $y_axis + $this->wm_shadow_distance;
        // Set horizontal alignment
        switch ($this->wm_hor_alignment) {
            case "L":
                break;
            case "R":
                if ($this->wm_use_drop_shadow) {
                    $x_shad += ((int) $this->orig_width - $fontwidth * strlen($this->wm_text));
                    $x_axis += ((int) $this->orig_width - $fontwidth * strlen($this->wm_text));
                }
                break;
            case "C":
                if ($this->wm_use_drop_shadow) {
                    $x_shad += floor(($this->orig_width - $fontwidth * strlen($this->wm_text)) / 2);
                    $x_axis += floor(($this->orig_width - $fontwidth * strlen($this->wm_text)) / 2);
                }
                break;
        }
        //  Add the text to the source image
        if ($this->wm_use_truetype && $this->wm_use_drop_shadow) {
            imagettftext($src_img, $this->wm_font_size, 0, $x_shad, $y_shad, $drp_color, $this->wm_font_path, $this->wm_text);
            imagettftext($src_img, $this->wm_font_size, 0, $x_axis, $y_axis, $txt_color, $this->wm_font_path, $this->wm_text);
        } elseif ($this->wm_use_drop_shadow) {
            imagestring($src_img, $this->wm_font_size, $x_shad, $y_shad, $this->wm_text, $drp_color);
            imagestring($src_img, $this->wm_font_size, $x_axis, $y_axis, $this->wm_text, $txt_color);
        }
        // Output the final image
        if ($this->dynamic_output == true) {
            $this->image_display_gd($src_img);
        } else {
            $this->image_save_gd($src_img);
        }
        imagedestroy($src_img);
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Create Image - GD
     *
     * This simply creates an image resource handle
     * based on the type of image being processed
     *
     * @access  public
     *
     * @param   string
     *
     * @return  resource
     */
    function image_create_gd($path = '', $image_type = '') {
        if ($path == '') {
            $path = $this->full_src_path;
        }
        if ($image_type == '') {
            $image_type = $this->image_type;
        }

        switch ($image_type) {
            case IMAGETYPE_GIF:
                if (!function_exists('imagecreatefromgif')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
                    return false;
                }
                $im = @imagecreatefromgif($path);
                break;
            case IMAGETYPE_JPEG:
                if (!function_exists('imagecreatefromjpeg')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
                    return false;
                }
                $im = @imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                if (!function_exists('imagecreatefrompng')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
                    return false;
                }
                $im = @imagecreatefrompng($path);
                break;
            case IMAGETYPE_WEBP:
                if (!function_exists('imagecreatefromwebp')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_webp_not_supported'));
                    return false;
                }
                $im = @imagecreatefromwebp($path);
                break;
            default:
                $im = null;
        }
        if ($im !== null) {
            if ($im === '') {
                $this->set_error('imglib_image_cannot_opened');
                return false;
            }
            $this->gd_fix_orientation($im, $path);
            return $im;
        }
        $this->set_error(array('imglib_unsupported_imagecreate'));
        return false;
    }

    /**
     * gd_fix_orientation function.
     *
     * @access public
     *
     * @param   mixed &$im
     * @param   mixed  $imagename
     *
     * @return void
     */
    function gd_fix_orientation(&$im, $imagename) {
        // Try to handle exif based orientation
        if (!$im || !function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($imagename);
        if ($exif && isset($exif['Orientation'])) {
            if ($exif['Orientation'] === 1) {
                return true;
            }
            if ($exif['Orientation'] === 6 || $exif['Orientation'] === 5) {
                $im = imagerotate($im, 270, null);
            } elseif ($exif['Orientation'] === 3 || $exif['Orientation'] === 4) {
                $im = imagerotate($im, 180, null);
            } elseif ($exif['Orientation'] === 8 || $exif['Orientation'] === 7) {
                $im = imagerotate($im, 90, null);
            }
            if ($exif['Orientation'] === 5 || $exif['Orientation'] === 4 || $exif['Orientation'] === 7) {
                imageflip($im, IMG_FLIP_HORIZONTAL);
            }
            return true;
        }
        return false;
    }


    // --------------------------------------------------------------------

    /**
     * Write image file to disk - GD
     *
     * Takes an image resource as input and writes the file
     * to the specified destination
     *
     * @access  public
     *
     * @param   resource
     *
     * @return  bool
     */
    function image_save_gd($resource) {
        if (PHPWCMS_WEBP && $this->target_ext === 'webp' && function_exists('imagewebp') && imagewebp($resource, $this->full_dst_path, $this->quality)) {
            return true;
        }
        switch ($this->image_type) {
            case IMAGETYPE_GIF:
                if (!function_exists('imagegif')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
                    return false;
                }
                if (!@imagegif($resource, $this->full_dst_path)) {
                    $this->set_error('imglib_save_failed');
                    return false;
                }
                break;
            case IMAGETYPE_JPEG:
                if (!function_exists('imagejpeg')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
                    return false;
                }
                if (!@imagejpeg($resource, $this->full_dst_path, $this->quality)) {
                    $this->set_error('imglib_save_failed');
                    return false;
                }
                break;
            case IMAGETYPE_PNG:
                if (!function_exists('imagepng')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
                    return false;
                }
                if (!@imagepng($resource, $this->full_dst_path)) {
                    $this->set_error('imglib_save_failed');
                    return false;
                }
                break;
            case IMAGETYPE_WEBP:
                if (!function_exists('imagewebp')) {
                    $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
                    return false;
                }
                if (!@imagewebp($resource, $this->full_dst_path, $this->quality)) {
                    $this->set_error('imglib_save_failed');
                    return false;
                }
                break;
            default:
                $this->set_error('imglib_unsupported_imagecreate');
                return false;
        }
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Dynamically outputs an image
     *
     * @param   resource
     *
     * @return  void
     */
    function image_display_gd($resource) {
        header('Content-Disposition: filename=' . $this->source_image . ';');
        header('Content-Type: ' . $this->mime_type);
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        switch ($this->image_type) {
            case IMAGETYPE_GIF:
                imagegif($resource);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($resource, null, $this->quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($resource);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($resource, null, $this->quality);
                break;
            default:
                echo 'Unable to display the image';
                break;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Re-proportion Image Width/Height
     *
     * When creating thumbs, the desired width/height
     * can end up warping the image due to an incorrect
     * ratio between the full-sized image and the thumb.
     *
     * This function lets us re-proportion the width/height
     * if users choose to maintain the aspect ratio when resizing.
     *
     * @access  public
     * @return  void
     */
    function image_reproportion() {
        if (($this->width == 0 && $this->height == 0) || $this->orig_width == 0 || $this->orig_height == 0
            || (!preg_match('/^[0-9]+$/', $this->width) && !preg_match('/^[0-9]+$/', $this->height))
            || !preg_match('/^[0-9]+$/', $this->orig_width)
            || !preg_match('/^[0-9]+$/', $this->orig_height)) {
            return;
        }
        // Sanitize so we don't call preg_match() anymore
        $this->width = (int) $this->width;
        $this->height = (int) $this->height;
        if ($this->master_dim !== 'width' && $this->master_dim !== 'height') {
            if ($this->width > 0 && $this->height > 0) {
                $this->master_dim = ((($this->orig_height / $this->orig_width) - ($this->height / $this->width)) < 0) ? 'width' : 'height';
            } else {
                $this->master_dim = ($this->height === 0) ? 'width' : 'height';
            }
        } elseif (($this->master_dim === 'width' && $this->width === 0) || ($this->master_dim === 'height' && $this->height === 0)) {
            return;
        }
        if ($this->master_dim === 'width') {
            $this->height = (int) ceil($this->width * $this->orig_height / $this->orig_width);
        } else {
            $this->width = (int) ceil($this->orig_width * $this->height / $this->orig_height);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get image properties
     *
     * A helper function that gets info about the file
     *
     * @access  public
     *
     * @param   string
     * @param   bool
     *
     * @return  mixed
     */
    function get_image_properties($path = '', $return = false) {
        // For now we require GD but we should
        // find a way to determine this using IM or NetPBM
        if ($path === '') {
            $path = $this->full_src_path;
        }
        if (!file_exists($path)) {
            $this->set_error('imglib_invalid_path');
            return false;
        }
        $cache = md5($path);
        if (!isset($this->image_cache[$cache])) {
            $vals = getimagesize($path);
            if ($this->image_library === 'gd2') {
                if (!isset($vals[2])) {
                    return false;
                }
            } elseif(!isset($vals[2])) {
                return true; // the image lib might handle this format
            }
            $types = array(
                IMAGETYPE_GIF => 'gif',
                IMAGETYPE_JPEG => 'jpeg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_WEBP => 'webp'
            );
            $mime = isset($vals[2]) && isset($types[$vals[2]]) ? 'image/' . $types[$vals[2]] : 'image/jpg';
            $this->image_current_vals = $vals;
            $this->image_cache[$cache] = array(
                'orig_width'  => $vals[0],
                'orig_height' => $vals[1],
                'image_type'  => $vals[2],
                'size_str'    => $vals[3],
                'mime_type'   => $mime,
            );
        }
        if ($return) {
            return $this->image_cache[$cache];
        }
        $this->orig_width = $this->image_cache[$cache]['orig_width'];
        $this->orig_height = $this->image_cache[$cache]['orig_height'];
        $this->image_type = $this->image_cache[$cache]['image_type'];
        $this->size_str = $this->image_cache[$cache]['size_str'];
        $this->mime_type = $this->image_cache[$cache]['mime_type'];
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Size calculator
     *
     * This function takes a known width x height and
     * recalculates it to a new size. Only one
     * new variable needs to be known
     *
     *  $props = array(
     *          'width'     => $width,
     *          'height'    => $height,
     *          'new_width' => 40,
     *          'new_height'    => ''
     *      );
     *
     * @access  public
     *
     * @param   array
     *
     * @return  array
     */
    function size_calculator($vals) {
        if (!is_array($vals)) {
            return;
        }
        $allowed = array('new_width', 'new_height', 'width', 'height');
        foreach ($allowed as $item) {
            if (!isset($vals[$item]) || $vals[$item] == '') {
                $vals[$item] = 0;
            }
        }
        if ($vals['width'] == 0 || $vals['height'] == 0) {
            return $vals;
        }
        if ($vals['new_width'] == 0) {
            $vals['new_width'] = ceil($vals['width'] * $vals['new_height'] / $vals['height']);
        } elseif ($vals['new_height'] == 0) {
            $vals['new_height'] = ceil($vals['new_width'] * $vals['height'] / $vals['width']);
        }
        return $vals;
    }

    // --------------------------------------------------------------------

    /**
     * Explode source_image
     *
     * This is a helper function that extracts the extension
     * from the source_image.  This function lets us deal with
     * source_images with multiple periods, like: my.cool.jpg
     * It returns an associative array with two elements:
     * $array['ext']  = '.jpg';
     * $array['name'] = 'my.cool';
     *
     * @access  public
     *
     * @param   array
     * @param   bool
     *
     * @return  array
     */
    function explode_name($source_image, $ext_only = false) {
        $ext = strrchr($source_image, '.');
        $name = ($ext === false) ? $source_image : substr($source_image, 0, -strlen($ext));
        return $ext_only ? strtolower(trim($ext, '.')) : array('ext' => $ext, 'name' => $name);
    }

    // --------------------------------------------------------------------

    /**
     * Is GD Installed?
     *
     * @return  bool
     */
    function gd_loaded() {
        if (!extension_loaded('gd')) {
            /* As it is stated in the PHP manual, dl() is not always available
             * and even if so - it could generate an E_WARNING message on failure
             */
            return (function_exists('dl') && @dl('gd.so'));
        }
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Get GD version
     *
     * @access  public
     * @return  mixed
     */
    function gd_version() {
        if (function_exists('gd_info')) {
            $gd_version = @gd_info();
            return preg_replace('/\D/', '', $gd_version['GD Version']);
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Set error message
     *
     * @param   string
     *
     * @return  void
     */
    function set_error($msg) {
        if (!$this->lang_localized) {
            $local_lang = strtolower(isset($_SESSION["wcs_user_lang"]) ? $_SESSION["wcs_user_lang"] : $GLOBALS['phpwcms']['DOCTYPE_LANG']);
            if ($local_lang && is_file(PHPWCMS_ROOT . '/include/inc_lang/image/image.' . $local_lang . '.php')) {
                include PHPWCMS_ROOT . '/include/inc_lang/image/image.' . $local_lang . '.php';
                $this->lang = array_merge($this->lang, $ci_lang);
                $this->lang_localized = true;
            }
        }
        if (is_array($msg)) {
            foreach ($msg as $val) {
                $msg = ($this->lang[$val] == false) ? $val : $this->lang[$val];
                $this->error_msg[] = $msg;
            }
        } else {
            $msg = ($this->lang[$msg] == false) ? $msg : $this->lang[$msg];
            $this->error_msg[] = $msg;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Show error messages
     *
     * @param   string
     *
     * @return  string
     */
    function display_errors($open = '<p>', $close = '</p>', $wrap_open = '', $wrap_close = '') {
        return (count($this->error_msg) > 0) ? $wrap_open . $open . implode($close . $open, $this->error_msg) . $close . $wrap_close : '';
    }

}
// END Image_lib Class
/* End of file Image_lib.php */
/* Location: ./system/libraries/Image_lib.php */
