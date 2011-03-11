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
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track3.MP3", $playlist->tracks[2]);
  }
  
  public function testGetInvalid_Dir() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/invalid_dir");
    $this->assertNotEquals(__DIR__ . "/music/invalid_dir/folder", $playlist->tracks[0]);
  }
  
  /**
   * @expectedException IncompletePlaylistException
   */
  public function testGetInvalid_Empty() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/empty");
  }
}

class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    $items = glob("{$pathToPlaylistFiles}/*");
    if (empty($items)) throw new IncompletePlaylistException();
    $playlist = new Playlist();
    $playlist->cover = $pathToPlaylistFiles . "/cover.jpg";
    if (!is_file($playlist->cover)) {
      $playlist->cover = NULL; 
    }
    $playlist->title = array_pop(explode("/", $pathToPlaylistFiles));
    $playlist->info = @file_get_contents($pathToPlaylistFiles . "/info.txt");
    $playlist->tracks = glob("{$pathToPlaylistFiles}/*.{mp3,MP3,Mp3,mP3}", GLOB_BRACE);
    return $playlist;
  }
}

class Playlist {
  var $cover;
  var $title;
  var $info;
  var $tracks;
}

class IncompletePlaylistException extends Exception {}
