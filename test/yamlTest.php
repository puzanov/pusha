<? 
include "config.php";

class YamlTests extends PHPUnit_Framework_TestCase {
  public function testGet() {
    $yaml = yaml_parse(file_get_contents("config.yml"));
    $this->assertEquals("test", $yaml["test"]);
  }
}
