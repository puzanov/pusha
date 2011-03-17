<? 
include "config.php";
require_once 'HTTP/Request2.php';
require_once "lib/HttpClient.php";
require_once "lib/CurlFileUploader.php";

class VideoTests extends PHPUnit_Framework_TestCase {
  public function testGet() {
    $config = yaml_parse(file_get_contents("config.yml"));    
    $client = new HttpClient('login.namba.test');
    $client->cookie_host = 'namba.test';
    $client->handle_redirects;
    $client->referer = 'http://video.namba.test/add.php';
    $client->post('/login.php', array(
        'login' => $config['username'],
        'password' => $config['password'],
        'sub' => 'Войти'
    ));
    
    $content = $client->getContent();
    $resForSession = array();
    preg_match('/\<input name=\"ses\" type=\"hidden\" value=\"(.*)\"\/\>/', $content, $resForSession);
    $ses = $resForSession[1];
    $resForUploadhost = array();
    preg_match("/var uploadHost \= '(.*)'\;/", $content, $resForUploadhost);
    $uploadHost = $resForUploadhost[1];

    $uploader = new CurlFileUploader(
      '/mnt/src/pusha/test/video/osel.flv',
      'http://'.$uploadHost.'/upload',
      'file',
      array(
          'ses'=>$ses,
          'l'=>$config['username'],
          'video_service'=>1,
          'title'=>'test video',
          'category_id'=>1
      )
    );

    $uploader->UploadFile();
  }
}
