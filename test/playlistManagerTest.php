<?php
require_once "test/config.php";
require_once "lib/PlaylistBuilder.php";
require_once "lib/PlaylistManager.php";
require_once "lib/Uploader.php";

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
  
  public function testAddMp3ToPlaylist() {
    $playlistManager = new PlaylistManager();
    $playlistBuilder = new PlaylistBuilder();
    $playlist = $playlistBuilder->build(__DIR__ . "/music/Metallica - Load - 1997");
    $playlist_id = $playlistManager->createPlaylist($playlist)->playlist_id;
    $uploader = new Uploader();
    $file_id = $uploader->upload($playlist->tracks[0])->file_id;
    $result = $playlistManager->addMp3ToPlaylist($playlist_id, $file_id);
    $this->assertEquals("OK", $result->status);
  }
}
