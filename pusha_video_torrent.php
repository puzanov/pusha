<?
$worker= new GearmanWorker();
$worker->addServer();
$worker->addFunction("migrate_torrent_to_kg", "migrate_torrent_to_kg");
while ($worker->work());

function migrate_torrent_to_kg($job) {
  system("curl -L --cookie nada --data 'login_username=parapapa&login_password=qwerty&login=Вход' http://torrent.kg/forum/login.php?redirect=/forum/download.php?id={$job->workload()} > 1.torrent");
  system("cd videos/2; ctorrent -e 0 ../../1.torrent");
  system("php pusha_video.php");
  system("rm -rf videos/2/*");
}
?>
