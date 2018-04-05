<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\View\View;

class UploadHeader extends Helper
{
	public $fileArr = [];
	public $resizeWidth;
	public $resizeHeight;
	
	public function __construct($fileData,$resize_with,$resize_height)
	{
		$this->fileArr = $fileData;
		$this->resizeWidth = $resize_with ? $resize_with : 250;
		$this->resizeHeight= $resize_height ? $resize_height : 250;
	}
	/*Resize image */
	function resizeImage($resourceType,$image_width,$image_height) {
		$imageLayer = imagecreatetruecolor($this->resizeWidth,$this->resizeHeight);
		imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$this->resizeWidth,$this->resizeHeight, $image_width,$image_height);
		return $imageLayer;
	}
	
	/*Upload image*/
	public function upload()
	{
		$imageProcess = 0;
		if(isset($_POST["form_submit"])) {
			if(is_array($this->fileArr)) {
				$fileName = $this->fileArr['upload_image']['tmp_name']; 
				$sourceProperties = getimagesize($fileName);
				$resizeFileName = time();
				$uploadPath = "/uploads/";
				$fileExt = pathinfo($this->fileArr['upload_image']['name'], PATHINFO_EXTENSION);
				$uploadImageType = $sourceProperties[2];
				$sourceImageWidth = $sourceProperties[0];
				$sourceImageHeight = $sourceProperties[1];
				switch ($uploadImageType) {
					case IMAGETYPE_JPEG:
						$resourceType = imagecreatefromjpeg($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagejpeg($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		 
					case IMAGETYPE_GIF:
						$resourceType = imagecreatefromgif($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagegif($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		 
					case IMAGETYPE_PNG:
						$resourceType = imagecreatefrompng($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagepng($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		 
					default:
						$imageProcess = 0;
						break;
				}
				move_uploaded_file($file, $uploadPath. $resizeFileName. ".". $fileExt);
				$imageProcess = 1;
			}
		}
		return $imageProcess;
	}
}