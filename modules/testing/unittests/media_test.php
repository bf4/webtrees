<?php
	/*
	 * Tests for the Person class
	 */

	// this object contains all the different parameters we are testing
	class MediaTestParameters {
		var $use_media_firewall = false;
		var $media_firewall_thumbs = false;
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
			global $MEDIA_DIRECTORY, $MEDIA_DIRECTORY_LEVELS, $USE_MEDIA_FIREWALL, $MEDIA_FIREWALL_ROOTDIR;
			global $MEDIA_FIREWALL_THUMBS, $GEDCOM, $MEDIA_ID_PREFIX;
			// set global variables to known values
			$MEDIA_DIRECTORY_LEVELS = $testObj->directory_levels;
			$USE_MEDIA_FIREWALL = $testObj->use_media_firewall;
			$MEDIA_FIREWALL_THUMBS = $testObj->media_firewall_thumbs;

			// set local variables
			$serverPath = ($USE_MEDIA_FIREWALL) ? $MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY : $MEDIA_DIRECTORY;
			if (empty($testObj->thumbnail)) {
				if ($MEDIA_FIREWALL_THUMBS) {
					$thumbnailFile = $serverPath.'thumbs/'.$testObj->path.$testObj->file;
				} else {
					$thumbnailFile = $MEDIA_DIRECTORY.'thumbs/'.$testObj->path.$testObj->file;
				}
			} else {
				$thumbnailFile = $testObj->thumbnail;
			}

			if ($testObj->copyfile) {
				// copy image files into place
				$this->assertTrue(file_exists(TESTING_ROOT.'data/media/'.$testObj->file));
				$this->assertTrue(mkdirs($serverPath.$testObj->path));
				$this->assertTrue(copy(TESTING_ROOT.'data/media/'.$testObj->file, $serverPath.$testObj->path.$testObj->file));
			}
			
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
			$this->assertEqual($media->canDisplayDetails(), true);
			$this->assertEqual($media->getNote(), "This is a note\nmore of the same note");
			$this->assertEqual($media->getTitle(), 'Title Goes Here');
			$this->assertEqual($media->getName(), $media->getTitle());
			$this->assertEqual($media->getAddName(), "<br />Hebrew Title<br />\nRoman Title");
			$this->assertEqual($media->getAddTitle(), $media->getAddName());
			$this->assertEqual($media->getSortableName(), $media->getTitle());
			$this->assertEqual($media->getThumbnail(), $thumbnailFile);
			$this->assertEqual($media->getFilename(), '/ignorepath/'.$testObj->path.$testObj->file);
			// when files does not exist, getLocalFilename and getServerFilename should return whatever was passed in to them
			$this->assertEqual($media->getLocalFilename(), $MEDIA_DIRECTORY.$testObj->path.$testObj->file);
			$this->assertEqual($media->getServerFilename(), $serverPath.$testObj->path.$testObj->file);
			$this->assertEqual($media->fileExists(), $testObj->copyfile);
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
				if (file_exists($serverPath.$testObj->path.$testObj->file)) {
					$this->assertTrue(unlink($serverPath.$testObj->path.$testObj->file));
				}
				if (file_exists($thumbnailFile)) {
					$this->assertTrue(unlink($thumbnailFile));
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
		// test with AUTO_GENERATE_THUMBS on and off
		// test external media with MEDIA_EXTERNAL on and off

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

  		function testMedia2() {
			// gif, exists, directory levels 2, media firewall off
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

  		function testMedia3() {
			// jpg, exists, directory levels 1, media firewall on, media firewall thumbs off
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
	}
