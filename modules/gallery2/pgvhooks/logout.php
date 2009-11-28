<?php
/**
 * phpGedView Gallery 2 Module.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @subpackage Gallery2
 * @version $Id$
 * @author Patrick Kellum
 */
$modinfo = parse_ini_file('modules/gallery2.php', true);
if(!isset($modinfo['Gallery2']['path'])){$modinfo['Gallery2']['path'] = 'modules/gallery2';}
if(file_exists("{$modinfo['Gallery2']['path']}/embed.php")){require_once "{$modinfo['Gallery2']['path']}/embed.php";}

class mod_gallery2_logout
{
	function hook($params)
	{
		if(!class_exists('GalleryEmbed')){return null;}
		$g2ret = GalleryEmbed::logout();
		return null;
	}
}
?>
