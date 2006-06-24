<?php
$modinfo = parse_ini_file('modules/gallery2.php', true);
if(!isset($modinfo['Gallery2']['path'])){$modinfo['Gallery2']['path'] = 'modules/gallery2';}
if(file_exists("{$modinfo['Gallery2']['path']}/embed.php"))
{
	include_once "{$modinfo['Gallery2']['path']}/embed.php";
	require_once 'modules/gallery2/G2EmbedDiscoveryUtilities.class';
	require_once("{$modinfo['Gallery2']['path']}/modules/core/classes/GalleryCoreApi.class");
	require_once("{$modinfo['Gallery2']['path']}/modules/core/classes/GallerySession.class");
}

class mod_gallery2_updateuser
{
	function hook($user)
	{
		global $language_settings, $SERVER_URL, $modinfo;

		if(!class_exists('GalleryEmbed')){return null;}

		$ret = GalleryEmbed::init(array(
			'embedUri'			=> 'index.php?mod=gallery2',
			'g2Uri'				=> G2EmbedDiscoveryUtilities::normalizeG2Uri($modinfo['Gallery2']['path']),
			'loginRedirect'		=> "{$SERVER_URL}login.php",
			'apiVersion'		=> array(1, 1)
		));

		$ret = GalleryEmbed::updateUser($user['username'], array(
			'username'			=> $user['username'],
			'email'				=> $user['email'],
			'fullname'			=> "{$user['firstname']} {$user['lastname']}",
			'language'			=> $language_settings[$user['language']]['lang_short_cut'],
			'hashedpassword'	=> $user['password'],
			'hashmethod'		=> 'crypt',
			'creationtimestamp'	=> $user['reg_timestamp']
		));
		// If user doesn't exist in Gallery yet, create user
		if($ret)
		{
			$ret = GalleryEmbed::createUser($user['username'], array(
				'username'			=> $user['username'],
				'email'				=> $user['email'],
				'fullname'			=> "{$user['firstname']} {$user['lastname']}",
				'language'			=> $language_settings[$user['language']]['lang_short_cut'],
				'hashedpassword'	=> $user['password'],
				'hashmethod'		=> 'crypt',
				'creationtimestamp'	=> $user['reg_timestamp']
			));
		}
		return null;
	}
}
?>