<?
  // getting video from videobash.com
  
include "lib/nokogiri.php";
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
  $qs = $flashvars['value'];
  parse_str($qs, $params);
  $video_url = $params['video_url'];
  var_dump($video_url);
}
