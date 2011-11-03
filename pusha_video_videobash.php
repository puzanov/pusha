<?
  // getting video from videobash.com

include "lib/GoogleTranslate.php";
include "lib/nokogiri.php";

$tr = new Google_Translate_API();
echo $tr->translate("world", "en", "ru");
exit;

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
  $title = $title['#text'];
  var_dump($title);exit;
  $qs = $flashvars['value'];
  parse_str($qs, $params);
  $video_url = $params['video_url'];
  var_dump($video_url);
}
