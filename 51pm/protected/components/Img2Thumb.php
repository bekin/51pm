<?php
// Important: Must be includeable from outside the Mambo Framework!
// defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/**
* @package mambo-phpShop
* 
* Adaption for Mambo:
* 	@copyright (C) 2004-2005 Soeren Eberhardt
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* mambo-phpShop is Free Software.
* mambo-phpShop comes with absolute no warranty.
*
* www.mambo-phpshop.net
*/
/**
*	class Image2Thumbnail
*   Thumbnail creation with PHP4 and GDLib (recommended, but not mandatory: 2.0.1 !)
*
*
*   @author     Andreas Martens <heyn@plautdietsch.de>
*   @author     Patrick Teague <webdude@veslach.com>
*   @author     Soeren Eberhardt <soeren@mambo-phpshop.net>
*	@version	1.0b
*   @date       modified 11/22/2004
*   @modifications 
*   - added support for GDLib < 2.0.1
*	- added support for reading gif images 
*	- makes jpg thumbnails
*	- changed several groups of 'if' statements to single 'switch' statements
*   - commented out original code so modification could be identified.
*/

class Img2Thumb	{
// New modification
/**
*	private variables - do not use
*	
*	@var int $bg_red				0-255 - red color variable for background filler
*	@var int $bg_green				0-255 - green color variable for background filler
*	@var int $bg_blue				0-255 - blue color variable for background filler
*	@var int $maxSize				0-1 - true/false - should thumbnail be filled to max pixels
*/
	var $bg_red;
	var $bg_green;
	var $bg_blue;
	var $maxSize;

/**
*   Constructor - requires following vars:
*	
*	@param string $filename			image path
*	
*	These are additional vars:
*	
*	@param int $newxsize			new maximum image width
*	@param int $newysize			new maximum image height
*	@param string $fileout			output image path
*	@param int $thumbMaxSize		whether thumbnail should have background fill to make it exactly $newxsize x $newysize
*	@param int $bgred				0-255 - red color variable for background filler
*	@param int $bggreen				0-255 - green color variable for background filler
*	@param int $bgblue				0-255 - blue color variable for background filler
*	
*/
	function Img2Thumb($filename, $newxsize=60, $newysize=60, $fileout='',
		$thumbMaxSize=0, $bgred=0, $bggreen=0, $bgblue=0)
	{
		global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_COOKIE_VARS;
		
		if (isset($HTTP_COOKIE_VARS))
			$httpvars = $HTTP_COOKIE_VARS;
		else if (isset($HTTP_POST_VARS))
			$httpvars =  $HTTP_POST_VARS;
   		else if (isset($HTTP_GET_VARS))
   			$httpvars =  $HTTP_GET_VARS;
		
//	New modification - checks color int to be sure within range
		if($thumbMaxSize)
		{
			$this->maxSize = true;
		}
		else
		{
			$this->maxSize = false;
		}
		if($bgred>=0 || $bgred<=255)
		{
			$this->bg_red = $bgred;
		}
		else
		{
			$this->bg_red = 0;
		}
		if($bggreen>=0 || $bggreen<=255)
		{
			$this->bg_green = $bggreen;
		}
		else
		{
			$this->bg_green = 0;
		}
		if($bgblue>=0 || $bgblue<=255)
		{
			$this->bg_blue = $bgblue;
		}
		else
		{
			$this->bg_blue = 0;
		}
		
		
		$this -> NewImgCreate($filename,$newxsize,$newysize,$fileout);
	}
	
/**
*  
*	private function - do not call
*
*/
	function NewImgCreate($filename,$newxsize,$newysize,$fileout)
	{
		$type = $this->GetImgType($filename);
		
		/* Original code removed in favor of 'switch' statement
		if ($type=="png")
		{
			 $orig_img = imagecreatefrompng($filename);
		}
		if ($type=="jpg")
		{
			 $orig_img = imagecreatefromjpeg($filename);
		}
		*/
		switch($type)
		{
			case "gif":
				// unfortunately this function does not work on windows
				// via the precompiled php installation :(
				// it should work on all other systems however.
				if( function_exists("imagecreatefromgif") )
				{
					$orig_img = imagecreatefromgif($filename);
					break;
				}
				else
				{
					print( "sorry, this server doesn't support <b>imagecreatefromgif()</b>" );
					exit;
					break;
				}
			case "jpg":
				$orig_img = imagecreatefromjpeg($filename);
				break;
			case "png":
				$orig_img = imagecreatefrompng($filename);
				break;
		}
		
		$new_img =$this->NewImgResize($orig_img,$newxsize,$newysize,$filename);

		if (!empty($fileout))
		{
			 $this-> NewImgSave($new_img,$fileout,$type);
		}
		else
		{
			 $this->NewImgShow($new_img,$type);
		}
		
		ImageDestroy($new_img);
		ImageDestroy($orig_img);
	}
	
