<? 
include "config.php";
require_once "lib/UTF.php";

class UTFTests extends PHPUnit_Framework_TestCase {
  public function test_detectEncoding() {
    $str = 'Строка кириллицы в UFT-8';
    $result = UTF::detectEncoding($str);
    $this->assertEquals("utf-8", $result);

    $str = base64_decode('0fLw7urgIOro8Ojr6+j2+yDiIHdpbmRvd3MtMTI1MQ==');
    $result = UTF::detectEncoding($str);
    $this->assertEquals("windows-1251", $result);
  }

    public function test_getUTF8String() {
    $str = 'Строка кириллицы в UFT-8';
    $result = UTF::getUTF8String($str);
    $this->assertEquals($str, $result);

    $str = base64_decode('0fLw7urgIOro8Ojr6+j2+yDiIHdpbmRvd3MtMTI1MQ==');
    $result = UTF::getUTF8String($str);
    $this->assertEquals("Строка кириллицы в windows-1251", $result);
  }
}
