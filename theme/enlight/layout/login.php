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
    <?php echo custom_css(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>

<div id="custom-page" class="custom-login-page">
    
    <div class="form-tabs">
        <ul>
            <li class="active">התחברות</li>
            <li><a href="<?php echo $CFG->httpswwwroot; ?>/login/signup.php">יצירת חשבון</a></li>
        </ul>
    </div>
    <div class="custom-login-page__header">
        <img src="<?php echo theme_enlight_theme_url(); ?>/images/login-logo.png" alt="">
    </div>

    <div class="container-fluid custom-login-page__content">
        <div class="row-fluid">
            <div class="span6">
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
                            <input type="text" name="username" id="username" placeholder="<?php echo print_string('mail-placeholder', 'theme_enlight') ?>" value="" />
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
                                <input type="checkbox" name="rememberusersession" id="idrememberusersession" value="1" />
                                   <label for="idrememberusersession"><?php print_string('rememberusersession', 'core_academicenglish') ?></label>
                                <a href="<?php  echo new moodle_url("/login/forgot_password.php"); ?>">
                                <?php print_string("forgotten") ?></a>
                            </div>
                            <!-- <p></p> -->
                            <!-- <p><a target="_new" href="<?php  echo new moodle_url("/user/policy.php"); ?>">
                            <?php print_string("policyagreement") ?></a></p> -->
                        </div>
                        <div class="form-action">
                            <input type="submit" id="loginbtn1" value="<?php print_string("login") ?>" />
                            <!-- <a href="<?php echo $CFG->httpswwwroot; ?>/login/signup.php"><?php echo get_string('signup','theme_enlight')?></a> -->
                        </div>
                        </form>
                        <!-- <form action="signup.php" method="get" id="signup">
                            <div class="form-action">
                                <input type="submit" value="<?php theme_enlight_get_setting('signup', 'core_academicenglish') ?>" />
                            </div>
                        </form> -->
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
            <div class="span6 create-account">
                <div class="form-box">
                    <div class="fbox-head">
                        <h2><?php print_string('create-account', 'theme_enlight') ?></h2>
                    </div>
                    <div class="fbox-body">
                        <h5>הרשמה לאתר מאפשרת לך כניסה מהירה ונוחה לחומרי הלימוד וזמינות מלאה מכל מכשיר שתבחר.</h5>
                        <div class="sign-up-image">
                            <img src="<?php echo theme_enlight_theme_url(); ?>/images/sign-up-logo.png" alt="">
                        </div>
                        <a href="<?php echo $CFG->httpswwwroot; ?>/login/signup.php" class="create-account__button"><?php echo get_string('signup','theme_enlight')?></a>
                    </div>
                </div>
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
