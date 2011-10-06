<?
class VideoUploader {
  var $config = array();

  public function __construct($config = null) {
    if (!$config) {
      $this->config = yaml_parse(file_get_contents("config.yml"));
    } else {
      $this->config = $config;
    }
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
    $client->referer = $this->config['json_data_url'];
    $client->post('/login.php', array(
        'login' => $this->config['username'],
        'password' => $this->config['password'],
        'sub' => 'Войти'
    ));

    $data = json_decode($client->getContent(), true);

    if (isset($data['success']) && $data['success'] === false) {
      throw new AuthError();
    }

    $uploader = new CurlFileUploader(
      $video_path,
      $data['upload_host'],
      'file',
      array(
        'ses'=>$data['ses'],
        'l'=>$this->config['username'],
        'video_service'=>1,
        'title'=>$this->getTitle($video_path),
        'category_id'=>$category_id
      )
    );

    $upload_result = $uploader->UploadFile();
    $matches = array();
    if (!preg_match("/X-Namba-FileId: ([0-9]+)/", $upload_result, $matches)) {
      throw new VideoUploadFailed();
    }

    return (int) $matches[1];
  }
}
class AuthError extends Exception{}
class VideoUploadFailed extends Exception{}
?>
