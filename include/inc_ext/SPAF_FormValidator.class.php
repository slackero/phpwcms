<?php
/* ----------------------------------------------------------------------------
  SPAF_FormValidator.class.php
 ------------------------------------------------------------------------------
  version  : 1.01
  author   : martynas@solmetra.com
 ------------------------------------------------------------------------------
  Form validation class
 --------------------------------------------------------------------------- */

class SPAF_FormValidator {
    // !!! EDITABLE CONFIGURATION ===============================================
    var $lib_dir = 'lib/';
    var $backgrounds = array(
        '01.png',
        '02.png',
        '03.png',
        '04.png',
        '05.png',
        '06.png',
        '07.png',
        '08.png',
        '09.png',
        '10.png',
        '11.png',
        '12.png',
    );
    var $fonts = array(
        'solmetra1.ttf',
        'solmetra2.ttf',
        'solmetra3.ttf',
        'solmetra4.ttf',
    );
    var $font_sizes = array( 13, 14, 15 );
    var $colors = array(
        array( 221, 27, 27 ),
        array( 94, 71, 212 ),
        array( 212, 71, 210 ),
        array( 8, 171, 0 ),
        array( 234, 142, 0 ),
    );
    var $shadow_color = array( 255, 255, 255 );
    var $hide_shadow = false;
    var $char_num = 5;
    var $chars = array(
        'A',
        'C',
        'D',
        'E',
        'F',
        'H',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'R',
        'S',
        'T',
        'Y',
        '3',
        '4',
        '6',
        '7',
        '9',
    );
    var $session_var = 'spaf_form_validator_tag';

    var $no_session = false;  // If this is set to true following config
    // variables must also be set

    var $work_dir = 'work'; // If $no_session is set to true set this variable
    // to the directory in which FormValidator will
    // create its temporary files.
    // If path begins with a backslash (i.e. /tmp),
    // FormValidator will assume it's an absolute path
    // Otherwise path will be treated as relative to
    // FormValidator class location.
    // Please note that this directory must be
    // writable to PHP scripts.

    var $work_ext = 'spaf'; // An extention to use for temporary work files

    var $tag_ttl = 120;    // Number of minutes to consider user tag valid
    // Used only in conjunction with:
    // $no_session = true

    var $tag_cookie = 'spaf_formvalidator'; // the name of cookie to be used
    // for tagging a user

    var $gc_prob = 1;      // Percental probability for garbage collector to
    // launch per each instance of FormValidator
    // class. Garbage collector is needed to remove
    // old user tag files from disk if you use
    // $no_session = true
    // 0 means GC will never launch
    // 100 means GC will launch everytime you
    // instantiate this class

    // !!! DO NOT CHANGE ANYTHING BELOW THIS LINE ===============================
    var $img_func_suffix = 'png';

    function __construct() {
        // set properties that might have been accidentally removed
        if ( ! isset( $this->session_var ) ) {
            $this->session_var = 'spaf_formvalidator';
        }
        if ( ! isset( $this->no_session ) ) {
            $this->no_session = false;
        }

        // below tasks are only required if $no_session is set to true
        if ( isset( $this->no_session ) && $this->no_session ) {
            // set class directory if none specified
            if ( $this->work_dir == '' ) {
                $this->work_dir = dirname( __FILE__ ) . '/';
            } // set a relative path
            elseif ( substr( $this->work_dir, 0, 1 ) != '/' ) {
                $this->work_dir = dirname( __FILE__ ) . '/' . $this->work_dir;
            }

            // add backslash at the end of path if necessary
            if ( substr( $this->work_dir, - 1 ) != '/' ) {
                $this->work_dir .= '/';
            }

            // launch garbage collector
            if ( mt_rand( 1, 100 ) < $this->gc_prob ) {
                $this->launchGC();
            }
        } // tasks that are required for session enabled operation
        else {
            // check if session is started
            if ( ! isset( $_SESSION ) ) {
                session_start();
            }
        }
    }

    function setLibDir( $dir ) {
        $this->lib_dir = $dir;
    }

    function tagUser() {
        if ( $this->no_session ) {
            // generate validation word and secret identity cookie
            $tag    = $this->getRandomString( $this->char_num );
            $cookie = md5( microtime() . $_SERVER['REMOTE_ADDR'] );

            // set cookie
            setcookie( $this->tag_cookie, $cookie, 0, '/' );
            $_COOKIE[ $this->tag_cookie ] = $cookie;

            // write to a file
            $this->writeFile( $this->work_dir . $cookie . '.' . $this->work_ext, $tag );
        } else {
            // set session variable
            // ATTENTION! Session must be already started with session_start()
            $_SESSION[ $this->session_var ] = $this->getRandomString( $this->char_num );
        }

        return true;
    }

