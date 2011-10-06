<?php

$json = file_get_contents("http://music.namba.kg/service.php?_dc=1317663733046&action=getUserPlaylists&login={$argv[1]}");
$p = json_decode($json);
preg_match_all("/playlist\/(\d*)/", $p->html, $matches);
$m = array_unique($matches[0]);
foreach ($m as $p) {
  $r = str_replace("playlist/", "", $p);
  if (!empty($r)) {
    $pp[] = $r;
    system("php client.php $r");
  }
}

?>
