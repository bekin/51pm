<?php
class LocaleManager 
{
	const LOCAL_COOKIE_NAME = '__phoenix_locale__'; 
	public static $dlftLocale = 'zh_cn'; 
	
	public static function getCurrentLocale()
	{
		// TO DO,
		// the logic is: 
		// 1. does it have a locale parameter in request, if so, get it, set cookie
		// 2. if no, try get from cookie
		// 3. no cookie, return default
		return Yii::app()->language;
		//return self::$dlftLocale;		
	}

	public static function isChinese() 
	{
		return self::getCurrentLocale() == 'zh_cn';		
	}

	public static function isEnglish() 
	{
		return self::getCurrentLocale() == 'en_us';
	}
	
}