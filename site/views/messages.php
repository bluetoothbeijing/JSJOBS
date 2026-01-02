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
 
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;


class JSJobsMessages {

    function getSystemOfflineMsg($config) {
        $html = '
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/offline-icon.png"/>
                    </div>    
                    <div class="message2">
                        <span class="message-txt">
                            ' . $config["title"] .' '.Text::_('Is Offline'). '
                        </span>
                        <span class="message-text">
                            ' . $config["offline_text"] . '
                         </span>
                    </div>
                    <div class="footer">

                    </div>
                </div>
        ';
        echo $html;
    }


    function getCPNoRecordFound() {
        $msg = '<div class="jsjobs-cp-applied-resume-not-found">
                    <div class="jsjobs-cp-not-found-data">
                       <img class="jsjobs-cp-not-found-img" src="'.Uri::root().'components/com_jsjobs/images/controlpanel/jobseeker/no-record.png">  
                       <span class="jsjobs-not-found-title">'. Text::_('Record Not Found').'</span>
                    </div>
                </div>';
        echo $msg;
    }


    function getAccessDeniedMsg($msgTitle, $msgLang, $isVisitor = 0) {
        if ($isVisitor == 1) {
            $src = 'login.png';
        }else{
            $src = 'no-record.png';
        }
        if($msgTitle == 'Jobseeker is not allowed'){
            $src = 'user-ban.png';   
        }
        $html = '<div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/'.$src.'"/>
                    </div>    
                    <div class="message2">
                        <span>
                            ' . Text::_($msgTitle) . '
                        </span>
                    </div>
                    <div class="footer">
                    ';

        if ($isVisitor == 1) {
            $itemid = Factory::getApplication()->input->get('Itemid');
            $default_login_from = JSModel::getJSModel('configurations')->getConfigValue('default_login_from');
            if($default_login_from == 1){ // JS Jobs login
                $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid;    
            }elseif($default_login_from == 2){ // Joomla Login
                $link = 'index.php?option=com_users&view=login&Itemid=' . $itemid;
            }else{ // custom link
                $login_custom_link = JSModel::getJSModel('configurations')->getConfigValue('login_custom_link');
                $link = $login_custom_link;
                if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                        $link = "http://" . $link; 
                }                                         
                if($login_custom_link == ""){
                  $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid;   // JS Jobs link 
                }
            }
            $html.= '<a class="login" href="'.$link.'" >' . Text::_("Login") . '</a>';
        }

        $html .= '
                    </div>
                </div>
        ';

        echo $html;
    }

    function getAccessDeniedMsg_return($msgTitle, $msgLang , $links = "") {
        $html = '<div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/login.png"/>
                    </div>    
                    <div class="message2">
                        <span class="message-txt">
                            ' . Text::_($msgTitle) . '
                        </span>
                         <span class="message-text">
                            ' . Text::_($msgLang) . '
                         </span>
                    </div>
                    <div class="footer">
                        '.$links.'
                    </div>
                </div>
                ';
        return $html;
    }


    function getPackageExpireMsg($msgTitle, $msgLang, $link, $linktitle = 'Packages') {
        $html = '
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/no-package.png"/>
                    </div>    
                    <div class="message2">
                        <span class="message-txt">
                            '.Text::_($msgTitle).'
                        </span>
                         <span class="message-text">
                            '.Text::_($msgLang).'
                         </span>
                    </div>
                    <div class="footer">
                        <a class="register" href="'.$link.'" >'.Text::_($linktitle).'</a>
                    </div>
                </div>
        ';
        echo $html;
    }


    function getUserNotSelectedMsg($msgTitle, $msgLang, $link) {
        $html = '
                <div class="js_job_error_messages_wrapper">
                    <div class="message1">
                        <span>
                            '.Text::_($msgTitle).'
                        </span>
                    </div>    
                    <div class="message2">
                         <span class="img">
                        <img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/not-allowed.png"/>
                         </span> 
                         <span class="message-text">
                            '.Text::_($msgLang).'
                         </span>
                    </div>
                    <div class="footer">
                        <a class="login" href="'.$link.'" >'.Text::_('Please select your role').'</a>
                    </div>
                </div>
        ';
        echo $html;
    }
}

?>
