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
 * Language definition for the RSS client block, EN.
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname']               = 'Moderated RSS';
$string['promo']                    = 'Moderated RSS plugin for Moodle';
$string['promodesc']                = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['selectrss']                = 'Moderated RSS';
$string['selectrss:addinstance']    = 'Add a new Moderated RSS block';
$string['selectrss:myaddinstance']  = 'Add a new Moderated RSS block to the My Moodle page';
$string['selectrss:managefeeds']    = 'Manage Moderated RSS Feeds';
$string['rss_items']                = 'RSS Items';
$string['rss_item_title']           = 'RSS Item';
$string['rss_item_intro']           = 'RSS Item Intro';
$string['rss_feeds']                = 'RSS Feeds';
$string['rss_feeds_info']           = 'Add multiple feeds, a new one on each line';
$string['num_items']                = 'Max. number of items to display';
$string['num_items_info']           = '';
$string['get_num_items']            = 'Max. number of items to get';
$string['get_num_items_info']       = '';
$string['max_keep_items']           = 'Max. number of items kept';
$string['max_keep_items_info']      = '';
$string['autocleanafter']           = 'Remove items older than';
$string['autocleanafter_info']      = '';
$string['status']                   = 'Status';
$string['date']                     = 'Date';
$string['title']                    = 'Title';
$string['source']                   = 'Source';
$string['viewfeed']                 = 'Manage Feed Items';
$string['itemsedit']                = 'Manage Feed Items';
$string['feedurl']                  = 'Max. number of items to fetch';
$string['unpublish']                = 'Unpublish';
$string['publish']                  = 'Publish';
$string['active']                   = 'Active';
$string['inactive']                 = 'Inactive';
$string['get_items']                = 'Fetch RSS Items';
$string['viewfeeds']                = 'View feeds';
$string['blockid']                  = 'Block ID';
$string['manageitems']              = 'Manage Items';
$string['default_publish']          = 'Publish by default?';

$string['cleanfeeds']               = 'Cleaning of old RSS items';
$string['synchronizefeeds']         = 'Fetch new RSS items';
$string['custom_title']             = 'Custom block title';

$string['days']                     = 'Days';
$string['weeks']                    = 'Weeks';
$string['months']                   = 'Months';

$string['msg:published']            = '<div class="published">Item published</div>';
$string['msg:unpublished']          = '<div class="unpublished">Item unpublished</div>';

$string['messagesubject']           = 'There are {$a} messages awaiting moderation in Moodle';
$string['messagehtml']              = '<p>Dear {$a->fullname},</p>
<p>There are feeds added waiting to be authorized</p>
<p>Please login to your moodle environment at <a href="{$a->siteurl}">{$a->sitename}</a>
to authorize or review any Moderated RSS feed items.</p>
<p>Kind regards,<br/>{$a->from}</p>';
$string['clientshowpartialcontentlabel'] = 'Show (partial) content (limited to max 30 words)';