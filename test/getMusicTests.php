<?php
include "test/config.php";

class HolderTests extends PHPUnit_Framework_TestCase { 
  public function testGetValid() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/Metallica - Load - 1997");
    $this->assertEquals("Playlist", get_class($playlist));
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/cover.jpg", $playlist->cover);
    $this->assertEquals("Metallica - Load - 1997", $playlist->title);
    $this->assertEquals("Metallica Load Remastered", trim($playlist->info));
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track1.mp3", $playlist->tracks[0]);
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track2.mp3", $playlist->tracks[1]);
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track3.mp3", $playlist->tracks[2]);
  }
}

class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    $playlist = new Playlist();
    $playlist->cover = $pathToPlaylistFiles . "/cover.jpg";
    if (!is_file($playlist->cover)) {
      // raise ex 
    }
    $playlist->title = array_pop(explode("/", $pathToPlaylistFiles));
    $playlist->info = file_get_contents($pathToPlaylistFiles . "/info.txt");
    $playlist->tracks = glob("{$pathToPlaylistFiles}/*.mp3");
    return $playlist;
  }
}

class Playlist {
  var $cover;
}
