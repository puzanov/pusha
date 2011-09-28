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
    echo "Is playlist exists?\n";
    if ($manager->isNew($playlist->title)->status) {
      echo "We have this playlist already. Skipping...\n";
      continue;
    } else {
      echo "Playlist is new\n";
      echo "Creating playlist {$playlist->title}\n";
      $result = $manager->createPlaylist($playlist);
      if ($result == NULL || $result->status == "ERR") {
        echo "Cannot create playlist\n";
        continue;
      }
      if ($result->status == "ERR") {
        echo "Cannot create playlist\n";
        continue;
      }
      $playlist_id = $result->playlist_id;
      echo "Playlist id is $playlist_id\n";
    }
    echo "Uploading tracks for {$playlist->title}\n";
    foreach ($playlist->tracks as $track) {
      echo "Uploading $track\n";
      $file_id = $uploader->upload($track)->file_id;
      $mp3s[] = $file_id;
      echo "File id is $file_id\n";
    }
    echo "Adding tracks to playlist\n";
    foreach ($mp3s as $mp3) {
      if (empty($mp3)) {continue;}
      echo $manager->addMp3ToPlaylist($playlist_id, $mp3)->status."\n";
    }  
  } catch (IncompletePlaylistException $e) {
    echo $e->getMessage()."\n";
  }  
}
