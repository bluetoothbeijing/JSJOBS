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
use Joomla\CMS\Filter\OutputFilter;

Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
use Joomla\CMS\Session\Session;

//echo '<pre>'; print_r($this->items); echo "</pre>";
$sort_combo = array(
    '0' => array('value' => 0, 'text' => Text::_('Sort By')),
    '1' => array('value' => 1, 'text' => Text::_('State Name')),
    '2' => array('value' => 2, 'text' => Text::_('Status')),);
    
    $sort_combo = HTMLHelper::_('select.genericList', $sort_combo, 'js_sortby', 'class="inputbox" onchange="js_Ordering();"'.'', 'value', 'text',$this->js_sortby);
?>
<script type="text/javascript">
    function js_Ordering(){
        var val = jQuery('#js_sortby option:selected').val();
        jQuery('form#adminForm').submit();
    }

    function js_imgSort(){
        var val = jQuery('#js_sortby option:selected').val();
        if(val!=0){
            jQuery('#my_click').val('1');
            jQuery('form#adminForm').submit();
        }
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
                            <a href="index.php?option=com_jsjobs&c=country&view=country&layout=countries" title="<?php echo Text::_('Countries'); ?>">
                                <?php echo Text::_('Countries'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                                echo Text::_('States');
                            ?>
                        </li>
                    </ul>
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
                    echo Text::_('Countries').' > '.Text::_('States');
                 ?>
            </h1>
            <a onclick="Joomla.submitbutton('state.editjobstate');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New').' '.Text::_('State'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('State'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
         <form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
         <div id="jsjobs_filter_wrapper">
         <span class="jsjobs_filter-inner_wrapper">                                         
            <span class="jsjobs-filter"><input type="text" name="searchname" placeholder="<?php echo Text::_('Name'); ?>" id="searchname" value="<?php if (isset($this->lists['searchname'])) echo $this->lists['searchname']; ?>" /></span>
            <span class="jsjobs-filter"><?php echo $this->lists['searchstatus']; ?></span>
            <span class="jsjobs-filter "><div class="mycheckboxblock"><input type="checkbox" class="jsinputstate" id="hascities" name="hascities" value="" <?php if (isset($this->lists['hascities'])) echo "checked"; ?> /><label id="hascities" for="hascities" name="hascities" class="jshasstates"><?php echo Text::_('Has Cities'); ?></label></div></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('searchname').value = '';
                    document.getElementById('searchstatus').value = '';                                                        
                    document.getElementById('hascities').checked = false;                                                        
                    this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
                </span>
            <span id="sorting-wrapper">
                <span id="sorting-bar1">
                    <?php echo $sort_combo ?>
                </span>
                <span id="sortimage">
                    <?php if ($this->sort == 'asc') {$img = "components/com_jsjobs/include/images/down.png"; }else{$img = "components/com_jsjobs/include/images/up.png"; } ?>
                    <a href="javascript: void(0);"onclick="js_imgSort();"><img src="<?php echo $img ?>"></a>
                </span>
            </span>
         </div>
<?php if(!empty($this->items)){ ?>
                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <td colspan="3" class="s-td">
                                <?php
                                $session = Factory::getApplication()->getSession();
                                $countryid = $session->get('countryid');
                                $statecode = $session->get('statecode');
                                ?>
                               <!--  <a href="index.php?option=com_jsjobs&c=country&view=country&layout=countries"><?php //echo Text::_('Countries'); ?></a> > <?php //echo Text::_('States'); ?>  -->
                            </td>
                        </tr>

                        <tr>
                            <th width="1%">
                                <?php if (JVERSION < '3') { ?> 
                                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                                <?php } else { ?>
                                    <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                <?php } ?>
                            </th>
                            <th  width="60%" class="title"><?php echo Text::_('Name'); ?></th>
                            <th width="13%" class="center"><?php echo Text::_('Published'); ?></th>
                            <th width="13%" class="center"><?php echo Text::_('Cities'); ?></th>
                        </tr>
                    </thead><tbody>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=state.editjobstate&cid[]=' . $row->id);
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td>
                                <?php echo $checked; ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>">
                                    <?php echo $row->name; ?></a>
                            </td>
                            <td align="center">
                                <?php
                                if ($row->enabled == '1') {
                                    $functionname = 'unpublishstates';
                                    $img = 'tick.png';
                                } else {
                                    $functionname = 'publishstates';
                                    $img = 'cross.png';
                                }
                                ?>

                                <?php
                                    if(JVERSION < 4){
                                        $token = Factory::getSession()->getFormToken();
                                    }else{
                                        $token = Session::getFormToken();
                                    }
                                ?>
                                <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=state.'.$functionname.'&cid[]='.$row->id.'&'.$token.'=1'); ?>
                                <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                    <img src="components/com_jsjobs/include/images/<?php echo $img; ?>"  />
                                </a>
                            </td>
                            <td class="center"><a href="index.php?option=com_jsjobs&c=city&view=city&layout=cities&sd=<?php echo $row->id; ?>&ct=<?php echo $this->ct; ?>"><?php echo Text::_('Cities'); ?></a></td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
</tbody>
                </table>
                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
            <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="state" />
                <input type="hidden" name="view" value="state" />
                <input type="hidden" name="layout" value="states" />
                <input type="hidden" name="task" value="view" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="sortby" value="<?php echo $this->sort;?>"/>
                <input type="hidden" id="my_click" name="my_click" value=""/>
                
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



