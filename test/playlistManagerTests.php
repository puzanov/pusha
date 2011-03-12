<?php
require_once "test/config.php";
require_once "lib/PlaylistBuilder.php";
require_once 'HTTP/Request2.php';

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

class PlaylistManager {
  var $config;
  var $request;

  public function __construct() {
    $this->config = yaml_parse(file_get_contents("config.yml"));      
    $this->request = new HTTP_Request2($this->config["create_playlist_url"]);
    $this->request->setMethod(HTTP_Request2::METHOD_POST)
                  ->addPostParameter('username', $this->config["username"])
                  ->addPostParameter('password', $this->config["password"]);
  }
  
  public function createPlaylist($playlist) {
    $this->request->addPostParameter('playlist_name', $playlist->title)
                  ->addPostParameter('playlist_text', $playlist->info);
    if ($playlist->cover) $this->request->addUpload('cover', $playlist->cover);
    $json = $this->request->send()->getBody();    
    $result = json_decode($json);
    return $result;
  }
}
