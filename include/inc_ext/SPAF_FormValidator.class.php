<?php
/* ----------------------------------------------------------------------------
  SPAF_FormValidator.class.php
 ------------------------------------------------------------------------------
  version  : 1.02
  author   : martynas@solmetra.com
 ------------------------------------------------------------------------------
  Form validation class
 --------------------------------------------------------------------------- */

class SPAF_FormValidator {
    // !!! EDITABLE CONFIGURATION ===============================================
    public string $lib_dir = 'lib/';
    public array $backgrounds = [
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
        '100.png',
        '101.png',
    ];
    public array $fonts = [
        'solmetra1.ttf',
        'solmetra2.ttf',
        'solmetra3.ttf',
        'solmetra4.ttf',
    ];
    public array $font_sizes = [13, 14, 15];
    public array $colors = [
        [221, 27, 27],
        [94, 71, 212],
        [212, 71, 210],
        [8, 171, 0],
        [234, 142, 0],
    ];
    public array $shadow_color = [255, 255, 255];
    public bool $hide_shadow = false;
    public int $char_num = 5;
    public array $chars = [
        'A', 'C', 'D', 'E', 'F', 'H', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'R', 'S', 'T', 'Y', '3', '4', '6', '7', '9',
    ];
    public string $session_var = 'spaf_form_validator_tag';

    public bool $no_session = false;

    public string $work_dir = 'work';
    public string $work_ext = 'spaf';
    public int $tag_ttl = 120;
    public string $tag_cookie = 'spaf_formvalidator';
    public int $gc_prob = 1;

    // !!! DO NOT CHANGE ANYTHING BELOW THIS LINE ===============================
    public string $img_func_suffix = 'png';

    public function __construct() {
        if ($this->no_session) {
            if ($this->work_dir === '') {
                $this->work_dir = __DIR__ . '/';
            } elseif (!str_starts_with($this->work_dir, '/')) {
                $this->work_dir = __DIR__ . '/' . $this->work_dir;
            }

            if (!str_ends_with($this->work_dir, '/')) {
                $this->work_dir .= '/';
            }

            if (random_int(1, 100) < $this->gc_prob) {
                $this->launchGC();
            }
        } elseif (!isset($_SESSION)) {
            session_start();
        }
    }

    public function setLibDir(string $dir): void {
        $this->lib_dir = $dir;
    }

    public function tagUser(): bool {
        if ($this->no_session) {
            $tag    = $this->getRandomString($this->char_num);
            $cookie = md5(microtime() . $_SERVER['REMOTE_ADDR']);

            setcookie($this->tag_cookie, $cookie, 0, '/');
            $_COOKIE[$this->tag_cookie] = $cookie;

            $this->writeFile($this->work_dir . $cookie . '.' . $this->work_ext, $tag);
        } else {
            $_SESSION[$this->session_var] = $this->getRandomString($this->char_num);
        }

        return true;
    }

    public function getUserTag(): ?string {
        if ($this->no_session) {
            $cookie = isset($_COOKIE[$this->tag_cookie]) ? preg_replace('/[^a-f0-9]/i', '', $_COOKIE[$this->tag_cookie]) : '';
            if (isset($_GET['regen']) || strlen($cookie) !== 32) {
                $this->tagUser();
                $cookie = $_COOKIE[$this->tag_cookie];
            }

            $filePath = $this->work_dir . $cookie . '.' . $this->work_ext;
            if (!file_exists($filePath)) {
                $this->tagUser();
                $filePath = $this->work_dir . $_COOKIE[$this->tag_cookie] . '.' . $this->work_ext;
            }

            $content = @file_get_contents($filePath);
            return $content !== false ? $content : null;
        }

        if (!isset($_SESSION[$this->session_var]) || isset($_GET['regen'])) {
            $this->tagUser();
        }

        return $_SESSION[$this->session_var] ?? null;
    }

