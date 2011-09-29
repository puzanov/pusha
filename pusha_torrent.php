<?
system("cd torrent");
system("curl -L --cookie nada --data 'login_username={$argv[1]}&login_password={$argv[2]}&login=Вход' http://torrent.kg/forum/login.php?redirect=/forum/download.php?id={$argv[3]} > 1.torrent");
system("cd torrent; ctorrent -e 0 ../1.torrent");
system("php pusha_music.php");
system("rm -rf torrent/*");
?>
