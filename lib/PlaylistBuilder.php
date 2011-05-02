<?php
require_once __DIR__ . '/UTF.php';

class PlaylistBuilder {
  public function build($pathToPlaylistFiles) {
    $pathToPlaylistFilesEscaped = str_replace(array("]", "["), array('\]', '\['), $pathToPlaylistFiles);
    $items = glob("{$pathToPlaylistFilesEscaped}/*");

    if (empty($items)) {
      throw new IncompletePlaylistException("Empty directory");
    }

    $playlist = new Playlist();
    $playlist->cover = $this->getCover($pathToPlaylistFilesEscaped);

    if (!is_file($playlist->cover)) {
      $playlist->cover = NULL;
    }

    $playlist->title = array_pop(explode("/", $pathToPlaylistFiles));
    $playlist->info = UTF::getUTF8String(@file_get_contents($pathToPlaylistFiles . "/info.txt"));
    $playlist->tracks = glob("{$pathToPlaylistFilesEscaped}/*.{mp3,MP3,Mp3,mP3}", GLOB_BRACE);

    if (empty($playlist->tracks)) {
      throw new IncompletePlaylistException("No MP3 tracks");
    }

    return $playlist;
  }

  private function getCover($path) {
    $dh = dir($path);
    $images = array();

    // try to find cover
    while ($file = $dh->read()) {
      $filePath = $path . '/' . $file;
      $mime = explode('/', mime_content_type($filePath));


      if ($mime[0] == 'image') {
        $image = pathinfo($filePath);
        if ($image['filename'] == 'cover') {
          return $filePath;
        }

        $images[] = $image;
      }
    }

    // try to create cover
    foreach ($images as $image) {
      $imageInfo = $image;
      $imagePath = $imageInfo['dirname'] . '/' . $imageInfo['basename'];

      if (filesize($imagePath) > (300 * 1024)) {
        $destination = $imageInfo['dirname'] . '/' . 'cover.jpg';
        $this->resizeImage($imagePath, $destination, 150, 150);

        return $destination;
      } else {
        return $imagePath;
      }
    }
  }

  /**
   * Image resize.
   *
   * @param string $source      Source image path.
   * @param string $destination Destination image path.
   * @param int    $width       New image width.
   * @param int    $height      New image height.
   * @return void
   */
  private function resizeImage($source, $destination, $width, $height) {
    $size = getimagesize($source);
    $newHeight = $height;
    $newWidth = $width;

    if ($size[0] < $size[1]) {
      $newWidth = ($size[0] / $size[1]) * $height;
    } else {
      $newHeight = ($size[1] / $size[0]) * $width;
    }

    $newWidth = ($newWidth > $width) ? $width : $newWidth;
    $newHeight = ($newHeight > $height) ? $height : $newHeight;
    $image = @imagecreatetruecolor($newWidth, $newHeight);

    if ($size[2] == 2) {
      $imageSource = imagecreatefromjpeg($source);
    } else if ($size[2] == 3) {
      $imageSource = imagecreatefrompng($source);
    } else if ($size[2] == 1) {
      $imageSource = imagecreatefromgif($source);
    } else {
      die('unknown image format: ' . $source);
    }

    imagecopyresampled($image, $imageSource, 0, 0, 0, 0, $newWidth, $newHeight, $size[0], $size[1]);

    if ($size[2] == 2) {
      imagejpeg($image, $destination, 100);
    } else if ($size[2] == 1) {
      imagegif($image, $destination);
    } else if ($size[2] == 3) {
      imagepng($image, $destination);
    }
  }

}

class Playlist {
  var $cover;
  var $title;
  var $info;
  var $tracks;
}

class IncompletePlaylistException extends Exception {
}
