<?php
$client= new GearmanClient();
$client->addServer();
$client->doBackground("migrate_video_to_kz", $argv[1]);
?>

