<?php
class Utils
{
	/**
	 *
	 * @param $path the absolute path
	 * @param $mode
	 * @return true/false
	 */
	public static function recursive_mkdir($path, $mode = 0777) {
		$dirs = explode(DIRECTORY_SEPARATOR , $path);
		$count = count($dirs);
		$path = '.';
		for ($i = 0; $i < $count; ++$i)
		{
			$path .= DIRECTORY_SEPARATOR . $dirs[$i];
			if (!is_dir($path) && !mkdir($path, $mode))
			{
				return false;
			}
		}
	  
		return true;
	}
	
	/**
	 * Generates an UUID
	 *
	 * @author     Anis uddin Ahmad <admin@ajaxray.com>
	 * @param      string  an optional prefix
	 * @return     string  the formatted uuid
	 */
	public static function uuid($prefix = '')
	{
		$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return $prefix . $uuid;
	}
	
	function timespan($date1,$date2)
	{
		$nms = abs($date2 - $date1);
		$diff = ' few seconds ';
		if($nms>60){
			$mins  = floor($nms/60);
			$diff = ' '.$mins.' minutes ';
		}
		if($nms>60*60){
			$hours  = floor($nms/60/60);
			$diff = ' '.$hours.' hours ';
		}
		if($nms>60*60*24){
			$days  = floor($nms/60/60/24);
			$diff = ' '.$days.' days ';
		}	
	
		return $diff;
	}
	
	public static function quoted($list)
	{
		$q = array();
		foreach ($list as $i)
		{
			$q[] = '\'' . $i . '\'';
		}		
		return $q;
	}
	
	public static function fileExt($filename)
	{
		$ts = explode('.', $filename); 
		$n = count($ts); 
		if ($n > 1) 
		{
			return $ts[$n - 1]; 
		}
		
		return null; 		
	}
	
	public function self_link() {
		return	'http'
		. ( (isset($_SERVER['https']) && $_SERVER['https'] == 'on') ? 's' : '' ) . '://'
		. $_SERVER['SERVER_NAME']
		. ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']) 
		. stripslashes($_SERVER['REQUEST_URI']);
	}
	
	public function http_link_base() {
        return  'http'
        . ( (isset($_SERVER['https']) && $_SERVER['https'] == 'on') ? 's' : '' ) . '://'
        . $_SERVER['SERVER_NAME']
        . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']); 
    }
}