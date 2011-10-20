<?
system("cd torrent");
system("curl -L --cookie nada --data 'login_username=parapapa&login_password=qwerty&login=Вход' http://torrent.kg/forum/login.php?redirect=/forum/download.php?id={$argv[1]} > 1.torrent");
system("cd videos/16; ctorrent -e 0 ../../1.torrent");
system("php pusha_video.php");
system("rm -rf videos/16/*");
?>
