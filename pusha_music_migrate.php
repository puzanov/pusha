<?php

require_once 'HTTP/Request2.php';

$config = yaml_parse(file_get_contents("config.yml"));
$request = new HTTP_Request2($config["get_playlist_url"]);
$request->setMethod(HTTP_Request2::METHOD_GET);
$url = $request->getUrl();
$url->setQueryVariables(array(
  'id'  => $argv[1],
  'q3h' => 1
));
$json = $request->send()->getBody();
$playlist = json_decode($json);
var_dump($playlist);
if (empty($playlist)) {
  echo "Failed to get platylist\n";
  exit;
}
system("rm -rf from_kg/*");
mkdir("from_kg/{$playlist->name}");
if ($playlist->cover > 0) {
  system("cd \"from_kg/{$playlist->name}\"; wget http://download.files.namba.kg/files/{$playlist->cover}/cover.jpg");
}  
foreach ($playlist->files as $file) {
  system("wget \"http://download.files.namba.kg/files/{$file->id}/{$file->filename}?{$file->key}\" -O \"from_kg/{$playlist->name}/{$file->filename}\"");
}
system("php pusha_music.php");
?>
