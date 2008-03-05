<?php
	/*
	 * Tests for the Media class
	 */

	// this object contains all the different parameters we are testing
	class MediaTestParameters {
		var $use_media_firewall = false;
		var $media_firewall_thumbs = false;
		var $auto_generate_thumbs = true;
		var $directory_levels = 0;
		var $path = '';
		var $filetype = '';
		var $copyfile = false;
		var $mimetype = 'application/octet-stream';
		var $filesize = 0;
		var $filesizeraw = -1;
		var $width = 0;
		var $height = 0;
		var $thumbnail = '';
	}
	
	// this object performs the tests based on the parameters that were passed in
	class MediaTestCase extends UnitTestCase {
		function doMediaObject($testObj) {
			global $MULTI_MEDIA, $MEDIA_DIRECTORY, $MEDIA_DIRECTORY_LEVELS, $USE_MEDIA_FIREWALL, $MEDIA_FIREWALL_ROOTDIR;
			global $AUTO_GENERATE_THUMBS, $MEDIA_FIREWALL_THUMBS, $GEDCOM, $MEDIA_ID_PREFIX;
			// set global variables to known values
			$MULTI_MEDIA = true;
			$AUTO_GENERATE_THUMBS = $testObj->auto_generate_thumbs;
			$MEDIA_DIRECTORY_LEVELS = $testObj->directory_levels;
			$USE_MEDIA_FIREWALL = $testObj->use_media_firewall;
			$MEDIA_FIREWALL_THUMBS = $testObj->media_firewall_thumbs;

			if ( $USE_MEDIA_FIREWALL && (strpos($MEDIA_DIRECTORY,"../") !== false ) ) {
				$this->assertTrue(0, "Can't test Media Firewall, MEDIA_DIRECTORY has ../");
				return;
			}

			// set local variables
			$server_media_directory = ($USE_MEDIA_FIREWALL) ? $MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY : $MEDIA_DIRECTORY;
			if (empty($testObj->thumbnail)) {
				// dynamically determine what the thumbnail is
				$thumbnail = $MEDIA_DIRECTORY.'thumbs/'.$testObj->path.$testObj->file;
				if ($MEDIA_FIREWALL_THUMBS) {
					// if media_firewall_thumbs is true and the main media item is in the media_firewall_rootdir, then thumbnail is stored in the media_firewall_rootdir too
					$server_thumbnail = $server_media_directory.'thumbs/'.$testObj->path.$testObj->file;
				} else {
					// if media_firewall_thumbs is false, the thumbnail is stored in the main media directory
					$server_thumbnail = $thumbnail;
				}
			} else {
				// a specific thumbnail was passed in, it should be something in the images directory, not the media directory
				$thumbnail = $testObj->thumbnail;
				$server_thumbnail = $thumbnail;
				$this->assertTrue(strpos($thumbnail,"images") === 0);
			}

			if ($testObj->copyfile) {
				// copy image files into place
				$this->assertTrue(file_exists(TESTING_ROOT.'data/media/'.$testObj->file));
				$this->assertTrue(mkdirs($server_media_directory.$testObj->path));
				$this->assertTrue(copy(TESTING_ROOT.'data/media/'.$testObj->file, $server_media_directory.$testObj->path.$testObj->file));
				$this->assertFalse(empty($server_thumbnail));
			} else {
				// the image file should not exist if we did not copy it there for this test
				$this->assertFalse(file_exists($server_media_directory.$testObj->path.$testObj->file));
			}
			if ( (!empty($server_thumbnail)) && (strpos($server_thumbnail,"images") !== 0) ) {
				// make sure the server_thumbnail does not already exist (unless it is in the images directory)
				$this->assertFalse(file_exists($server_thumbnail));
			}
			
			// pick a random MID
			$mid = $MEDIA_ID_PREFIX.'123456';
			$mediaGedcom = '0 @'.$mid.'@ OBJE
2 FORM JPEG
3 TYPE photo
1 TITL Title Goes Here
2 _HEB Hebrew Title
2 ROMN Roman Title
1 NOTE This is a note
2 CONT more of the same note
1 FILE /ignorepath/'.$testObj->path.$testObj->file;
			$media = new Media($mediaGedcom);
			$this->assertTrue($media->canDisplayDetails());
			$this->assertEqual($media->getNote(), "This is a note\nmore of the same note");
			$this->assertEqual($media->getTitle(), 'Title Goes Here');
			$this->assertEqual($media->getName(), $media->getTitle());
			$this->assertEqual($media->getAddName(), "<br />Hebrew Title<br />\nRoman Title");
			$this->assertEqual($media->getAddTitle(), $media->getAddName());
			$this->assertEqual($media->getSortableName(), $media->getTitle());
			// NOTE: getThumbnail() returns the standard media path, not the protected media path
			$this->assertEqual($media->getThumbnail(), $thumbnail);
			// NOTE: getFilename() returns the complete "1 FILE" line, which is not necessarily the standard or protected media path
			$this->assertEqual($media->getFilename(), '/ignorepath/'.$testObj->path.$testObj->file);
			// when file does not exist, getLocalFilename() and getServerFilename() should return whatever was passed in to them
			$this->assertEqual($media->getLocalFilename(), $MEDIA_DIRECTORY.$testObj->path.$testObj->file);
			$this->assertEqual($media->getServerFilename(), $server_media_directory.$testObj->path.$testObj->file);
			// if we copied the file there, it should exist.  otherwise, it should not
			$this->assertEqual($media->fileExists(), $testObj->copyfile);
			if (!empty($server_thumbnail)) {
				if (strpos($server_thumbnail,"images") !== 0) {
					// the thumbnail is not in the images directory.  if we copied the file in, and auto_generate_thumbs is true, then the thumbnail should now exist. 
					$this->assertEqual(file_exists($server_thumbnail), ($testObj->copyfile && $AUTO_GENERATE_THUMBS) );
				} else {
					// the thumbnail is in the images directory, it should exist
					$this->assertEqual(file_exists($server_thumbnail), true);
				}
			}
			$this->assertEqual($media->getFilesize(), $testObj->filesize);
			$this->assertEqual($media->getFilesizeraw(), $testObj->filesizeraw);
			$this->assertEqual($media->getMediatype(), 'photo');
			$this->assertEqual($media->getFiletype(), $testObj->filetype);
			$this->assertEqual($media->getMimetype(), $testObj->mimetype);
			$this->assertEqual($media->getWidth(), $testObj->width);
			$this->assertEqual($media->getHeight(), $testObj->height);
			$this->assertEqual($media->getLinkUrl(), 'mediaviewer.php?mid='.$mid.'&amp;ged='.$GEDCOM);
			// $this->assertEqual($media->setFileProperties(), '');
			// $this->assertEqual($media->in_obje_list(), '');
			// $this->assertEqual($media->equals(), '');
			
			if ($testObj->copyfile) {
				// remove the files that we initially copied
				if (file_exists($server_media_directory.$testObj->path.$testObj->file)) {
					$this->assertTrue(unlink($server_media_directory.$testObj->path.$testObj->file));
				}
				if ( (!empty($server_thumbnail)) && (strpos($server_thumbnail,"images") !== 0) && (file_exists($server_thumbnail)) ) {
					// only delete the thumbnail if it is not in the images directory
					$this->assertTrue(unlink($server_thumbnail));
				}
				// unfortunately, this still leaves some extra directories on the system
			}

		}
	}

	class TestOfMedia extends MediaTestCase {
		// variations to test:
		// test different file types: jpg, gif, png, pdf
		// test with files that exist and files that don't
		// test with $USE_MEDIA_FIREWALL on and off
		// test with $MEDIA_FIREWALL_THUMBS on and off
		// test with different values for $MEDIA_DIRECTORY_LEVELS
		
		// still to do:
		// test external media with MEDIA_EXTERNAL on and off
		// test RESN

		// Notes for writing test cases:
		// * Do not test with files from your own gedcom, only test with the files in the /modules/testing/data/media/ directory (the files are deleted at the end of the test)
		// * If copyfile or xxx is false, you have to specify the default thumbnail or the test will fail
		// * If you specify a default thumbnail, it must be from the images directory, not the media directory
		// * All paths and filenames should start with "__" so they can be kept separate from real data
		
  		function testMedia1() {
			// jpg, does not exist
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->directory_levels = 2;
			$test->path = '__testpath1/__testpath2/';
			$test->file = '__testfile.jpg';
			$test->filetype = 'JPG';
			$test->copyfile = false;
			$test->thumbnail = 'images/media.gif';
			$this->doMediaObject($test);
		}

  		function testMedia2a() {
			// gif, exists, directory levels 2, media firewall off, auto generate thumbs on
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->directory_levels = 2;
			$test->path = '__testpath1/__testpath2/';
			$test->file = '__testfile.gif';
			$test->filetype = 'GIF';
			$test->copyfile = true;
			$test->mimetype = 'image/gif';
			$test->filesize = 6.44;
			$test->filesizeraw = 6591;
			$test->width = 300;
			$test->height = 68;
			$this->doMediaObject($test);
		}

  		function testMedia2b() {
			// gif, exists, directory levels 2, media firewall off, auto generate thumbs off
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->auto_generate_thumbs = false;
			$test->directory_levels = 2;
			$test->path = '__testpath1/__testpath2/';
			$test->file = '__testfile.gif';
			$test->filetype = 'GIF';
			$test->copyfile = true;
			$test->mimetype = 'image/gif';
			$test->filesize = 6.44;
			$test->filesizeraw = 6591;
			$test->width = 300;
			$test->height = 68;
			$test->thumbnail = 'images/media.gif';
			$this->doMediaObject($test);
		}

  		function testMedia3a() {
			// jpg, exists, directory levels 1, media firewall on, media firewall thumbs off, auto generate thumbs on
			$test = new MediaTestParameters();
			$test->use_media_firewall = true;
			$test->media_firewall_thumbs = false;
			$test->directory_levels = 1;
			$test->path = '__testpath1/';
			$test->file = '__testfile.jpg';
			$test->filetype = 'JPG';
			$test->copyfile = true;
			$test->mimetype = 'image/jpeg';
			$test->filesize = 5.98;
			$test->filesizeraw = 6123;
			$test->width = 250;
			$test->height = 57;
			$this->doMediaObject($test);
		}	

  		function testMedia3b() {
			// jpg, exists, directory levels 1, media firewall on, media firewall thumbs off, auto generate thumbs off
			$test = new MediaTestParameters();
			$test->use_media_firewall = true;
			$test->media_firewall_thumbs = false;
			$test->auto_generate_thumbs = false;
			$test->directory_levels = 1;
			$test->path = '__testpath1/';
			$test->file = '__testfile.jpg';
			$test->filetype = 'JPG';
			$test->copyfile = true;
			$test->mimetype = 'image/jpeg';
			$test->filesize = 5.98;
			$test->filesizeraw = 6123;
			$test->width = 250;
			$test->height = 57;
			$test->thumbnail = 'images/media.gif';
			$this->doMediaObject($test);
		}	

  		function testMedia4() {
			// jpg, exists, directory levels 1, media firewall on, media firewall thumbs on
			$test = new MediaTestParameters();
			$test->use_media_firewall = true;
			$test->media_firewall_thumbs = true; // this shows an error with thumbnail_file - it always returns the standard media directory!  need to fix
			$test->directory_levels = 1;
			$test->path = '__testpath1/';
			$test->file = '__testfile.jpg';
			$test->filetype = 'JPG';
			$test->copyfile = true;
			$test->mimetype = 'image/jpeg';
			$test->filesize = 5.98;
			$test->filesizeraw = 6123;
			$test->width = 250;
			$test->height = 57;
			$this->doMediaObject($test);
		}	

  		function testMedia5() {
			// png, exists, directory levels 0, media firewall off
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->directory_levels = 0;
			$test->path = '';
			$test->file = '__testfile.png';
			$test->filetype = 'PNG';
			$test->copyfile = true;
			$test->mimetype = 'image/png';
			$test->filesize = 14.58;
			$test->filesizeraw = 14928;
			$test->width = 380;
			$test->height = 86;
			$this->doMediaObject($test);
		}	

  		function testMedia6() {
			// pdf, doesn't exist
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->directory_levels = 0;
			$test->path = '';
			$test->file = '__testfile.pdf';
			$test->filetype = 'PDF';
			$test->copyfile = false;
			$test->thumbnail = 'images/media/pdf.gif';
			$test->mimetype = 'application/pdf';
			$this->doMediaObject($test);
		}

  		function testMedia7() {
			// doc, doesn't exist
			$test = new MediaTestParameters();
			$test->use_media_firewall = false;
			$test->directory_levels = 1;
			$test->path = '__testpath1/';
			$test->file = '__testfile.doc';
			$test->filetype = 'DOC';
			$test->copyfile = false;
			$test->thumbnail = 'images/media/doc.gif';
			$test->mimetype = 'application/msword';
			$this->doMediaObject($test);
		}

	}
