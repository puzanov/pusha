<? 
require_once "lib/PlaylistManager.php";
require_once "lib/PlaylistBuilder.php";
require_once "lib/Uploader.php";

$config = yaml_parse(file_get_contents("config.yml"));
$d = dir($config["music_path"]);
while (false !== ($entry = $d->read())) {
  if ($entry == ".." || $entry == ".") continue;
  $builder = new PlaylistBuilder();
  $manager = new PlaylistManager();
  try {
    $dir = $config["music_path"]."/".$entry;
    echo "Working with {$dir}\n";
    $playlist = $builder->build($dir);
    $uploader = new Uploader();
    $mp3s = array();
    echo "Uploading tracks for {$playlist->title}\n";
    foreach ($playlist->tracks as $track) {
      echo "Uploading $track\n";
      $file_id = $uploader->upload($track)->file_id;
      $mp3s[] = $file_id;
      echo "File id is $file_id\n";
    }
    echo "Creating playlist {$playlist->title}\n";
    $playlist_id = $manager->createPlaylist($playlist)->playlist_id;
    echo "Playlist id is $playlist_id\n";
    echo "Adding tracks to playlist\n";
    foreach ($mp3s as $mp3) {
      echo $manager->addMp3ToPlaylist($playlist_id, $mp3)->status."\n";
    }  
  } catch (IncompletePlaylistException $e) {
    echo $e->getMessage()."\n";
  }  
}
