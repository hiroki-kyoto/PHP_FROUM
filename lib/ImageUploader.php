<?php
include('settings.php');

class ImageUploader{
	/** the $file should be an uploaded file, while $types should be the allowed types of image **/
	public static function checkType($file, $types){
		if($file==null){return "File not found";}
		else{
			$fileName = $file['tmp_name'];
			$type = exif_imagetype($fileName);	
			switch($type){
				case 1: $tp = "gif";break;
				case 2: $tp = "jpg";break;
				case 3: $tp = "png";break;
				default: $tp = "unacceptable";	
			}
			if(in_array($tp, $types)){
				return $tp;
			}
			else{
				return "Uploaded file has an unacceptable type:".$type;
			}
		}
	}	
	
	
	public static function checkSize($img,$type, $sx, $sy){
		switch($type){
			case 'jpg': $im = imagecreatefromjpeg($img['tmp_name']); break;
			case 'gif': $im = imagecreatefromgif($img['tmp_name']); break;
			case 'png': $im = imagecreatefrompng($img['tmp_name']); break;
			default: $im = null;
		}
		
		if(imagesx($im)==$sx&&imagesy($im)==$sy){
			return true;
		}
		else{
			return false;
		}
	}
	
	public static function saveImage($img, $name){
		$state=move_uploaded_file($img['tmp_name'],settings::$IMAGE_UPLOAD_FOLDER.'/'.$name);
        if ($state)
        {
            return "OK";
        }
       else
       {
            switch($img['error'])
            {
                case 1 : return "UPLOAD FILE TOO BIG";
                case 2 : return "FILE TOO BIG";
                case 3 : return "FILE ONLY PARTLY UPLOADED";
                case 4 : return "NO FILE UPLOADED";
                case 5 : return "TEMP FILE NOT FOUND";
                case 6 : return "FAIL TO WRITE";
            }
        }
	}		
}


/** extended knowledge:

function exif_imgaetype returns:

1	IMAGETYPE_GIF
2	IMAGETYPE_JPEG
3	IMAGETYPE_PNG
4	IMAGETYPE_SWF
5	IMAGETYPE_PSD
6	IMAGETYPE_BMP
7	IMAGETYPE_TIFF_II（Intel byte sequence）
8	IMAGETYPE_TIFF_MM（Motorola byte sequence）
9	IMAGETYPE_JPC
10	IMAGETYPE_JP2
11	IMAGETYPE_JPX
12	IMAGETYPE_JB2
13	IMAGETYPE_SWC
14	IMAGETYPE_IFF
15	IMAGETYPE_WBMP
16	IMAGETYPE_XBM

**/

?>
