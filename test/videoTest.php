<? 
include "config.php";
require_once 'HTTP/Request2.php';
require_once "lib/HttpClient.php";

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

    var_dump($ses);
    var_dump($uploadHost);

    $uploader = new CurlFileUploader(
        '/mnt/src/pusha/test/video/hack.flv',
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

class NambaVideoKGUploader {
  var $login;
  var $password;
  var $category_id;
  public function __construct($login, $password, $category_id) {
    $this->login = $login;
    $this->password = $password;
    $this->category_id = $category_id;
    $this->logger = Logger::getLogger(__CLASS__);
  }
  public function upload($filepath) {
    $client = new HttpClient('login.namba.kg');
    $client->cookie_host = 'namba.kg';
    $client->handle_redirects;
    $client->referer = 'http://video.namba.kg/add.php';
    $client->post('/login.php', array(
        'login' => $this->login,
        'password' => $this->password,
        'sub' => 'Войти'
    ));
    $content = $client->getContent();
    $resForSession = array();
    preg_match('/\<input name=\"ses\" type=\"hidden\" value=\"(.*)\"\/\>/', $content, $resForSession);
    $ses = $resForSession[1];
    $resForUploadhost = array();
    preg_match("/var uploadHost \= '(.*)'\;/", $content, $resForUploadhost);
    $uploadHost = $resForUploadhost[1];

    $title = explode('/', $filepath);

    $video_suffix = "____.avi";
    rename($filepath, $filepath.$video_suffix);

    $uploader = new CurlFileUploader(
        $filepath.$video_suffix,
        'http://'.$uploadHost.'/upload',
        'file',
        array(
            'ses'=>$ses,
            'l'=>$this->login,
            'video_service'=>1,
            'title'=>$title[sizeof($title)-1],
            'category_id'=>$this->category_id
        )
    );

    if ($uploader->UploadFile()) {
        $this->logger->info('The file was uploaded successfully');
        rename($filepath.$video_suffix, $filepath);
        return;
    }
    $this->logger->fatal('Cannot upload file '.$filepath.' to video.namba.kg');
  }
}

class CurlFileUploader {
  var $filePath;
  var $uploadURL;
  var $formFileVariableName;
  var $postParams = array();

  /* Constructor for CurlFileUploader
  * @param $filePath absolute path of file
  * @param $uploadURL url where you want to upload file
  * @param $formFileVariableName form field name to upload file
  * @param $otherParams assosiative array of other params which you want to send as post
  */
  function CurlFileUploader ($filePath, $uploadURL, $formFileVariableName, /* assosiative array */ $otherParams = false) {
    $this->filePath = $filePath;
    $this->uploadURL = $uploadURL;
    if(is_array($otherParams) && $otherParams != false) {
      foreach ($otherParams as $fieldName => $fieldValue) {
        $this->postParams[$fieldName] = $fieldValue;
      }
    }
    $this->postParams[$formFileVariableName] = "@".realpath($filePath);
  }

  /*
  * function to upload file
  * if unable to upload file produce error and exit
  * else upload file
  */
  function UploadFile () {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->uploadURL );
    curl_setopt($ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_HEADER, 1 );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Real-IP: '.$_SERVER['REMOTE_ADDR']));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    #curl_setopt($ch, CURLOPT_, 1);
    $postResult = curl_exec($ch);
    if (curl_errno($ch)) {
      return false;
    } else {
      curl_close($ch);
      return $postResult;
    }
  }
}
