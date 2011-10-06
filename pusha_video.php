<? 
require_once "lib/VideoUploader.php";
require_once "lib/HttpClient.php";
require_once "lib/CurlFileUploader.php";

$config = yaml_parse(file_get_contents("config.yml"));
$d = dir($config["video_path"]);
while (false !== ($entry = $d->read())) {
  if ($entry == ".." || $entry == ".") continue;
  if (is_file($config['video_path']."/".$entry)) continue; 
  $category_id = $entry;
  $video_uploader = new VideoUploader();
  $dv = dir($config['video_path']."/".$entry);
  while (false !== ($video = $dv->read())) {
    if ($video == ".." || $video == ".") continue;
    if (is_dir($config['video_path']."/".$entry."/".$video)) continue;
    try {
      echo "uploading video ".$config['video_path']."/".$entry."/".$video." to category $category_id\n";
      $video_uploader->upload($config['video_path']."/".$entry."/".$video, $category_id);
      echo "gooood\n";
    } catch (Exception $e) {
      echo "failed :( ".$e->getMessage()."\n";
    } 
  }  
}
