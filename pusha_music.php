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
      try {
        $file_id = $uploader->upload($track)->file_id;
      } catch(Exception $e) {
        echo "Error uploading file ".$e->getMessage()."\n";
        continue;
      }  
      echo "File id is $file_id\n";
      if (empty($file_id)) {
        echo "Ups... Failed to upload file... Skip it\n";
        continue;
      }
      echo "Add this track to playlist\n";
      echo $manager->addMp3ToPlaylist($playlist_id, $file_id)->status."\n";
    }
  } catch (IncompletePlaylistException $e) {
    echo $e->getMessage()."\n";
  }  
}
