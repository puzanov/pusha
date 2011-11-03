<?
  // getting video from videobash.com

include "lib/nokogiri.php";

$used_file = 'used.txt';
if (file_exists($used_file) == false) {
  file_put_contents($used_file, '');
  $used = array();
} else {
  $used = explode("\n", file_get_contents($used_file));
}

$content = file_get_contents("http://videobash.com");
$saw = new nokogiri($content);
$hrefs = $saw->get("#topVideo li div div a")->toArray();
foreach ($hrefs as $href) {
  if (empty($href['img'])) {
    continue;
  }
  $url = $href['href'];
  $video_content = file_get_contents($url);
  $saw = new nokogiri($video_content);
  $flashvars = $saw->get("param[name=flashvars]")->toArray();
  $title = $saw->get("h1.video-title")->toArray();
  $title = preg_replace("/[^\ 0-9a-zA-Zа-яА-Я]/u", "", $title['#text']);
  if (isset($used[$title])) {
    echo "we have this video\n";
    continue;
  } else {
    echo "video is new\n";
  }
  $qs = $flashvars['value'];
  parse_str($qs, $params);
  $video_url = $params['video_url'];
  var_dump($video_url);
  var_dump($title);
  system("wget \"$video_url\" -O \"videos/1/{$title}.flv\"");
}
file_put_contents($used_file, implode("\n", $used));
system("php pusha_video.php");
system("rm -rf videos/1/*");
