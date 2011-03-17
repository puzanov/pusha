<? 
include "config.php";
require_once 'HTTP/Request2.php';
require_once "lib/HttpClient.php";
require_once "lib/CurlFileUploader.php";

class VideoTests extends PHPUnit_Framework_TestCase {
  public function testUploadVideo() {
    $video_uploader = new VideoUploader();
    $video_uploader->upload('/mnt/src/pusha/test/video/osel.flv', 1);
  }

  public function testGetTitle() {
    $video_uploader = new VideoUploader();
    $title = $video_uploader->getTitle('/mnt/src/pusha/test/video/osel.flv');
    $this->assertEquals("osel", $title);
    $title = $video_uploader->getTitle('/mnt/src/pusha/test/video/pidaraz.osel.flv');
    $this->assertEquals("pidaraz.osel", $title);
    $title = $video_uploader->getTitle('/mnt/src/pusha/test/video/Терминатор 4 - полная версия.HD720p.DVDRIP.mpeg');
    $this->assertEquals("Терминатор 4 - полная версия.HD720p.DVDRIP", $title);
    $title = $video_uploader->getTitle('/mnt/src/pusha/test/video/Файл без расширения');
    $this->assertEquals("Файл без расширения", $title);
  }
}

class VideoUploader {
  var $config = array();
  
  public function __construct() {
    $this->config = yaml_parse(file_get_contents("config.yml"));    
  }
  
  public function getTitle($video_path) {
    $dir_chunks = explode("/", $video_path);
    $filename = $dir_chunks[count($dir_chunks)-1];
    $dotted_title = explode(".", $filename);
    if (count($dotted_title) == 2) return $dotted_title[0];
    if (count($dotted_title) > 2) {
      array_pop($dotted_title);
      return implode(".", $dotted_title);
    }
    return $dotted_title[0];
  }
  
  public function upload($video_path, $category_id) {
    $client = new HttpClient($this->config['login_host']);
    $client->handle_redirects;
    $client->referer = $this->config['add_video_url'];
    $client->post('/login.php', array(
        'login' => $this->config['username'],
        'password' => $this->config['password'],
        'sub' => 'Войти'
    ));
    
    $content = $client->getContent();
    $resForSession = array();
    preg_match('/\<input name=\"ses\" type=\"hidden\" value=\"(.*)\"\/\>/', $content, $resForSession);
    $ses = $resForSession[1];
    
    if ($ses == NULL) {
      throw new AuthError();
    }
    
    $resForUploadhost = array();
    preg_match("/var uploadHost \= '(.*)'\;/", $content, $resForUploadhost);
    $uploadHost = $resForUploadhost[1];

    $uploader = new CurlFileUploader(
      $video_path,
      'http://'.$uploadHost.'/upload',
      'file',
      array(
        'ses'=>$ses,
        'l'=>$this->config['username'],
        'video_service'=>1,
        'title'=>$this->getTitle($video_path),
        'category_id'=>$category_id
      )
    );

    $upload_result = $uploader->UploadFile();
    if (!preg_match("/X-Namba-FileId:/", $upload_result)) {
      throw new VideoUploadFailed();
    }
  }
}

class AuthError extends Exception {}
class VideoUploadFailed extends Exception {}
