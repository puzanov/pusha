<?php
include "test/config.php";

class HolderTests extends PHPUnit_Framework_TestCase { 
  public function testGetCover() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build("/path/to/playlist");
    $this->assertEquals("Playlist", get_class($playlist));
  }
}

class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    return new Playlist(); 
  }
}

class Playlist {

}