    public function validRequest(mixed $req): bool {
        return strtolower((string) $this->getUserTag()) === strtolower((string) $req);
    }

    public function getRandomString(int $chars = 5): string {
        $str = '';
        $cnt = count($this->chars);
        for ($i = 0; $i < $chars; $i++) {
            $str .= $this->chars[random_int(0, $cnt - 1)];
        }

        return $str;
    }

    public function streamImage(): bool {
        $background = $this->backgrounds[random_int(0, count($this->backgrounds) - 1)];

        $this->setImageFormat($background);

        $function = 'imagecreatefrom' . $this->img_func_suffix;
        $image    = $function($this->lib_dir . $background);

        $colors      = [];
        $color_count = count($this->colors);
        foreach ($this->colors as $iValue) {
            $colors[] = imagecolorallocate($image, $iValue[0], $iValue[1], $iValue[2]);
        }
        $shadow = imagecolorallocate($image, $this->shadow_color[0], $this->shadow_color[1], $this->shadow_color[2]);

        $word = (string) $this->getUserTag();

        $width  = imagesx($image);
        $height = imagesy($image);
        $lenght = strlen($word);
        $step   = (int) floor(($width / $lenght) * 0.9);

        for ($i = 0; $i < $lenght; $i++) {
            $char = substr($word, $i, 1);

            $font_size = $this->font_sizes[random_int(0, count($this->font_sizes) - 1)];
            $data      = [
                'size'  => $font_size,
                'angle' => random_int(-20, 20),
                'x'     => $step * $i + 5,
                'y'     => random_int($font_size + 5, $height - 5),
                'color' => $colors[random_int(0, $color_count - 1)],
                'font'  => $this->lib_dir . $this->fonts[random_int(0, count($this->fonts) - 1)],
            ];

            if (!$this->hide_shadow) {
                imagettftext($image, $font_size, $data['angle'], $data['x'] + 1, $data['y'] + 1, $shadow, $data['font'], $char);
            }

            imagettftext($image, $font_size, $data['angle'], $data['x'], $data['y'], $data['color'], $data['font'], $char);
        }

        $function = 'image' . $this->img_func_suffix;

        header('Content-Type: image/' . $this->img_func_suffix);
        $function($image);

        return true;
    }

    public function setImageFormat(string $file): void {
        $arr = explode('.', $file);
        $ext = strtolower(end($arr));

        switch ($ext) {
            case 'gif':
            case 'png':
            case 'jpeg':
                $this->img_func_suffix = $ext;
                break;
            case 'jpg':
                $this->img_func_suffix = 'jpeg';
                break;
            default:
                die('ERROR: Unsupported format!');
        }
    }

    public function destroy(): bool {
        if ($this->no_session) {
            $cookie = isset($_COOKIE[$this->tag_cookie]) ? preg_replace('/[^a-f0-9]/i', '', $_COOKIE[$this->tag_cookie]) : '';
            if (strlen($cookie) === 32) {
                @unlink($this->work_dir . $cookie . '.' . $this->work_ext);
            }
            unset($_COOKIE[$this->tag_cookie]);
            setcookie($this->tag_cookie, '', 0, '/');
        } else {
            unset($_SESSION[$this->session_var]);
        }

        return true;
    }

    public function launchGC(): bool {
        if ($dir = @opendir($this->work_dir)) {
            while (false !== ($file = @readdir($dir))) {
                $fdata = pathinfo($file);
                if (isset($fdata['extension']) && $fdata['extension'] === $this->work_ext && (filemtime($this->work_dir . $file) < (time() - ($this->tag_ttl * 60)))) {
                    @unlink($this->work_dir . $file);
                }
            }
            @closedir($dir);
        }

        return true;
    }

    public function writeFile(string $file, string $content): int|false {
        $fl  = @fopen($file, 'w');
        if ($fl === false) {
            return false;
        }
        $ret = @fwrite($fl, $content);
        @fclose($fl);

        return $ret;
    }
}
