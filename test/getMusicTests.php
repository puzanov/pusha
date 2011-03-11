<?php
include "test/config.php";

class HolderTests extends PHPUnit_Framework_TestCase { 
  public function testGetValid() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/Metallica - Load - 1997");
    $this->assertEquals("Playlist", get_class($playlist));
    $this->assertEquals("/mnt/src/pusha/test/music/Metallica - Load - 1997/cover.jpg", $playlist->cover);
    $this->assertEquals("Metallica - Load - 1997", $playlist->title);
    $this->assertEquals("Metallica Load Remastered", trim($playlist->info));
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

    return $playlist;
  }
}

class Playlist {
  var $cover;
}
