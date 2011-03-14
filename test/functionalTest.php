<? 
require_once "config.php";
require_once "lib/PlaylistManager.php";
require_once "lib/PlaylistBuilder.php";
require_once "lib/Uploader.php";

class FunctionalTests extends PHPUnit_Framework_TestCase {
  public function testScenario() {
    $config = yaml_parse(file_get_contents("config.yml"));
    $d = dir($config["music_path"]);
    while (false !== ($entry = $d->read())) {
      if ($entry == ".." || $entry == ".") continue;
      $builder = new PlaylistBuilder();
      try {
        $playlist = $builder->build($config["music_path"]."/".$entry);
        $uploader = new Uploader();
        $mp3s = array();
        foreach ($playlist->tracks as $track) {
          $mp3s[] = $uploader->upload($track)->file_id;
        }
        
      } catch (IncompletePlaylistException $e) {
        echo $e."\n";
      }  
    }
  }
}
