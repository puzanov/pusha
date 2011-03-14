<?php
require_once "test/config.php";
require_once "lib/PlaylistBuilder.php";
require_once "lib/PlaylistManager.php";

class PlaylistManagerTests extends PHPUnit_Framework_TestCase { 
  public function testCreatePlaylistNoCover() {
    $playlistManager = new PlaylistManager();
    $playlist = new Playlist();
    $playlist->cover = NULL;
    $playlist->title = "title";
    $playlist->info = "info";
    $result = $playlistManager->createPlaylist($playlist);
    $this->assertEquals("OK", $result->status);
  }
  
  public function testCreatePlaylistWithCover() {
    $playlistManager = new PlaylistManager();
    $playlist = new Playlist();
    $playlist->cover = __DIR__. "/music/Metallica - Load - 1997/cover.jpg";
    $playlist->title = "title";
    $playlist->info = "info";
    $result = $playlistManager->createPlaylist($playlist);
    $this->assertEquals("OK", $result->status);
  }
}
