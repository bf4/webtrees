<?php
/**
 * Add media to gedcom file
 *
 * This file allows the user to maintain a seperate table
 * of media files and associate them with individuals in the gedcom
 * and then add these records later.
 * Requires SQL mode.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2009  PGV Development Team.  All rights reserved.
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
 * @package PhpGedView
 * @subpackage search
 * @version $Id: opensearch.php 5320 2009-04-23 22:16:41Z kosherjava $
 */
require './config.php';
header('Content-Type: application/opensearchdescription+xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">';
echo '<ShortName>' . $GEDCOMS[$GEDCOM]["title"] . ' ' . $pgv_lang["search"]  . '</ShortName>';
echo '<Description>' . $GEDCOMS[$GEDCOM]["title"] . ' ' . $pgv_lang["search"] . '</Description>';
echo '<InputEncoding>UTF-8</InputEncoding>';
echo '<Url type="text/html" template="' . $SERVER_URL. 'search.php?action=general&amp;topsearch=yes&amp;query={searchTerms}"/>';
echo'<Image height="16" width="16" type="image/x-icon">' . $SERVER_URL. $FAVICON . '</Image>';
echo '</OpenSearchDescription>';
?>