<? 
include "config.php";
require_once 'HTTP/Request2.php';
require_once "lib/HttpClient.php";
require_once "lib/CurlFileUploader.php";
require_once "lib/VideoUploader.php";

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

