<?
//example
//$tr = new Gootrans();
//echo $tr->translate("Impressive Russian Balalaika Playing", "en", "ru");

class Gootrans {
  var $user_agent = "Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10";  
  public function translate($text, $from, $to) {
    $text = urlencode($text);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://translate.google.com");
    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s
    curl_exec($ch); // run the whole process
    curl_setopt($ch, CURLOPT_URL, "http://translate.google.com/translate_a/t?client=t&text=$text&hl=ru&sl=$from&tl=$to");
    $result = explode(",", str_replace(array("[[[", "\""), "", curl_exec($ch)));
    curl_close($ch);
    return $result[0];
  }
}

?>
