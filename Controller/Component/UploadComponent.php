<?php

	class UploadComponent extends Component {
	
		/**
		  *	Private Vars
		  */
		  
		var $_file;
		var $_filepath;
		var $_destination;
		var $_name;
		var $_short;
		var $_rules;
		var $_allowed;
		
		/**
		  *	Public Vars
		  */
		var $errors;
		

		/**
		  * upload
		  * - handle uploads of any type
		  *		@ file - a file (file to upload) $_FILES[FILE_NAME]
		  *		@ path - string (where to upload to)
		  *		@ name [optional] - override saved filename
		  *		@ rules [optional] - how to handle file types
		  *			- rules['type'] = string ('resize','resizemin','resizecrop','crop')
		  *			- rules['size'] = array (x, y) or single number
		  *			- rules['output'] = string ('gif','png','jpg')
		  *			- rules['quality'] = integer (quality of output image)
		  *		@ allowed [optional] - allowed filetypes
		  *			- defaults to 'jpg','jpeg','gif','png'
		  *	ex:
		  * 	$upload = new upload($_FILES['MyFile'], 'uploads');
		  *
		  */
		
		function upload ($file, $destination, $name = NULL, $rules = NULL, $allowed = NULL) {
			//$permission_to_path=getcwd().'/img/uploads/';
			$permission_to_path=getcwd().'/app/webroot/img/uploads/';
			chmod($permission_to_path, 0777);
			$this->result = false;
			$this->error = false;
			// -- save parameters
			$this->_file = $file;
			$this->_name=$name;
			$this->_destination = $destination;
			if (!is_null($rules)) $this->_rules = $rules;
			
			if (!is_null($allowed)) { $this->_allowed = $allowed; } else { $this->_allowed = array('jpg','jpeg','gif','png','csv'); }
			
			// -- hack dir if / not provided
			if (substr($this->_destination,-1) != '/') {
				$this->_destination .= '/';
			}
			
			// -- check that FILE array is even set
			if (isset($file) && is_array($file) && !$this->upload_error($file['error'])) {
				
				// -- cool, now set some variables
				$fileName = ($name == NULL) ? $this->uniquename($destination . $file['name']) : $destination . $name;
			//	$fileName = ($name == NULL) ? $this->uniquename($destination . $file['name']) : $destination . time().$name;
				$inputFileName = $destination .$file['name'];
				$fileTmp = $file['tmp_name'];
				$fileSize = $file['size'];
				$fileType = $file['type'];
				$fileError = $file['error'];
				
				// -- update name
				$this->_name = $fileName;
				
				// -- error if not correct extension
				if(!in_array($this->ext($inputFileName),$this->_allowed)){
					$this->error("File type not allowed.");
				} else { 
				//echo $fileTmp;die;
					// -- it's been uploaded with php
					if (is_uploaded_file($fileTmp)) {
						
						// -- how are we handling this file
						if ($rules == NULL) {
							// -- where to put the file?
							$output = $fileName;
							// -- just upload it
							if (copy($fileTmp, $output)) {
							//echo "hi";
								chmod($output, 0755);
								$this->result = basename($this->_name);
							} else {
								$this->error("Could not move '$fileName' to '$destination'");
							}
						} else {
							// -- gd lib check
							if (function_exists("imagecreatefromjpeg")) {
							
								if (!isset($rules['output'])) $rules['output'] = NULL;
								if (!isset($rules['quality'])) $rules['quality'] = NULL;
								// -- handle it based on rules
								if (isset($rules['type']) && isset($rules['size'])) {
									
									$this->image($this->_file, $rules['type'], $rules['size'], $rules['output'], $rules['quality']);
								} else {
										
									$this->error("Invalid \"rules\" parameter");
								}
							} else {
								$this->error("GD library is not installed");
							}
						}
					} else {
						$this->error("Possible file upload attack on '$fileName'");
					}
				}
				
			} else {
				$this->error("Possible file upload attack");
			}
			return $this->result;
		}

		// -- return the extension of a file	
		function ext ($file) {
			$ext = trim(substr($file,strrpos($file,".")+1,strlen($file)));
			return $ext;
		}
		
		// -- add a message to stack (for outside checking)
		function error ($message) {
			if (!is_array($this->errors)) $this->errors = array();
			array_push($this->errors, $message);
		}	
		
		function image ($file, $type, $size, $output = NULL, $quality = NULL) {
			if (is_null($type)) $type = 'resize';
			if (is_null($size)) $size = 100;
			if (is_null($output)) $output = 'jpg';
			if (is_null($quality)) $quality = 75;

			// -- format variables
			$type = strtolower($type);
			$output = strtolower($output);
			if (is_array($size)) {
				$maxW = intval($size[0]);
				$maxH = intval($size[1]);
			} else {
				$maxScale = intval($size);
			}
			
			// -- check sizes
			if (isset($maxScale)) {
				if (!$maxScale) {
					$this->error("Max scale must be set");
				}
			} else {
				if (!$maxW || !$maxH) {
					$this->error("Size width and height must be set");
					return;
				}
				if ($type == 'resize') {
					$this->error("Provide only one number for size");
				}
			}
			
			// -- check output
			if ($output != 'jpg' && $output != 'png' && $output != 'gif') {
				$this->error("Cannot output file as " . strtoupper($output));
			}
			
			if (is_numeric($quality)) {
				$quality = intval($quality);
				if ($quality > 100 || $quality < 1) {
					$quality = 75;
				}
			} else {
				$quality = 75;
			}
			
			// -- get some information about the file
			$uploadSize = getimagesize($file['tmp_name']);
			//pr($uploadSize);die;
			$uploadWidth  = $uploadSize[0];
			$uploadHeight = $uploadSize[1];
			$uploadType = $uploadSize[2];
			
			if ($uploadType != 1 && $uploadType != 2 && $uploadType != 3) {
				$this->error ("File type must be GIF, PNG, or JPG to resize");
			}
			
			switch ($uploadType) {
				case 1: $srcImg = imagecreatefromgif($file['tmp_name']); break;
				case 2: $srcImg = imagecreatefromjpeg($file['tmp_name']); break;
				case 3: $srcImg = imagecreatefrompng($file['tmp_name']); break;
				default: $this->error ("File type must be GIF, PNG, or JPG to resize");
			}
						
			switch ($type) {
			
				case 'resize':
					# Maintains the aspect ration of the image and makes sure that it fits
					# within the maxW and maxH (thus some side will be smaller)
					// -- determine new size
					if (isset($maxScale) && ($uploadWidth > $maxScale || $uploadHeight > $maxScale)) {
						if ($uploadWidth > $uploadHeight) {
							$newX = $newY = $maxScale;
							/* $newX = $maxScale;
							$newY = ($uploadHeight*$newX)/$uploadWidth; */
						} else if ($uploadWidth < $uploadHeight) {
							$newX = $newY = $maxScale;
							/* $newY = $maxScale;
							$newX = ($newY*$uploadWidth)/$uploadHeight; */
						} else if ($uploadWidth == $uploadHeight) {
							$newX = $newY = $maxScale;
						}
					} elseif(isset($maxW) && isset($maxH)){
						$newX = $maxW;
						$newY = $maxH;
					} else {
						$newX = $uploadWidth;
						$newY = $uploadHeight;
					}

					$dstImg = imagecreatetruecolor($newX, $newY);
					//print_r($this->_name);die;
					imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
					//imagejpeg($dstImg, $this->_name, $quality);
					break;
					
				case 'resizemin':
					# Maintains aspect ratio but resizes the image so that once
					# one side meets its maxW or maxH condition, it stays at that size
					# (thus one side will be larger)
					#get ratios
					$ratioX = $maxW / $uploadWidth;
					$ratioY = $maxH / $uploadHeight;

					#figure out new dimensions
					if (($uploadWidth == $maxW) && ($uploadHeight == $maxH)) {
						$newX = $uploadWidth;
						$newY = $uploadHeight;
					} else if (($ratioX * $uploadHeight) > $maxH) {
						$newX = $maxW;
						$newY = ceil($ratioX * $uploadHeight);
					} else {
						$newX = ceil($ratioY * $uploadWidth);		
						$newY = $maxH;
					}

					$dstImg = imagecreatetruecolor($newX,$newY);
					imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
				
					break;
				
				case 'resizecrop':
					// -- resize to max, then crop to center
					$ratioX = $maxW / $uploadWidth;
					$ratioY = $maxH / $uploadHeight;

					if ($ratioX < $ratioY) { 
						$newX = round(($uploadWidth - ($maxW / $ratioY))/2);
						$newY = 0;
						$uploadWidth = round($maxW / $ratioY);
						$uploadHeight = $uploadHeight;
					} else { 
						$newX = 0;
						$newY = round(($uploadHeight - ($maxH / $ratioX))/2);
						$uploadWidth = $uploadWidth;
						$uploadHeight = round($maxH / $ratioX);
					}
					
					$dstImg = imagecreatetruecolor($maxW, $maxH);
					
					imagecopyresampled($dstImg, $srcImg, 0, 0, $newX, $newY, $maxW, $maxH, $uploadWidth, $uploadHeight);
					
					break;
				
				case 'crop':
					// -- a straight centered crop
					$startY = ($uploadHeight - $maxH)/2;
					$startX = ($uploadWidth - $maxW)/2;

					$dstImg = imageCreateTrueColor($maxW, $maxH);
					ImageCopyResampled($dstImg, $srcImg, 0, 0, $startX, $startY, $maxW, $maxH, $maxW, $maxH);
				
					break;
				
				default: $this->error ("Resize function \"$type\" does not exist");
			}	
		
			switch ($output) {
				case 'jpg':
					
					$write = imagejpeg($dstImg, $this->_name, $quality);
					break;
				case 'png':
					$write = imagepng($dstImg, $this->_name, 9);
					break;
				case 'gif':
					$write = imagegif($dstImg, $this->_name, 9);
					break;
			}
			
			// -- clean up
			imagedestroy($dstImg);
			
			if ($write) {
				$this->result = basename($this->_name);
			} else {
				$this->error("Could not write " . $this->_name . " to " . $this->_destination);
			}
		}
		
		function newname ($file) {
			return time() . "." . $this->ext($file);
		}
		
		function uniquename ($file) {
			$parts = pathinfo($file);
			$dir = $parts['dirname'];
			$file = ereg_replace('[^[:alnum:]_.-]','',$parts['basename']);
			$ext = $parts['extension'];
			if ($ext) {
				$ext = '.'.$ext;
				$file = substr($file,0,-strlen($ext));
			}
			$i = 0;
			while (file_exists($dir.'/'.$file.$i.$ext)) $i++;
			return $dir.'/'.$file.$i.$ext;
		}
		
		function upload_error ($errorobj) {
			$error = false;
			switch ($errorobj) {
			   case UPLOAD_ERR_OK: break;
			   case UPLOAD_ERR_INI_SIZE: $error = "The uploaded file exceeds the upload_max_filesize directive (".ini_get("upload_max_filesize").") in php.ini."; break;
			   case UPLOAD_ERR_FORM_SIZE: $error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."; break;
			   case UPLOAD_ERR_PARTIAL: $error = "The uploaded file was only partially uploaded."; break;
			   case UPLOAD_ERR_NO_FILE: $error = "No file was uploaded."; break;
			   case UPLOAD_ERR_NO_TMP_DIR: $error = "Missing a temporary folder."; break;
			   case UPLOAD_ERR_CANT_WRITE: $error = "Failed to write file to disk"; break;
			   default: $error = "Unknown File Error";
			}
			return ($error);
		}
		
		function createThumb($path1, $path2, $file_type, $new_w, $new_h, $squareSize = ''){
			/* read the source image */
			$source_image = FALSE;

			if (preg_match("/jpg|JPG|jpeg|JPEG/", $file_type)) {
				$source_image = imagecreatefromjpeg($path1);
			}
			elseif (preg_match("/png|PNG/", $file_type)) {
				
				if (!$source_image = @imagecreatefrompng($path1)) {
					$source_image = imagecreatefromjpeg($path1);
				}
			}
			elseif (preg_match("/gif|GIF/", $file_type)) {
				$source_image = imagecreatefromgif($path1);
			}		
			if ($source_image == FALSE) {
				$source_image = imagecreatefromjpeg($path1);
			}

			$orig_w = imageSX($source_image);
			$orig_h = imageSY($source_image);

			if ($orig_w < $new_w && $orig_h < $new_h) {
				$desired_width = $orig_w;
				$desired_height = $orig_h;
			} else {
				$scale = min($new_w / $orig_w, $new_h / $orig_h);
				$desired_width = ceil($scale * $orig_w);
				$desired_height = ceil($scale * $orig_h);
			}
					
			if ($squareSize != '') {
				$desired_width = $desired_height = $squareSize;
			}

			/* create a new, "virtual" image */
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
			// for PNG background white----------->
			$kek = imagecolorallocate($virtual_image, 255, 255, 255);
			imagefill($virtual_image, 0, 0, $kek);

			if ($squareSize == '') {
				/* copy source image at a resized size */
				imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $orig_w, $orig_h);
			} else {
				$wm = $orig_w / $squareSize;
				$hm = $orig_h / $squareSize;
				$h_height = $squareSize / 2;
				$w_height = $squareSize / 2;
				
				if ($orig_w > $orig_h) {
					$adjusted_width = $orig_w / $hm;
					$half_width = $adjusted_width / 2;
					$int_width = $half_width - $w_height;
					imagecopyresampled($virtual_image, $source_image, -$int_width, 0, 0, 0, $adjusted_width, $squareSize, $orig_w, $orig_h);
				}

				elseif (($orig_w <= $orig_h)) {
					$adjusted_height = $orig_h / $wm;
					$half_height = $adjusted_height / 2;
					imagecopyresampled($virtual_image, $source_image, 0,0, 0, 0, $squareSize, $adjusted_height, $orig_w, $orig_h);
				} else {
					imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $squareSize, $squareSize, $orig_w, $orig_h);
				}
			}

			if (@imagejpeg($virtual_image, $path2, 90)) {
				imagedestroy($virtual_image);
				imagedestroy($source_image);
				return TRUE;
			} else {
				return FALSE;
			}
		}
		public function rotate_Edit($profile_pic,$rotate_value){
			$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
			$rotate_value = 360 - (int)$rotate_value;  
			$file_path=getcwd().'/app/webroot/img/uploads/'.$profile_pic;
			$profile_pic=time().$profile_pic;
			$file_base_path=getcwd().'/app/webroot/img/uploads/'.$profile_pic;
			if(($image_type=='jpg')||($image_type=='jpeg')){
							
				$imageResource = imagecreatefromjpeg($file_path);
				$new_image = imagerotate($imageResource, $rotate_value, 0);
				//$new_image=WWW_BASE.'/img/uploads/'.'new.jpg';
				$path=getcwd().'/app/webroot/img/uploads/';
				chmod($path, 0777);
				imagejpeg($new_image, $file_base_path,90);
			
			}elseif($image_type=='png'){
				$imageResource = imagecreatefrompng($file_path);
				$new_image = imagerotate($imageResource, $rotate_value, 0);
				$path=getcwd().'/app/webroot/img/uploads/';
				chmod($path, 0777);
				imagepng($new_image, $file_base_path, 9);
			
			}elseif($image_type=='gif'){
				$imageResource = imagecreatefromgif($file_path);
				$new_image = imagerotate($imageResource, $rotate_value, 0);
				$path=getcwd().'/app/webroot/img/uploads/';
				chmod($path, 0777);
				imagegif($new_image, $file_base_path, 90);
			}
			return $profile_pic;
		}
	}
	
?>