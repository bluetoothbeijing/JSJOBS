<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     https://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;    
use Joomla\CMS\Component\ComponentHelper;

jimport('joomla.html.parameter');
HTMLHelper::_('behavior.formvalidator');
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>

<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <div id="js_main_wrapper">
        <span class="js_controlpanel_section_title">
            <?php
                if ($this->userrole == 2) {
                    echo Text::_('Job Seeker Login');
                }elseif ($this->userrole == 3){
                    echo Text::_('Employer Login');
                }
                else{
                    echo Text::_('Login');
                }
            ?>
        </span>
        <?php if ($this->config['cur_location'] == '1') { ?>
            <div class="jsjobs-breadcrunbs-wrp">
              <div id="jsjobs-breadcrunbs">
                  <ul>
                      <li>
                            <?php
                            if ($this->userrole == 2) {
                                $dlink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                $dtext='Job Seeker Login';
                            }else if ($this->userrole == 3){
                                $dlink='index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
                                $dtext='Employer Login';
                            }
                            else{
                                $dlink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                $dtext='Login';
                            }
                            ?>
                            <a href="<?php echo $dlink; ?>" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_("Dashboard"); ?>
                            </a>
                      </li>
                      <li>
                        <?php echo Text::_($dtext); ?>
                    </li>
                  </ul>
              </div>
            </div>
        <?php } ?>
        <form action="<?php echo Route::_('index.php?option=com_users&task=user.login' ,false); ?>" method="post" id="loginform" name="loginform">

            <div id="userform" class="userform jsjobs-login-wrp">
                <table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
                    <tr>
                        <td align="right" nowrap class="jsjobs-login-title">
                            <label id="name-lbl" for="name"><?php echo Text::_('User Name'); ?>:* </label> 
                        </td>
                        <td class="jsjobs-login-value">
                            <input id="username" class="validate-username inputbox" type="text" size="25" value="" name="username" >
                        </td>
                    </tr>
                    <tr>
                        <td align="right" nowrap class="jsjobs-login-title">
                            <label id="password-lbl" for="password"><?php echo Text::_('Password'); ?>:* </label> 
                        </td>
                        <td class="jsjobs-login-value">
                            <input id="password" class="validate-password inputbox" type="password" size="25" value="" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center" class="jsjobs-login-btn">
                            <input id="button" class="button validate" type="button" onclick="return checkformlogin();" value="<?php echo Text::_('Login'); ?>"/>

                    <?php /*<button  type="submit" class="button validate" onclick="return myValidate(this.loginform);"><?php echo Text::_('Login'); ?></button>*/ ?>
                        </td>
                    </tr>

                </table>
                <input type="hidden" name="return" value="<?php echo $this->loginreturn; ?>" />
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>	
        </form>
    </div>
    <div>
        <ul class="jsjobs-login-wrp">
            <li>
                <a href="<?php echo Route::_('index.php?option=com_users&view=reset' ,false); ?>">
                    <?php echo Text::_('Forgot Your Password?'); ?></a>
            </li>
            <li>
                <a href="<?php echo Route::_('index.php?option=com_users&view=remind' ,false); ?>">
                    <?php echo Text::_('Forgot Your Username?'); ?></a>
            </li>
            <?php
            $usersConfig = ComponentHelper::getParams('com_users');
            if ($usersConfig->get('allowUserRegistration')) :
                ?>
                <li>
                    <a href="<?php echo Route::_('index.php?option=com_users&view=registration&Itemid=0' ,false); ?>">
                        <?php echo Text::_('Do not have an account?'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <script type="text/javascript" language="javascript">
        function checkformlogin() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            if (username != "" && password != "") {
                document.loginform.submit();
            } else {
                alert("<?php echo Text::_("Fill Req Fields"); ?>");
            }
        }
    </script>	


<?php } //ol  ?>
<div id="jsjobsfooter">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions</a></span>
            </td>
        </tr>
    </table>
</div>

</div>

