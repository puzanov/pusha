<?
require_once 'HTTP/Request2.php';

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

  public function addMp3ToPlaylist($playlist_id, $file_id, $position = 0) {
    $request = new HTTP_Request2($this->config["add_mp3_to_playlist_url"]);
    $request->setMethod(HTTP_Request2::METHOD_POST)
                  ->addPostParameter('username', $this->config["username"])
                  ->addPostParameter('password', $this->config["password"])
                  ->addPostParameter('playlist_id', $playlist_id)
                  ->addPostParameter('position', $position)
                  ->addPostParameter('music_id',    $file_id);
    $json = $request->send()->getBody();                    
    $result = json_decode($json);
    return $result;
  }

  public function isNew($title) {
    $request = new HTTP_Request2($this->config["playlist_exists_url"]);
    $request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter('playlist_name', $title);
    $json = $request->send()->getBody();
    $result = json_decode($json);
    return $result;
  }
}
