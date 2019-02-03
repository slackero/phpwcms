<?php

/****************************************
Example of how to use this uploader class...
You can uncomment the following lines (minus the require) to use these as your defaults.

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

require('valums-file-uploader/server/php.php');
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload('uploads/');

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

/******************************************/



/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        if(!$temp) {
        	$temp = fopen("php://temp", "wb");
        }
		if(!$input || !$temp) {

			if($input) {
				fclose($input);
			}

			return false;
		}

		$realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
		if(!$target) {
			return false;
		}
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
	function getMimeType() {
		if (isset($_SERVER['X-Mime-Type']) && is_mimetype_format($_SERVER['X-Mime-Type'])) {
			return $_SERVER['X-Mime-Type'];
		} else {
			return get_mimetype_by_extension(which_ext($_GET['qqfile']));
		}
	}
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
	function getMimeType() {
		return $_FILES["qqfile"]["type"];
	}
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $forceExtension = true;
    private $sizeLimit = 10485760;
    private $file;
	private $uploadName;
	private $fileSize;
	private $fileExtension;
	private $fileType;
	private $forbiddenUploadName = array(
		'.htaccess', // Apache config
		'web.config', // IIS config
		'lighttpd.conf', // Lighttpd
		'nginx.conf' // Nginx
	);

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760, $forceExtension=true){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;
        $this->forceExtension = $forceExtension;

        $this->checkServerSettings();

		/*
        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
		*/
		if (isset($_FILES['qqfile'])) {
			$this->file = new qqUploadedFileForm();
		} elseif (isset($_GET['qqfile'])) {
			$this->file = new qqUploadedFileXhr();
		} else {
			$this->file = false;
		}

    }

	public function getUploadName(){
		if( isset( $this->uploadName ) ) {
            return $this->uploadName;
        }
	}

	public function getFileSize(){
		if( isset( $this->fileSize ) ) {
			return $this->fileSize;
		} else {
			return 0;
		}
	}

	public function getFileExtension(){
		if( isset( $this->fileExtension ) ) {
			return trim($this->fileExtension, '.');
		} else {
			return '';
		}
	}

	public function getFileType(){
		if ($this->file) {
            return $this->file->getMimeType();
        }
	}

	public function getName(){
		if ($this->file) {
            return $this->file->getName();
        }
	}

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($size_str){
        $size_str = trim($size_str);
        $size = (float)$size_str;
        $size_str = substr($size_str, -1);
        switch ($size_str) {
            case 'G': case 'g': $size *= 1024;
            case 'M': case 'm': $size *= 1024;
            case 'K': case 'k': $size *= 1024;
        }
        return (int)$size;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $newFileName=NULL, $replaceOldFile = FALSE, $lowerCaseExt=TRUE){

    	if(!empty($uploadDirectory)) {
    		$uploadDirectory = rtrim($uploadDirectory, '/') . '/';
    	}

        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.", 'success' => false);
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.', 'success' => false);
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty.', 'success' => false);
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large.', 'success' => false);
        }

        $pathinfo = pathinfo($this->file->getName());
        if(empty($newFileName)) {
        	$filename = $pathinfo['filename'];
        } else {
        	$cutoff = strrpos($newFileName, '.');
        	$filename = $cutoff !== false ? mb_substr($newFileName, 0, $cutoff) : $newFileName;
        }
        //$filename = md5(uniqid());
        $ext = @$pathinfo['extension'];		// hide notices if extension is empty

		if($this->forceExtension && !$ext) {
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has no extension, it should be one of '. $these . '.', 'success' => false);
		}
        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.', 'success' => false);
        }

        $ext = ($ext === '') ? $ext : '.' . ( $lowerCaseExt ? strtolower($ext) : $ext );

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . $ext)) {
                $filename .= '_' . uniqid();
            }
        }

		$this->fileSize = $size;
		$this->fileExtension = $ext;
		$this->uploadName = $filename . $ext;

		if(in_array(strtolower($this->uploadName), $this->forbiddenUploadName)) {
			return array('error' => 'File '. $this->uploadName . ' is forbidden for upload.', 'success' => false);
		}

        if ($this->file->save($uploadDirectory . $filename . $ext)){
            return array('success'=>true);
        } else {
            return array('success'=>false, 'error'=> 'Could not save uploaded file. The upload was cancelled, or server error encountered.');
        }

    }
}