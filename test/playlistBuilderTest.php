<?php
require_once "test/config.php";
require_once "lib/PlaylistBuilder.php";

class PlaylistBuilderTests extends PHPUnit_Framework_TestCase { 
  public function testGetValid1() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/Metallica - Load - 1997");
    $this->assertEquals("Playlist", get_class($playlist));
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/cover.jpg", $playlist->cover);
    $this->assertEquals("Metallica - Load - 1997", $playlist->title);
    $this->assertEquals("Metallica Load Remastered", trim($playlist->info));
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track1.mp3", $playlist->tracks[0]);
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track2.mp3", $playlist->tracks[1]);
    $this->assertEquals(__DIR__ . "/music/Metallica - Load - 1997/track3.MP3", $playlist->tracks[2]);
  }

  public function testGetValid2() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/30 Seconds To Mars [2002] Songs From 30 Seconds To Mars");
    $this->assertEquals("Playlist", get_class($playlist));
    $this->assertEquals("30 Seconds To Mars [2002] Songs From 30 Seconds To Mars", $playlist->title);
    $this->assertEquals(__DIR__ . "/music/30 Seconds To Mars [2002] Songs From 30 Seconds To Mars/track1.mp3", $playlist->tracks[0]);
    $this->assertEquals(__DIR__ . "/music/30 Seconds To Mars [2002] Songs From 30 Seconds To Mars/track2.mp3", $playlist->tracks[1]);
    $this->assertEquals(__DIR__ . "/music/30 Seconds To Mars [2002] Songs From 30 Seconds To Mars/track3.MP3", $playlist->tracks[2]);
  }


  /**
   * @expectedException IncompletePlaylistException
   */
  public function testGetInvalid_Dir() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/invalid_dir");
  }
  
  /**
   * @expectedException IncompletePlaylistException
   */
  public function testGetInvalid_EmptyDir() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/empty");
  }
  
  /**
   * @expectedException IncompletePlaylistException
   */
  public function testGetInvalid_NoTracks() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/notracks");
  }

  /**
   * @expectedException IncompletePlaylistException
   */
  public function testGetValid_dotMp3File() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/dot");
    $this->assertEquals(__DIR__ . "/music/dot/.track.mp3", $playlist->tracks[0]);
  }

  public function testGetValid_cyrillicInPlaylistInfo() {
    $builder = new PlaylistBuilder();
    $playlist = $builder->build(__DIR__ . "/music/По-русски");
    $this->assertTrue( strpos($playlist->info, 'Альбом с описанием на кириллице')!=-1 );
    $this->assertEquals(__DIR__ . "/music/По-русски/Песня1.mp3", $playlist->tracks[0]);
  }
}
