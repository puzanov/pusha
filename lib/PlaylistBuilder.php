<?
class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    $items = glob("{$pathToPlaylistFiles}/*");
    if (empty($items)) throw new IncompletePlaylistException("Empty directory");
    $playlist = new Playlist();
    $playlist->cover = $pathToPlaylistFiles . "/cover.jpg";
    if (!is_file($playlist->cover)) {
      $playlist->cover = NULL;
    }
    $playlist->title = array_pop(explode("/", $pathToPlaylistFiles));
    $playlist->info = @file_get_contents($pathToPlaylistFiles . "/info.txt");
    $playlist->tracks = glob("{$pathToPlaylistFiles}/*.{mp3,MP3,Mp3,mP3}", GLOB_BRACE);
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
