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
use Joomla\CMS\Language\Text;

?>

<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    
    <div id="jsjobs-content">
        <div class="dashboard">
            <div id="jsjobs-wrapper-top-left">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_('Dashboard'); ?>
                            </a>
                        </li>
                         <li>
                            <?php echo Text::_('Translations'); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="jsjobs-wrapper-top-right">
                <div id="jsjobs-config-btn">
                    <a href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations" title="<?php echo Text::_('Configuration'); ?>">
                        <img alt="<?php echo Text::_('Configuration'); ?>" src="components/com_jsjobs/include/images/icon/config.png" />
                    </a>
                </div>
                <div id="jsjobs-help-btn" class="jsjobs-help-btn">
                    <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=help" title="<?php echo Text::_('Help'); ?>">
                        <img alt="<?php echo Text::_('Help'); ?>" src="components/com_jsjobs/include/images/help-page/help.png" />
                    </a>
                </div>
                <div id="jsjobs-vers-txt">
                    <?php echo Text::_("Version").' :'; ?>
                    <span class="jsjobs-ver">
                        <?php
                        $version1 = $this->getJSModel('configuration')->getConfigByFor('default');
                        $version = str_split($version1['version']);
                        $version = implode('', $version);
                        echo $version?>
                    </span>
                </div>
            </div>
        </div>
        <div id="jsjobs-head">
            <h1 class="jsjobs-head-text">
                <?php echo Text::_('Translations'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
        <div id="black_wrapper_translation"></div>
        <div id="jstran_loading">
            <img src="components/com_jsjobs/include/images/spinning-wheel.gif" />
        </div>

        <div class="js-language-main-wrapper">
            <div id="js-language-wrapper">
                <div class="jstopheading"><?php echo Text::_('Get JS Jobs Translations');?></div>
                <div id="gettranslation" class="gettranslation"><img style="width:18px; height:auto;" src="components/com_jsjobs/include/images/download-icon.png" /><?php echo Text::_('Get Translations');?></div>
                <div id="js_ddl">
                    <span class="title"><?php echo Text::_('Select Translation');?>:</span>
                    <span class="combo" id="js_combo"></span>
                    <span class="button" id="jsdownloadbutton"><img style="width:14px; height:auto;" src="components/com_jsjobs/include/images/download-icon.png" /><?php echo Text::_('Download');?></span>
                    <div id="jscodeinputbox" class="js-some-disc"></div>
                    <div class="js-some-disc"><img style="width:18px; height:auto;" src="components/com_jsjobs/include/images/info-icon.png" /><?php echo Text::_('When Joomla language change to ro, JS Jobs language will auto change to ro');?></div>
                </div>
                <div id="js-emessage-wrapper">
                    <img src="components/com_jsjobs/include/images/c_error.png" />
                    <div id="jslang_em_text"></div>
                </div>
                <div id="js-emessage-wrapper_ok">
                    <img src="components/com_jsjobs/include/images/saved.png" />
                    <div id="jslang_em_text_ok"></div>
                </div>
            </div>
            <div id="js-lang-toserver">
                <div class="js-col-xs-12 js-col-md-6 col"><a class="anc one" href="https://www.transifex.com/joom-sky/js_jobs" target="_blank"><img src="components/com_jsjobs/include/images/translation-icon.png" /><?php echo Text::_('Contribute In Translation');?></a></div>
                <?php /*
                <div class="js-col-xs-12 js-col-md-4 col"><a class="anc two" href="https://www.joomsky.com/translations.html" target="_blank"><img src="components/com_jsjobs/include/images/manual-download.png" /><?php echo Text::_('Mannual Download');?></a></div>
                */?>
            </div>
        </div>
        </div>
    </div>
</div>
<div id="jsjobs-footer">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div><script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#gettranslation').click(function(){
            jsShowLoading();
            jQuery.post("index.php?option=com_jsjobs&c=jsjobs&task=getlisttranslations",{}, function (data) {
                if (data) {
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#js-emessage-wrapper div').html(data['error']);
                        jQuery('#js-emessage-wrapper').show();
                    }else{
                        jQuery('#js-emessage-wrapper').hide();
                        jQuery('#gettranslation').hide();
                        jQuery('div#js_ddl').show();
                        jQuery('span#js_combo').html(data['data']);
                    }
                }
            });
        });
        
        jQuery(document).on('change', 'select#translations' ,function() {
            var lang_name = jQuery( this ).val();
            if(lang_name != ''){
                jQuery('#js-emessage-wrapper_ok').hide();
                jsShowLoading();
                jQuery.post("index.php?option=com_jsjobs&c=jsjobs&task=validateandshowdownloadfilename",{ langname:lang_name}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                            jQuery('#jscodeinputbox').slideUp('400' , 'swing' , function(){
                                jQuery('input#languagecode').val("");
                            });
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#jscodeinputbox').html(data['path']+': '+data['input']);
                            jQuery('#jscodeinputbox').slideDown();
                        }
                    }
                });
            }
        });

        jQuery('#jsdownloadbutton').click(function(){
            jQuery('#js-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post("index.php?option=com_jsjobs&c=jsjobs&task=getlanguagetranslation",{ langname:lang_name , filename: file_name}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#js-emessage-wrapper_ok div').html(data['data']);
                            jQuery('#js-emessage-wrapper_ok').slideDown();
                        }
                    }
                });
            }
        });
    });
    
    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }    

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#jstran_loading').hide();
    }
</script>
