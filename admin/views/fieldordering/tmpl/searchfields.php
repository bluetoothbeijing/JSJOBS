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

Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
Factory::getDocument()->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');

$searchable_combo = array(
        (object) array('id' => 1, 'text' => Text::_('Enabled')),
        (object) array('id' => 0, 'text' => Text::_('Disabled'))
    );
?>
<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    

    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">
    	<span class="popup-top">
            <span id="popup_title" ></span>
            <img id="popup_cross" onClick="closePopup();" src="components/com_jsjobs/include/images/popup-close.png">
        </span>
        <form action="index.php" method="POST" class="popup-field-from" name="adminForm" id="adminForm">
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo Text::_('User Search'); ?></div>
                <div class="popup-field-obj"><?php echo customfields::select('search_user', $searchable_combo,0, '', array('class' => 'inputbox one', 'data-validation' => 'required')); ?></div>
            </div>
            <div class="popup-field-wrapper">
                <div class="popup-field-title"><?php echo Text::_('Visitor Search'); ?></div>
                <div class="popup-field-obj"><?php echo customfields::select('search_visitor', $searchable_combo,0, '', array('class' => 'inputbox one', 'data-validation' => 'required')); ?></div>
            </div>
         
	        <?php 
	        echo customfields::hidden('id', 0);
	        echo customfields::hidden('task', 'fieldordering.savesearchfieldordering');
            echo customfields::hidden('option', 'com_jsjobs');
	        echo customfields::hidden('ff',$this->ff);
	        echo HTMLHelper::_( 'form.token' );
	        ?>
        
	        <div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
	                    <input type="submit" name="save" id="save" class="savebutton" value="<?php echo Text::_('Save'); ?>">
	        </div>
        </form>
    </div>

    <div id="jsjobs-content"> <div class="dashboard">
            <div id="jsjobs-wrapper-top-left">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_('Dashboard'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                                if($this->ff == 1){
                                    echo Text::_('Company');
                                }elseif($this->ff == 2){
                                    echo Text::_('Job');
                                }elseif($this->ff == 3){
                                    echo Text::_('Resume');
                                }
                                echo ' '.Text::_('Search Fields');
                            ?>
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
                <?php
                    if($this->ff == 1){
                        echo Text::_('Company');
                    }elseif($this->ff == 2){
                        echo Text::_('Job');
                    }elseif($this->ff == 3){
                        echo Text::_('Resume');
                    }
                    echo ' '.Text::_('Search Fields');
                ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        <form action="index.php?option=com_jsjobs" method="post">
            <div id="jsjobs_filter_wrapper">
                    <span class="jsjobs-filter"><?php echo Text::_($this->lists['search']); ?></span>
                    <span class="jsjobs-filter"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Go'); ?></button></span>
            </div>
            <?php 
            echo customfields::hidden('option', 'com_jsjobs');
            echo customfields::hidden('c', 'fieldordering');
            echo customfields::hidden('layout', 'searchfields');
            echo customfields::hidden('ff',$this->ff);
            echo HTMLHelper::_( 'form.token' );
            ?>
        </form>
       <form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
            <?php if(!empty($this->items)){ ?>      
                <table class="adminlist" cellpadding="1" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th width="70%" class="title bold" > <span class="blackcolor"><?php echo Text::_('Field Title'); ?></span></th>
                            <th width="10%" class="center bold"><span class="blackcolor"><?php echo Text::_('User Search'); ?></span></th>
                            <th width="10%" class="center bold"><span class="blackcolor"><?php echo Text::_('Visitor Search'); ?> </span>    </th>
                            <th width="10%" class="center bold" nowrap="nowrap"><span class="blackcolor"><?php echo Text::_('Edit'); ?></span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    	$row = $this->items[$i]; 
                        ?>
                        <tr id="id_<?php echo $row->id; ?>">
                                <td class="left-row" style="cursor: grab;">
                                	<?php echo Text::_($row->fieldtitle); ?>
	                            </td>
                                <td align="center">
    								<?php if ($row->search_user == 1) { ?>
                                        <img src="components/com_jsjobs/include/images/tick.png"/>
                                    <?php } else { ?>
                                        <img src="components/com_jsjobs/include/images/cross.png"/>
                                    <?php } ?>
                                </td>
                                <td align="center">
									<?php if ($row->search_visitor == 1) { ?>
                                        <img src="components/com_jsjobs/include/images/tick.png" />
                                    <?php } else { ?>
                                        <img src="components/com_jsjobs/include/images/cross.png"/>
                                    <?php } ?>
                                </td>
                                <td class="center">
                                    <a class="action-btn" href="javascript:void(0);" onclick="showPopupAndSetValues(<?php echo $row->id; ?>,'<?php echo $row->fieldtitle; ?>',<?php echo $row->search_user; ?>,<?php echo $row->search_visitor; ?>)" >
                                    	<img src="components/com_jsjobs/include/images/edit.png" title="<?php echo Text::_('Edit'); ?>">
                                    </a>
                                </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="fieldordering.savesearchfieldorderingFromForm" />
                <input type="hidden" name="fields_ordering_new" id="fields_ordering_new" />
                <input type="hidden" name="ff" value="<?php echo $this->ff; ?>" />
                <?php echo HTMLHelper::_('form.token'); ?>

                <div class="js-buttons-area">
                    <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save').' '.Text::_('Ordering'); ?>" />
                    <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=searchfields&ff=<?php echo $this->ff; ?>"><?php echo Text::_('Cancel'); ?></a>
                </div>

			<?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
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

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery('table#js-table tbody').sortable({
            handle : ".grid-rows , .left-row",
            update  : function () {
                var abc =  jQuery('table#js-table tbody').sortable('serialize');
                jQuery('input#fields_ordering_new').val(abc);
            }
            
        });
    }); 
    function showPopupAndSetValues(id,title_string, search_user, search_visitor) {
    	jQuery("select#search_user").val(search_user);
        jQuery("select#search_visitor").val(search_visitor);
        jQuery("input#id").val(id);
        jQuery("span#popup_title").text(title_string);
        jQuery("div#full_background").css("display", "block");
        jQuery("div#popup_main").slideDown('slow');
    }

    function closePopup() {
        jQuery("div#popup_main").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            // jQuery("div#popup_main").html('');
        }, 700);
    }
</script>
