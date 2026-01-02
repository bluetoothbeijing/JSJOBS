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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Filter\OutputFilter;

$document = Factory::getDocument();


Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');

$status = array(
    '1' => Text::_('Approved'),
    '-1' => Text::_('Rejected'));
?>



<script language=Javascript>
    function confirmdelete() {
        if (confirm("<?php echo Text::_('Are you sure to delete'); ?>") == true) {
            return true;
        } else
            return false;
    }
</script>

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
                            <?php echo Text::_('GDPR Fields'); ?>
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
                <?php echo Text::_('GDPR Fields'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('gdpr.add');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New Field'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('Field'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
      <form action="index.php" method="post" name="adminForm" id="adminForm">
           
            <?php if(!empty($this->gdprfiels)){ ?>
                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th class="title"><?php echo Text::_('Field Title'); ?></th>
                            <th class="title"><?php echo Text::_('Field Text'); ?></th>
                            <th class="center"><?php echo Text::_('Required'); ?></th>
                            <th class="center"><?php echo Text::_('Ordering'); ?></th>
                            <th class="center"><?php echo Text::_('Link').' '.Text::_('Type'); ?></th>
                            <th class="center"><?php echo Text::_('Link'); ?></th>
                            <th class="center"><?php echo Text::_('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    $i=0;
                    foreach($this->gdprfiels AS $field) {

                        $termsandconditions_text = '';
                        $termsandconditions_linktype = '';
                        $termsandconditions_link = '';
                        $termsandconditions_page = '';
                        if(isset($field->userfieldparams) && $field->userfieldparams != '' ){
                            $userfieldparams = json_decode($field->userfieldparams,true);
                            $termsandconditions_text = isset($userfieldparams['termsandconditions_text']) ? $userfieldparams['termsandconditions_text'] :'' ;
                            $termsandconditions_linktype = isset($userfieldparams['termsandconditions_linktype']) ? $userfieldparams['termsandconditions_linktype'] :'' ;
                            $termsandconditions_link = isset($userfieldparams['termsandconditions_link']) ? $userfieldparams['termsandconditions_link'] :'' ;
                            $termsandconditions_page = isset($userfieldparams['termsandconditions_page']) ? $userfieldparams['termsandconditions_page'] :'' ;
                            if($termsandconditions_linktype == 2){
                                $article = Table::getInstance("content");
                                $article->load($termsandconditions_page);
                                // echo $article->get("title");
                                $page_title_link = $article->get("title");
                            }else{
                                $page_title_link = $termsandconditions_link;
                            }
                        }
                        $requiredpubimg = $field->required ? 'tick.png' : 'cross.png';
                        $requiredalt = $field->required ? Text::_('Required') : Text::_('Not required');
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=gdpr.edit&cid=' . $field->id);
                        $checked = HTMLHelper::_('grid.id', $i, $field->id);
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td class="title">
                                <a href="<?php echo $link; ?>">
                                    <?php echo $field->fieldtitle; ?></a>
                            </td>
                            <td class="title">
                                <?php echo  $termsandconditions_text; ?>
                            </td>
                            <td class="center">
                                <img src="components/com_jsjobs/include/images/<?php echo $requiredpubimg; ?>" alt="<?php echo $requiredalt; ?>" />
                            </td>
                            <td class="center">
                                <?php  echo $field->ordering; ?>
                            </td>
                            <td class="center">
                                <?php if($termsandconditions_linktype == 2){
                                    echo Text::_('Article Page');
                                }else{
                                    echo Text::_('Direct URL');
                                } ?>
                            </td>
                            <td class="center">
                                <?php echo Text::_($page_title_link); ?>
                            </td>
                            <td class="center">
                                <a class="action-btn" href="<?php echo 'index.php?option=' . $this->option . '&task=gdpr.remove&cid=' . $field->id; ?>">
                                    <img onclick="return confirmdelete()" src="components/com_jsjobs/include/images/remove.png"  alt="<?php echo Text::_("Delete Field"); ?>" />
                                </a>
                            </td>

                        </tr>
                        <?php
                        $k = 1 - $k;
                        $i++;
                    }
                    ?>
                </tbody>
                </table>
                                <?php /* <div id="jsjobs-pagination-wrapper"> */ ?>
                    <?php //echo $this->pagination->getLimitBox(); ?>
                    <?php //echo $this->pagination->getListFooter(); ?>
                <?php /* </div> */ ?>
            <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="gdpr" />
                <input type="hidden" name="view" value="gdpr" />
                <input type="hidden" name="layout" value="gdprfiels" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
            </form>
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
</div>



