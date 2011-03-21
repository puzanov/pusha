<? 
$config = yaml_parse(file_get_contents("config.yml"));
$d = dir($config["video_path"]);
while (false !== ($entry = $d->read())) {
  if ($entry == ".." || $entry == ".") continue;
  if (is_file($config['video_path']."/".$entry)) continue; 

}
