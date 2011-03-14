<? 
include "config.php";
require_once 'HTTP/Request2.php';

class UploaderTests extends PHPUnit_Framework_TestCase {
  public function testUpload() {
    $filepath = __DIR__ . "/music/Metallica - Load - 1997/track1.mp3";
    $uploader = new Uploader();    
    $result = $uploader->upload($filepath);
    $this->assertEquals("OK", $result->status);
    $this->assertGreaterThan(1, $result->file_id);
  }
}

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


