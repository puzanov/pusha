<?
class UTF {
  protected static $encodingsList = array('utf-8', 'windows-1251');
  public static function getUTF8String($string) {
    $enc = self::detectEncoding($string);
    if ($enc=='windows-1251') {
	return iconv($enc, 'utf-8', $string);
    } else return $string;
  }

  public static function detectEncoding($string) {
        foreach (self::$encodingsList as $encoding) {
            $transcoded = iconv($encoding, $encoding, $string);
            if (md5($transcoded) == md5($string))
              return $encoding;
            }
     return null;
  }
}