    function getUserTag() {
        // get current tag
        if ( $this->no_session ) {
            if ( ! isset( $_COOKIE[ $this->tag_cookie ] ) || isset( $_GET['regen'] ) ) {
                // user is not tagged - issue new tag
                $this->tagUser();
            }

            // get the work file
            if ( ! file_exists( $this->work_dir . $_COOKIE[ $this->tag_cookie ] . '.' . $this->work_ext ) ) {
                // file does not exist - reissue the tag once again to recreate the file
                $this->tagUser();
            }

            return @file_get_contents( $this->work_dir . $_COOKIE[ $this->tag_cookie ] . '.' . $this->work_ext );
        } else {
            if ( ! isset( $_SESSION[ $this->session_var ] ) || isset( $_GET['regen'] ) ) {
                // user is not tagged - issue new tag
                $this->tagUser();
            }

            return $_SESSION[ $this->session_var ];
        }
    }

    function validRequest( $req ) {
        return strtolower( $this->getUserTag() ) === strtolower( $req );
    }

    function getRandomString( $chars = 5 ) {
        $str = '';
        $cnt = sizeof( $this->chars );
        for ( $i = 0; $i < $chars; $i ++ ) {
            $str .= $this->chars[ mt_rand( 0, $cnt - 1 ) ];
        }

        return $str;
    }

    function streamImage() {
        // select random background
        $background = $this->backgrounds[ mt_rand( 0, sizeof( $this->backgrounds ) - 1 ) ];

        // set proper image format according to selected background image
        $this->setImageFormat( $background );

        // create image resource
        $function = "imagecreatefrom" . $this->img_func_suffix;
        $image    = $function( $this->lib_dir . $background );

        // create color resources
        $colors      = array();
        $color_count = sizeof( $this->colors );
        for ( $i = 0; $i < $color_count; $i ++ ) {
            $colors[] = imagecolorallocate( $image, $this->colors[ $i ][0], $this->colors[ $i ][1],
                $this->colors[ $i ][2] );
        }
        $shadow = imagecolorallocate( $image, $this->shadow_color[0], $this->shadow_color[1], $this->shadow_color[2] );

        // get secret word from session
        $word = $this->getUserTag();

        // calculate geometrics
        $width  = imagesx( $image );
        $height = imagesy( $image );
        $lenght = strlen( $word );
        $step   = floor( ( $width / $lenght ) * 0.9 );

        // put letters on background
        for ( $i = 0; $i < $lenght; $i ++ ) {
            // get current character
            $char = substr( $word, $i, 1 );

            // randomize letter display characteristics
            $font_size = $this->font_sizes[ mt_rand( 0, sizeof( $this->font_sizes ) - 1 ) ];
            $data      = array(
                'size'  => $font_size,
                'angle' => mt_rand( - 20, 20 ),
                'x'     => $step * $i + 5,
                'y'     => mt_rand( $font_size + 5, $height - 5 ),
                'color' => $colors[ mt_rand( 0, $color_count - 1 ) ],
                'font'  => $this->lib_dir . $this->fonts[ mt_rand( 0, sizeof( $this->fonts ) - 1 ) ],
            );

            // put a shadow
            if ( ! isset( $this->hide_shadow ) || ! $this->hide_shadow ) {
                imagettftext( $image, $font_size, $data['angle'], $data['x'] + 1, $data['y'] + 1, $shadow,
                    $data['font'], $char );
            }

            // put a letter
            imagettftext( $image, $font_size, $data['angle'], $data['x'], $data['y'], $data['color'], $data['font'],
                $char );
        }

        // stream image to browser
        $function = "image" . $this->img_func_suffix;

        header( 'Content-Type: image/' . $this->img_func_suffix );
        $function( $image );
        imagedestroy( $image );

        return true;
    }

    function setImageFormat( $file ) {
        // get extention
        $arr = explode( '.', $file );
        $ext = strtolower( $arr[ sizeof( $arr ) - 1 ] );

        // set appropriate formats
        switch ( $ext ) {
            case 'gif':
            case 'png':
            case 'jpeg':
                $this->img_func_suffix = $ext;
                break;
            case 'jpg':
                $this->img_func_suffix = 'jpeg';
                break;
            default:
                // critical error - unsupported format
                die( 'ERROR: Unsupported format!' );
                break;
        }
    }

    function destroy() {
        if ( $this->no_session ) {
            // remove physical file and cookie
            @unlink( $this->work_dir . $_COOKIE[ $this->tag_cookie ] . '.' . $this->work_ext );
            unset( $_COOKIE[ $this->tag_cookie ] );
            setcookie( $this->tag_cookie, '', 0, '/' );
        } else {
            // remove session variable
            unset( $_SESSION[ $this->session_var ] );
        }

        return true;
    }

    function launchGC() {
        // open work directory
        if ( $dir = @opendir( $this->work_dir ) ) {
            // check each file
            while ( false !== ( $file = @readdir( $dir ) ) ) {
                $fdata = pathinfo( $file );
                if ( $fdata['extension'] == $this->work_ext && ( filemtime( $this->work_dir . $file ) < ( time() - ( $this->tag_ttl * 60 ) ) ) ) {
                    // remove expired file
                    @unlink( $this->work_dir . $file );
                }
            }
            @closedir( $dir );
        }

        return true;
    }

    function writeFile( $file, $content ) {
        $fl  = @fopen( $file, 'w' );
        $ret = @fwrite( $fl, $content );
        @fclose( $fl );

        return $ret;
    }

}
