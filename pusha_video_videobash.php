<?
// getting video from videobash.com

error_reporting(E_ALL ^ E_NOTICE);
include "lib/gootrans.php";
include "lib/nokogiri.php";

$gootrans = new gootrans();

$used_file = 'used.txt';
if (file_exists($used_file) == false) {
  file_put_contents($used_file, '');
  $used = array();
} else {
  $used = explode("\n", file_get_contents($used_file));
}

while (true) {
  $content = file_get_contents("http://videobash.com");
  $saw     = new nokogiri($content);
  $hrefs   = $saw->get("#topVideo li div div a")->toArray();

  foreach ($hrefs as $href) {
    if (empty($href['img'])) {
      continue;
    }

    $url           = $href['href'];
    $video_content = file_get_contents($url);
    $saw           = new nokogiri($video_content);
    $flashvars     = $saw->get("param[name=flashvars]")->toArray();
    
    $title     = $saw->get("h1.video-title")->toArray();
    $title     = preg_replace("/[^\ 0-9a-zA-Zа-яА-Я]/u", "", $title['#text']);
    $title     = $gootrans->translate($title, "en", "ru");

    if (in_array($title, $used)) {
      echo "$title -- we have this video\n";
      continue; // going to fetch another video
    } else {
      echo "video is new\n";
    }

    parse_str($flashvars['value'], $params);
    system("wget \"{$params['video_url']}\" -O \"videos/1/{$title}.flv\"");
    
    system("php pusha_video.php");
    $used[] = $title;
    file_put_contents($used_file, implode("\n", $used));
    system("rm -rf videos/1/*");
  }
  sleep(600);
}
