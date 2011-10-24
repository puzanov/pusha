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
  $cd = $config['video_path']."/".$entry;
  $flvs = glob("{{$cd}/*.*,{$cd}/*/*.*}",GLOB_BRACE);
  foreach ($flvs as $video) {
    try {
      echo "uploading video $video to category $category_id\n";
      $video_uploader->upload($video, $category_id);
      echo "gooood\n";
    } catch (Exception $e) {
      echo "failed :( ".$e->getMessage()."\n";
    } 
  }  
}
