<?php
  $url = trim($_POST['url']);
  if ($url) {
    $cookie_file = "/tmp/cookie_torrent.txt";
    $url = str_replace(array("http://torrent.kg", "http://www.torrent.kg"), array("",""), $url);
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, "http://torrent.kg/forum/login.php?redirect=$url");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s  
    curl_setopt($ch, CURLOPT_POST, 1); // set POST method  
    curl_setopt($ch, CURLOPT_POSTFIELDS, "login_username=parapapa&login_password=qwerty&login=Вход");  
    $result = curl_exec($ch); // run the whole process  
    curl_close($ch);   
  
    if (preg_match("/download\.php\?id=(\d*)/i", $result, $matches)) {
      echo "Putting the ticket #$matches[1] to the migration pipe<br>";
      $client= new GearmanClient();
      $client->addServer();
      var_dump($client->doBackground("migrate_torrent_to_kg", $matches[1]));
    } else {
      echo "failed to get the ticket number<br>";
    }
  }
?>

<form method=POST>
  <input type=text name=url>
  <input type=submit>
</form>
