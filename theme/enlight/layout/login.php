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
 * The one column layout.
 *
 * @package   theme_enlight
 * @copyright 2015 Nephzat Dev Team,nephzat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_enlight_get_html_for_settings($OUTPUT, $PAGE);

if (isloggedin() && !isguestuser()) {
    redirect ($CFG->wwwroot);
}
if (empty($CFG->authloginviaemail)) {
    $strusername = get_string('username');
} else {
    $strusername = get_string('usernameemail');
}
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
	#site-footer { padding: 0px !important; }
	</style>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>

<div id="custom-page" class="custom-login-page">
	<div class="container-fluid">
    
    	<div class="form-box">
        	<div class="fbox-head">
            	<h2><?php print_string('loginheader', 'theme_enlight') ?></h2>
            </div>
            
           
            <div class="fbox-body">
            <form action="<?php echo $CFG->httpswwwroot; ?>/login/index.php" method="post" id="login1" >
             <div class="alert alert-error" id="lemsg" style="display:none;">
                  &nbsp;
                </div>
 
            	<div class="form-fields">
                	<!--label><?php echo($strusername) ?></label-->
                    <input type="text" name="username" id="username" placeholder="<?php echo($strusername) ?>" value="" />
                    <div id="username-empty"><?php print_string('usernamemissing', 'core_academicenglish') ?></div>
                    <div id="username-mustbe-email"><?php print_string('usernamemustbeemail', 'core_academicenglish') ?></div>
                </div>
            	<div class="form-fields">
                	<!--label><?php print_string("password") ?></label-->
                    <input type="password" name="password" id="password" placeholder="<?php print_string("password") ?>" value=""/>
                    <div id="password-empty"><?php print_string('passwordmissing', 'core_academicenglish') ?></div>
                </div>
				<div class="support-field">
                    <!--label class="checkbox">
                        <input type="checkbox" name="rememberusername" value="1" />
						<?php print_string('rememberusername', 'admin') ?>
                    </label-->
                    <div class="rememberusersession">
                        <input type="checkbox" name="rememberusersession" id="rememberusersession" value="1" />
                        <label for="rememberusersession"><?php print_string('rememberusersession', 'core_academicenglish') ?></label>
                    </div>
                    <p><a href="<?php  echo new moodle_url("/login/forgot_password.php"); ?>">
                    <?php print_string("forgotten") ?></a></p>
                </div>
                <div class="form-action">
					<input type="submit" id="loginbtn1" value="<?php print_string("login") ?>" />
                </div>
                </form>
<?php if ($CFG->guestloginbutton and !isguestuser()) {  ?>
                <form action="index.php" method="post" id="guestlogin">
                  <div class="form-action">
				    <input type="hidden" name="username" value="guest" />
                    <input type="hidden" name="password" value="guest" />
                    <input type="submit" value="<?php print_string("loginguest") ?>" />
                 </div>
               </form>
<?php
}
?>
            </div>
             
        </div>
    
    </div>
</div>
<!--E.O.custom-page-->
<script>
$(function(){ 
	var e1 = $("#loginerrormessage").text();
	if(e1.length>0)
	{
		$("#lemsg").html(e1);
		$("#lemsg").show();
	}
	$("#loginbtn").click(function(){
		var uname = $("#login1 input[name=username]").val();
		$("#login input[name=username]").val(uname);
		
		var pwd = $("#login1 input[name=password]").val();
		$("#login input[name=password]").val(pwd);
		$("#login").submit();
	});

    var username = $('#login1 input[name=username]');
    username.focusout(function() {
        if (!username.val()) {
            $('#login1 #username-empty').addClass('warning');
            console.log('empty username');
        } else {
            console.log('empty email username');
            if (!isValidEmailAddress(username.val())) {
                $('#login1 #username-empty').removeClass('warning');
                $('#login1 #username-mustbe-email').addClass('bademail-warning');
            } else {
                $('#login1 #username-mustbe-email').removeClass('bademail-warning');
            }
            $('#login1 #username-empty').removeClass('warning');
        }
    });
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    $("#login1 #password")
        .focusout(function() {
            if( !$('#login1 #password').val() ) {
                $('#login1 #password-empty').addClass('warning');
            } else {
                $('#login1 #password-empty').removeClass('warning');
            }
        });
});

</script>
<style>
    #username {direction: ltr;text-align: left;}
    #username-mustbe-email {display: none;}
    #username-empty {display: none;}
    #username-empty.warning {display: block; background-color: orange;}
    #username-mustbe-email.bademail-warning {display: block; background-color: red;}

    #password-empty {display: none;}
    #password-empty.warning {display: block; background-color: orange;}
</style>
<?php require_once(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>
</html>
