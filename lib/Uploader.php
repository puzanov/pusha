<?
require_once 'HTTP/Request2.php';

class Uploader {
  public function upload($filepath) {
    $this->config = yaml_parse(file_get_contents("config.yml"));
    $this->request = new HTTP_Request2($this->config["upload_file_url"]);
    $this->request->setMethod(HTTP_Request2::METHOD_POST)
                  ->addPostParameter('username', $this->config["username"])
                  ->addPostParameter('password', $this->config["password"])
                  ->addUpload('file', $filepath);
    $json = $this->request->send()->getBody();
    $result = json_decode($json);
    return $result;
  }
}