	/**
*  
*	private function - do not call
*	includes function ImageCreateTrueColor and ImageCopyResampled which are available only under GD 2.0.1 or higher !
*/
	function NewImgResize($orig_img,$newxsize,$newysize,$filename)
	{
		//getimagesize returns array
		// [0] = width in pixels
		// [1] = height in pixels
		// [2] = type
		// [3] = img tag "width=xx height=xx" values
		
		$orig_size = getimagesize($filename);

		$maxX = $newxsize;
		$maxY = $newysize;
		
		if ($orig_size[0]<$orig_size[1])
		{
			$newxsize = $newysize * ($orig_size[0]/$orig_size[1]);
			$adjustX = ($maxX - $newxsize)/2;
			$adjustY = 0;
		}
		else
		{
			$newysize = $newxsize / ($orig_size[0]/$orig_size[1]);
			$adjustX = 0;
			$adjustY = ($maxY - $newysize)/2;
		}
		
		/* Original code removed to allow for maxSize thumbnails
		$im_out = ImageCreateTrueColor($newxsize,$newysize);
		ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0,
			$newxsize, $newysize,$orig_size[0], $orig_size[1]);
		*/

		//	New modification - creates new image at maxSize
		if( $this->maxSize )
		{
			if( function_exists("imagecreatetruecolor") )
			  $im_out = imagecreatetruecolor($maxX,$maxY);
			else
			  $im_out = imagecreate($maxX,$maxY);
			  
			// Need to image fill just in case image is transparent, don't always want black background
			$bgfill = imagecolorallocate( $im_out, $this->bg_red, $this->bg_green, $this->bg_blue );
			
			imagefill( $im_out, 0,0, $bgfill );
			if( function_exists("imagecopyresampled") )
			  ImageCopyResampled($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
			else
			  ImageCopyResized($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
		}
		else
		{
			if( function_exists("imagecreatetruecolor") )
			  $im_out = ImageCreateTrueColor($newxsize,$newysize);
			else
			  $im_out = imagecreate($newxsize,$newysize);
			  
			// Need to image fill just in case image is transparent, don't always want black background
			$bgfill = imagecolorallocate( $im_out, $this->bg_red, $this->bg_green, $this->bg_blue );
			
			if( function_exists("imagecopyresampled") )
			  ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
			else
			  ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
		}
		

		return $im_out;
	}
	
	/**
*  
*	private function - do not call
*
*/
	function NewImgSave($new_img,$fileout,$type)
	{
		/* Original code removed in favor of 'switch' statement
		if ($type=="png")
		{
			if (substr($fileout,strlen($fileout)-4,4)!=".png")
				$fileout .= ".png";
			 return imagepng($new_img,$fileout);
		}
		if ($type=="jpg")
		{
			if (substr($fileout,strlen($fileout)-4,4)!=".jpg")
				$fileout .= ".jpg";
			 return imagejpeg($new_img,$fileout);
		}
		*/
		switch($type)
		{
			case "gif":
				if( function_exists("imagegif") )
				{
					if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".gif")
						$fileout .= ".gif";
					return imagegif($new_img,$fileout);
					break;
				}
				else
					$this->NewImgSave( $new_img, $fileout, "jpg" );
			case "jpg":
				if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".jpg")
					$fileout .= ".jpg";
				return imagejpeg($new_img,$fileout);
				break;
			case "png":
				if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".png")
					$fileout .= ".png";
				return imagepng($new_img,$fileout);
				break;
		}
	}
	
	/**
*  
*	private function - do not call
*
*/
	function NewImgShow($new_img,$type)
	{
		/* Original code removed in favor of 'switch' statement
		if ($type=="png")
		{
			header ("Content-type: image/png");
			 return imagepng($new_img);
		}
		if ($type=="jpg")
		{
			header ("Content-type: image/jpeg");
			 return imagejpeg($new_img);
		}
		*/
		switch($type)
		{
			case "gif":
				if( function_exists("imagegif") )
				{
					header ("Content-type: image/gif");
					return imagegif($new_img);
					break;
				}
				else
					$this->NewImgShow( $new_img, "jpg" );
			case "jpg":
				header ("Content-type: image/jpeg");
				return imagejpeg($new_img);
				break;
			case "png":
				header ("Content-type: image/png");
				return imagepng($new_img);
				break;
		}
	}
	
	/**
*  
*	private function - do not call
*
*   1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF,
*   5 = PSD, 6 = BMP,
*   7 = TIFF(intel byte order),
*   8 = TIFF(motorola byte order),
*   9 = JPC, 10 = JP2, 11 = JPX,
*   12 = JB2, 13 = SWC, 14 = IFF
*/
	function GetImgType($filename)
	{
		$size = getimagesize($filename);
		/* Original code removed in favor of 'switch' statement
		if($size[2]==2)
			return "jpg";
		elseif($size[2]==3)
			return "png";
		*/
		switch($size[2])
		{
			case 1:
				return "gif";
				break;
			case 2:
				return "jpg";
				break;
			case 3:
				return "png";
				break;
		}
	}
	
}

?>
