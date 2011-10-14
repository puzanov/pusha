<?php

$worker= new GearmanWorker();
$worker->addServer();
$worker->addFunction("migrate_video_to_kz", "migrate_video_to_kz");
while ($worker->work());

function migrate_video_to_kz($job) {
  global $argv;
  if ($argv[1] == "kz") {
    $cat[16] = 18;
    $cat[1]  = 1;
    $cat[2]  = 2;
    $cat[22] = 22;
    $cat[3]  = 3;
    $cat[4]  = 4;
    $cat[21] = 23;
    $cat[13] = 13;
    $cat[9]  = 9;
    $cat[10] = 10;
    $cat[5]  = 5;
    $cat[25] = 26;
    $cat[6]  = 6;
    $cat[7]  = 7;
    $cat[8]  = 8;
  } else {
    $cat[18] = 16;
    $cat[1]  = 1;
    $cat[2]  = 2;
    $cat[22] = 22;
    $cat[3]  = 3;
    $cat[4]  = 4;
    $cat[23] = 21;
    $cat[13] = 13;
    $cat[9]  = 9;
    $cat[10] = 10;
    $cat[5]  = 5;
    $cat[26] = 25;
    $cat[6]  = 6;
    $cat[7]  = 7;
    $cat[8]  = 8;
  }

  system("rm -rf videos/*");
  $tld = "kg";
  if ($argv[1] == "kz") $tld = "kz";
  $json = file_get_contents("http://video.namba.$tld/json/?_=1317879687192&action=video&id={$job->workload()}");
  $video = json_decode($json);
  mkdir ("videos/{$video->video->category->id}");
  $video->video->title = preg_replace("/[^\ 0-9a-zA-Zа-яА-Я]/u", "", $video->video->title);
  system("wget \"{$video->video->download->flv}\" -O \"videos/{$video->video->category->id}/{$video->video->title}.flv\"");
  system("php pusha_video.php");
}
?>
