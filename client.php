<?php
$client= new GearmanClient();
$client->addServer();
$client->doBackground("migrate_playlist_to_kz", $argv[1]);
?>
