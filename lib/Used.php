<?
class Used {
  var $filename;
  var $used;

  function __construct($filename) {
    $this->filename = $filename;
    if (file_exists($filename) == false) {
      file_put_contents($filename, '');
      $this->used = array();
    } else {
      $this->used = explode("\n", file_get_contents($filename));
    }
  }

  function check($title) {
    if (in_array($title, $this->used)) {
      echo "$title -- we have this content\n";
      return true;
    } else {
      echo "$title -- this content is new\n";
      return false;
    }
  }

  function save($title) {
    $this->used[] = $title;
    file_put_contents($this->filename, implode("\n", $this->used));
  }
}


