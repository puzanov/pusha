<?
require_once __DIR__.'/UTF.php';

class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    $pathToPlaylistFilesEscaped = str_replace(array("]", "["), array('\]', '\['), $pathToPlaylistFiles);
    $items = glob("{$pathToPlaylistFilesEscaped}/*");
    if (empty($items)) throw new IncompletePlaylistException("Empty directory");
    $playlist = new Playlist();
    $covers = glob("{$pathToPlaylistFiles}/*.{jpg,jpeg,JPG,png,PNG}", GLOB_BRACE);
    $playlist->cover = $covers[0];
    if (filesize($playlist->cover) > 307200) {
      $playlist->cover = NULL;
    }
    if (!is_file($playlist->cover)) {
      $playlist->cover = NULL;
    }
    $playlist->title = array_pop(explode("/", $pathToPlaylistFiles));
    $playlist->info =  UTF::getUTF8String(@file_get_contents($pathToPlaylistFiles . "/info.txt"));
    $playlist->tracks = glob("{$pathToPlaylistFilesEscaped}/*.{mp3,MP3,Mp3,mP3}", GLOB_BRACE);
    if (empty($playlist->tracks)) throw new IncompletePlaylistException("No MP3 tracks");
    return $playlist;
  }
}

class Playlist {
  var $cover;
  var $title;
  var $info;
  var $tracks;
}

class IncompletePlaylistException extends Exception {}
