<?
// getting video from vimeo.com

error_reporting(E_ALL ^ E_NOTICE);
include "lib/nokogiri.php";
include "lib/Used.php";

$used = new Used('used.txt');

while (true) {
  $content = file_get_contents("http://vimeo.com");
  $saw     = new nokogiri($content);
  $hrefs   = $saw->get(".digest h3 a")->toArray();

  foreach ($hrefs as $href) {
    $title = preg_replace("/[^\ 0-9a-zA-Zа-яА-Я]/u", "", $href['#text']);
    
    if ($used->check($title)) continue;

    system("cclive -c -O \"videos/16/{$title}.mp4\" http://vimeo.com{$href['href']}"); 
    system("php pusha_video.php");

    $used->save($title);
    
    system("rm -rf videos/16/*");
  }
  sleep(600);
}

