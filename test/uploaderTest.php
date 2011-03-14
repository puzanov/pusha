<? 
include "config.php";
include "lib/Uploader.php";

class UploaderTests extends PHPUnit_Framework_TestCase {
  public function testUpload() {
    $filepath = __DIR__ . "/music/Metallica - Load - 1997/track1.mp3";
    $uploader = new Uploader();    
    $result = $uploader->upload($filepath);
    $this->assertEquals("OK", $result->status);
    $this->assertGreaterThan(1, $result->file_id);
  }
}
