<?php
include "test/config.php";

class PlaylistCreatorTests extends PHPUnit_Framework_TestCase { 
  public function testCreatePlaylistNoCover() {
    $config = yaml_parse(file_get_contents("config.yml"));      
    require_once 'HTTP/Request2.php';
    $request = new HTTP_Request2($config["create_playlist_url"]);
    $request->setMethod(HTTP_Request2::METHOD_POST)
        ->addPostParameter('username', $config["username"])
        ->addPostParameter('password', $config["password"])
        ->addPostParameter('playlist_name', "name")
        ->addPostParameter('playlist_text', "text");
        #->addUpload('avatar', './exploit.exe', 'me_and_my_cat.jpg', 'image/jpeg');
    $json = $request->send()->getBody();    
    $result = json_decode($json);
    $this->assertEquals("OK", $result->status);
  }
}
