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
 * @package    theme_enlight
 * @copyright  2015 Nephzat Dev Team , nephzat.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
require_once('../../../config.php');
global $CFG, $DB, $PAGE, $USER, $OUTPUT;
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->set_title('Typography');

echo $OUTPUT->header();
?>

<div class="nz-content">
    <div class="container-fluid">
    
	    <h2 class="nz-pagehead"><?php echo get_string('typography', 'theme_enlight'); ?></h2>
        <div class="row-fluid nz-examples">
            <div class="span7">
                <fieldset>
                    <legend>Heading Regular Sizes</legend>
                    <div class="nz-example-content">
                        <h1>Header 1</h1>
                        <h2>Header 2</h2>
                        <h3>Header 3</h3>
                        <h4>Header 4</h4>
                        <h5>Header 5</h5>
                        <h6>Header 6</h6>
                    </div>
                </fieldset>
            </div>
            <div class="span5">
                <fieldset>
                    <legend>Dropdown</legend>
                    <div class="nz-example-content" style="min-height: 220px;">
                        <div class="ddltr">                        
                            <div class="dropdown clearfix">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                Dropdown<span class="caret"></span></button>
                                <ul class="dropdown-menu" style="display: block;">
                                    <li><a href="#">Menu Item one</a></li>
                                    <li><a href="#">Menu Item two</a></li>
                                    <li><a href="#">Menu Item three</a></li>
                                    <li><a href="#">Menu Item four</a></li>
                                </ul>
                            </div>
						</div>
                        
                        <div class="ddrtl">
                            <div class="dropdown clearfix">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                Dropdown<span class="caret"></span></button>
                                <ul class="dropdown-menu" style="display: block;">
                                    <li><a href="#">Menu Item one</a></li>
                                    <li><a href="#">Menu Item two</a></li>
                                    <li><a href="#">Menu Item three</a></li>
                                    <li><a href="#">Menu Item four</a></li>
                                </ul>
                            </div>
                        </div>
					</div>
                </fieldset>
            </div>
        </div>
        <div class="row-fluid nz-examples">
            <div class="span6">
                <fieldset>
                    <legend>Buttons</legend>
                    <div class="nz-example-content">
                        <div class="nz-btn-examples">
                            <h4>Example</h4>
                            <a class="btn btn-default" href="javascript:void(0);">Link</a>
                            <button class="btn btn-default">Button</button>
                            <input type="button" class="btn btn-default" value="Input">
                            <input type="submit" class="btn btn-default" value="Submit">
                        </div>
                        <div class="nz-btn-examples">
                            <h4>Styles</h4>
                            <button class="btn btn-default">Style 1</button>
                            <button class="btn btn-primary">Style 2</button>
                        </div>
                        <div class="nz-btn-examples">
                            <h4>Sizes</h4>
                            <button class="btn btn-primary btn-large">Button</button>
                            <button class="btn btn-primary">Button</button>
                            <button class="btn btn-primary btn-small">Button</button>
                            <button class="btn btn-primary btn-mini">Button</button>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="span6">
                <fieldset>
                    <legend>Paragraph</legend>
                    <div class="nz-example-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard  been the industry's standard  
                        Ipsum has been the industry's standard type and scrambled it to make a type specimen book. 
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard  been the industry's standard  
                        Ipsum has been the industry's standard type and scrambled it to make a type
                         specimen book. Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                         Lorem Ipsum has been the industry's standard  been the industry's standard  
                         Ipsum has been the industry's standard type and scrambled it to make a type specimen book.</p>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard  been the industry's standard 
                         Ipsum has been the industry's standard type and scrambled it to make a 
                         type specimen book. Lorem Ipsum is simply dummy text of the printing and 
                         typesetting industry. Lorem Ipsum has been the industry's standard  been 
                         the industry's standard  Ipsum has been the industry's standard type and 
                         scrambled it to make a type specimen book.</p>
                    </div>
                </fieldset>
            </div>
        </div>
    
    </div>
</div>
<!--Typographic Content-->

<script>
$(function(){
	$("#page-navbar").hide();
});
</script>

<?php 
echo $OUTPUT->footer();
