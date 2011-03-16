<?
class UTF {
  public static function getUTF8String($string) {
    $enc = self::detect_encoding($string);
    if ($enc=='windows-1251') {
	return iconv($enc, 'utf-8', $string);
    } else return $string;
  }

  protected static function detect_encoding($string) {
    static $list = array('utf-8', 'windows-1251');
        foreach ($list as $item) {
            $sample = iconv($item, $item, $string);
            if (md5($sample) == md5($string))
              return $item;
            }
     return null;
  }
}
