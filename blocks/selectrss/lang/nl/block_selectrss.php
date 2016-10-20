<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language definition for the RSS client block, NL.
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname']               = 'Moderated RSS';
$string['promo']                    = 'Moderated RSS plugin voor Moodle';
$string['promodesc']                = 'Deze plugin is geschreven door Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['selectrss']                = 'Moderated RSS';
$string['selectrss:addinstance']    = 'Voeg een nieuw Moderated RSS block toe';
$string['selectrss:myaddinstance']  = 'Voeg een nieuw Moderated RSS block toe aan Mijn Moodle pagina';
$string['selectrss:managefeeds']    = 'Beheer Moderated RSS Feeds';
$string['rss_items']                = 'RSS Items';
$string['rss_item_title']           = 'RSS Item';
$string['rss_item_intro']           = 'RSS Item Intro';
$string['rss_feeds']                = 'RSS Feeds';
$string['rss_feeds_info']           = 'Voeg meerdere feeds toe, gescheiden op een regel';
$string['num_items']                = 'Max. aantal weer te geven items';
$string['num_items_info']           = '';
$string['get_num_items']            = 'Max. aantal op te halen items';
$string['get_num_items_info']       = '';
$string['max_keep_items']           = 'Max. aantal te behouden berichten';
$string['max_keep_items_info']      = '';
$string['autocleanafter']           = 'Verwijder berichten ouder dan';
$string['autocleanafter_info']      = '';
$string['status']                   = 'Status';
$string['date']                     = 'Datum';
$string['title']                    = 'Titel';
$string['source']                   = 'Bron';
$string['viewfeed']                 = 'Beheer Feed Items';
$string['itemsedit']                = 'Beheer Feed Items';
$string['feedurl']                  = 'Max. aantal op te halen items';
$string['unpublish']                = 'Depubliceren';
$string['publish']                  = 'Publiceren';
$string['active']                   = 'Actief';
$string['inactive']                 = 'Inactief';
$string['get_items']                = 'RSS Items ophalen';
$string['viewfeeds']                = 'View feeds';
$string['blockid']                  = 'Block ID';
$string['manageitems']              = 'Manage Items';
$string['default_publish']          = 'Automatisch publiceren?';

$string['cleanfeeds']               = 'Opschonen oude RSS items';
$string['synchronizefeeds']         = 'Ophalen nieuwe RSS items';
$string['custom_title']             = 'Block titel';

$string['days']                     = 'Dagen';
$string['weeks']                    = 'Weken';
$string['months']                   = 'Maanden';

$string['msg:published']            = '<div class="published">Item gepubliceerd</div>';
$string['msg:unpublished']          = '<div class="unpublished">Publicatie ongedaan gemaakt</div>';

$string['messagesubject']           = 'Er zijn {$a} berichten die geautoriseerd moeten worden in Moodle';
$string['messagehtml']              = '<p>Beste {$a->fullname},</p>
<p>Er staan nog te modereren RSS berichten voor u klaar</p>
<p>Log aub in op uw moodle omgeving op <a href="{$a->siteurl}">{$a->sitename}</a>
om Moderated RSS berichten te autoriseren of valideren.</p>
<p>Met vriendelijke groet,<br/>{$a->from}</p>';
$string['clientshowpartialcontentlabel'] = 'Toon (gedeeltelijk) inhoud (maximaal 30 woorden)';