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
}
