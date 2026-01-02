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


$document = Factory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/jsjobsrating.css');
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/js/chosen/chosen.min.css');
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/chosen/chosen.jquery.min.js');
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
// 

if (isset($this->userrole->rolefor)) {
	$is_guest = false;
    if ($this->userrole->rolefor != '') {
        if ($this->userrole->rolefor == 2){ // job seeker
            $allowed = true;
            $is_jobseeker = true;
        }elseif ($this->userrole->rolefor == 1) {// Employer
        	$is_jobseeker = false;
            if ($this->config['employerview_js_controlpanel'] == 1)
                $allowed = true;
            else
                $allowed = false;
        }
    }else {
        $allowed = true;
    }
} else{
    $allowed = true; // user not logined
    $is_guest = true;
}
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">

    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>

<?php
require_once('jobapply.php' );
require_once(JPATH_COMPONENT.'/views/job/jobslisting.php');
$obj = new jobslist();
?>
<div id="jsjobs-wrapper">

<?php
if($this->config['searchjobtag'] != 7){
// search popup link buttn

$location = 'left';
$borderradius = '0px 8px 8px 0px';
$padding = '5px 10px 5px 20px';
switch ($this->config['searchjobtag']) {
    case 1: // Top left
        $top = "30px";
        $left = "0px";
        $right = "none";
        $bottom = "none";
    break;
    case 2: // Top right
        $top = "30px";
        $left = "none";
        $right = "0px";
        $bottom = "none";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
    case 3: // middle left
        $top = "48%";
        $left = "0px";
        $right = "none";
        $bottom = "none";
    break;
    case 4: // middle right
        $top = "48%";
        $left = "none";
        $right = "0px";
        $bottom = "none";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
    case 5: // bottom left
        $top = "none";
        $left = "0px";
        $right = "none";
        $bottom = "30px";
    break;
    case 6: // bottom right
        $top = "none";
        $left = "none";
        $right = "0px";
        $bottom = "30px";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
}
$html = '<style type="text/css">
            div#refineSearch{opacity:0;position:fixed;top:'.$top.';left:'.$left.';right:'.$right.';bottom:'.$bottom.';padding:'.$padding.';background:rgba(149,149,149,.50);z-index:9999;border-radius:'.$borderradius.';}
            div#refineSearch img{margin-'.$location.':10px;display:inline-block;}
            div#refineSearch a{color:#ffffff;text-decoration:none;}
        </style>'; ?>

		<?php
	    $html .= '<div id="refineSearch">';
	    if($location == 'right'){
	        $html .= '<img src="'.Uri::root().'components/com_jsjobs/images/searchicon.png" /><a href="#">'.Text::_("Search").'</a>';
	    }else{
	        $html .= '<a href="#">'.Text::_("Search").'</a><img src="'.Uri::root().'components/com_jsjobs/images/searchicon.png" />';
	    }
	    $html .= '
	            </div>
	            <script type="text/javascript">
	                jQuery(document).ready(function(){
	                    jQuery("div#refineSearch").css("'.$location.'","-"+(jQuery("div#refineSearch a").width() + 25)+"px");
	                    jQuery("div#refineSearch").css("opacity",1);
	                    jQuery("div#refineSearch").hover(
	                        function(){
	                            jQuery(this).animate({'.$location.': "+="+(jQuery("div#refineSearch a").width() + 25)}, 1000);
	                        },
	                        function(){
	                            jQuery(this).animate({'.$location.': "-="+(jQuery("div#refineSearch a").width() + 25)}, 1000);
	                        }
	                    );
	                });
	            </script>';
	    echo $html;
	}
	    $heading = null;
	    if(isset($this->jobs_filters['company']) && is_numeric($this->jobs_filters['company'])){ 
            $heading=Text::_('Company Jobs');
    	}
    	if(isset($this->jobs_filters['category']) && is_numeric($this->jobs_filters['category'])){ 
        		$heading=Text::_('Jobs By Category');
    	}
    	if(isset($this->jobs_filters['jobsubcategory']) && is_numeric($this->jobs_filters['jobsubcategory'])){ 
            	$heading=Text::_('Jobs By SubCategory');
    	}
    	if(isset($this->jobs_filters['jobtype']) && is_numeric($this->jobs_filters['jobtype'])){ 
            	if(!$heading)
            		$heading=Text::_('Jobs By Types');
            	else
            		$heading=Text::_('Jobs');
    	}
    	if(!$heading) 	    
    		$heading=Text::_("Newest Jobs");

    	if(isset($this->issearchform) && $this->issearchform == 1){
        	$heading=Text::_('Search Result');
    	}elseif(isset($this->isfromsavesearch) && $this->isfromsavesearch == 1){
        	$heading=Text::_('Search Result');
    	} ?>


    	<div class="page_heading"><?php echo $heading; ?>
    	<?php
    	$showNumOfJobs = $this->getJSModel('configurations')->getConfigValue('showtotalnjobs');
    	if($showNumOfJobs){
    		echo '<div class="totaljobsheading">'.(Text::_("Total Jobs").': <span>'.$this->totalnumofjobs).'</span></div>';
    	}
    	?>
    	</div>
        <?php if ($this->config['cur_location'] == '1') { ?>
        	<div class="jsjobs-breadcrunbs-wrp js-breadcrunbs">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <?php
                                if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                                    $dlink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                }else{
                                    $dlink='index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
                                }
                            ?>
                            <a href="<?php echo $dlink; ?>" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_("Dashboard"); ?>
                            </a>
                        </li>
                        <?php if(isset($this->jobs_filters['jobsubcategory']) && is_numeric($this->jobs_filters['jobsubcategory'])){ ?>
    	                    <li>
    	                        <a href="index.php?option=com_jsjobs&c=category&view=category&layout=jobcat" title="<?php echo Text::_('Job Category'); ?>">
    	                            <?php echo Text::_('Job Category'); ?>
    	                        </a>
    	                    </li>
                    	<?php }
                    	/* if(isset($this->jobs_filters['company']) && is_numeric($this->jobs_filters['company'])){ ?>
                    	       	<li>
    	                        <a href="index.php?option=com_jsjobs&c=company&view=company&layout=listallcompanies" title="<?php echo Text::_('Companies'); ?>">
    	                            <?php echo Text::_('Companies'); ?>
    	                        </a>
    	                    </li>
    	                <?php } */
    	                ?>
                        <li>
                            <?php echo Text::_($heading); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
		<?php
			if ($this->config['offline'] == '1') {
			    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
			} else {
			    if ($allowed == true) {
			    	$search_job_showsave = $this->getJSModel('configurations')->getConfigValue('search_job_showsave');
			    	if ($search_job_showsave == 1) {
						if((!$is_guest) && $is_jobseeker){
							if(isset($this->issearchform) && $this->issearchform == 1){ ?>
								<div class="page_heading jsjobs-page-heading-wrp">
									<form action="index.php" class="jsjobs-form-search" method="post" name="adminForm" id="adminForm" onsubmit="return myValidate(this);">
										<input placeholder="<?php echo Text::_('Search Name'); ?>" type="text" class="inputbox required" name="searchname" value="" />
										
										<input type="submit" id="button" class="button validate" value="<?php echo Text::_('Save'); ?>">
										<?php
		                                    $jobs_filters = json_encode($this->jobs_filters);
		                                    $jobs_filters = JSModel::getJSModel('common')->b64ForEncode($jobs_filters);
										?>
										<input type="hidden" name="searchcriteria" value="<?php echo $jobs_filters; ?>"/>
	                                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	                                    <input type="hidden" name="task" value="savejobsearch" />
	                                    <input type="hidden" name="c" value="jobsearch" />
	                                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid;?>" />
									</form>
								</div>
								<?php 
							}
						}
					}

		        if (isset($this->jobs) && !empty($this->jobs)) {
			$jobshtml = $obj->printjobs($this->jobs , $this->config, $this->fieldsforview ,$this->Itemid);
			echo($jobshtml);
		?>
	</div>
		<form action="index.php" method="post">
            <div id="jsjobs_jobs_pagination_wrapper">
                <div class="jsjobs-resultscounter">
                    <?php echo $this->pagination->getResultsCounter(); ?>
                </div>
                <div class="jsjobs-plinks">
                    <?php //echo addExtraParamsToPaginationLinks($this->pagination, $extraParams); ?>
                    <?php echo $this->pagination->getPagesLinks(); ?>
                </div>
                <div class="jsjobs-lbox">
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            </div>
        </form>
        <?php
        } else { // no result found in this category 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
        }
    } else { // not allowed job posting
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
    }
}
?>
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
<!-- Search Popup -->

<div id="jsjob-popup-background"></div>
<div id="jsjobs-listpopup">
	<span class="popup-title"><span class="title"></span><img id="popup_cross" src="<?php echo Uri::root();?>components/com_jsjobs/images/popup-close.png"></span>
	<div class="jsjob-contentarea"></div>
</div>
<div id="jsjob-search-popup">
	<span class="popup-title"><?php echo Text::_('Refine Search');?><img id="popup_cross" src="<?php echo Uri::root();?>components/com_jsjobs/images/popup-close.png"></span>

	<?php $slink = Uri::root()."index.php?option=$this->option&c=job&view=job&layout=jobs&Itemid=$this->Itemid"; ?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="job_form">
		<div class="jsjob-contentarea">
			<?php 
			function getRow($title,$value,$i,$style=''){
		        $html='';
		        if($i==9) $html .='<div id="jsjobs-hide">';
		        if(($i%2)!=0) $html .='<div class="jsjobs-searchwrapper">';
		        $html .= '<div class="js-col-md-6 jsjob-refine-wrapper">
		                    <div class="js-col-md-12 js-searchform-title">'.$title.'</div>
		                    <div class="js-col-md-12 js-searchform-value '.$style.'">'.$value.'</div>
		                </div>';
		        if(($i%2)==0)
		        	$html .='</div>';
		        return $html;
			}
			$i = 0;
			$hidemapscript = false;
			$customfieldobj = getCustomFieldClass();
			$fieldsordering = $this->getJSModel('fieldsordering')->getFieldsOrderingForSearchByFieldFor(2);
		    foreach($fieldsordering AS $field){
		    	$title = Text::_($field->fieldtitle);
		        switch ($field->field) {
		            case 'metakeywords':
		                $value = '<input type="text" name="metakeywords" class="inputbox" id="metakeywords" value="'.$this->jobs_filters['metakeywords'].'" />';
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobtitle':
						$value = '<input type="text" name="jobtitle" class="inputbox" id="jobtitle" value="'.$this->jobs_filters['jobtitle'].'" />';
		                echo getRow($title,$value,++$i);
		            break;
		            case 'company':
		                $value = $this->search_combo['company'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobcategory':
		                $value = $this->search_combo['category'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'subcategory':
		                $value = $this->search_combo['jobsubcategory'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'careerlevel':
		                $value = $this->search_combo['careerlevel'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobshift':
		                $value = $this->search_combo['shift'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'gender':
		                $value = $this->search_combo['gender'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobtype':
		                $value = $this->search_combo['jobtype'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobstatus':
		                $value = $this->search_combo['jobstatus'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'zipcode':
		                $value = '<input type="text" name="zipcode" class="inputbox" id="zipcode" value="'.$this->jobs_filters['zipcode'].'" />';
		                echo getRow($title,$value,++$i);
		            break;
		            case 'startpublishing':
		                $value = HTMLHelper::_('calendar', $this->jobs_filters['startpublishing'], 'startpublishing', 'startpublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19'));
		                echo getRow($title,$value,++$i);
		            break;
		            case 'stoppublishing':
		                $value = HTMLHelper::_('calendar', $this->jobs_filters['stoppublishing'], 'stoppublishing', 'stoppublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19'));
		                echo getRow($title,$value,++$i);
		            break;
		            case 'workpermit':
		                $value = $this->search_combo['workpermit'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'jobsalaryrange':
		                $value = $this->search_combo['currencyid'];
		                $value .= $this->search_combo['srangestart'];
		                $value .= $this->search_combo['srangeend'];
		                $value .= $this->search_combo['srangetype'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'heighesteducation':
		                $value = $this->search_combo['education'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'experience':
		                $value = $this->search_combo['experiencemin'];
		                $value .= $this->search_combo['experiencemax'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'city':
		                $value = '<input type="text" name="city" class="inputbox" id="city" value="" />';
		                echo getRow($title,$value,++$i);
		            break;
		            case 'requiredtravel':
		                $value = $this->search_combo['requiredtravel'];
		                echo getRow($title,$value,++$i);
		            break;
		            case 'duration':
		                $value = '<input type="text" name="duration" class="inputbox" id="duration" value="'.$this->jobs_filters['duration'].'" />';
		                echo getRow($title,$value,++$i);
		            break;
		            case 'map':
						$hidemapscript = true;
						 ?>
		            	<div class="jsjobs-searchwrapper">
		            		<div class="js-col-md-12 jsjob-refine-wrapper">
		                    	<div class="js-col-md-12 js-searchform-title"><?php echo $title; ?></div>
		                    	<div class="js-col-md-12 js-searchform-value"><div id="map_container"><div id="map"></div></div></div>
		                	</div>
		                </div>
		                <?php
		                $title = Text::_('Latitude');
		                $value = '<input type="text" name="latitude" class="inputbox" id="latitude" value="'.$this->jobs_filters['latitude'].'" />';
		                echo getRow($title,$value,++$i);
		                $title = Text::_('Longitude');
		                $value = '<input type="text" name="longitude" class="inputbox" id="longitude" value="'.$this->jobs_filters['longitude'].'" />';
		                echo getRow($title,$value,++$i);
		                $title = Text::_('Radius');
		                $value = '<input type="text" name="radius" class="inputbox" id="radius" value="'.$this->jobs_filters['radius'].'" />';
		                echo getRow($title,$value,++$i);
		                $title = Text::_('Radius Length Type');
		                $value = $this->search_combo['radiuslengthtype'];
		                echo getRow($title,$value,++$i);
		            break;
		           	default:
	                    $params = null;
	                    $style='';
	                    if($field->userfieldtype == 'checkbox' || $field->userfieldtype == 'radio'){
	                    	$style='js-searchform-customfield-wrp';	
	                    }
	                    if($field->userfieldtype == 'multiple'){
	                    	$style='js-searchform-multiselect';	
	                    }
	                    if(isset($this->jobs_filters['params'])){
	                        $params = $this->jobs_filters['params'];
	                    }
	                    $k = 1;
	                    $f_res = $customfieldobj->formCustomFieldsForSearch($field, $k, $params , 1,$style);
	                    if($f_res){
			                echo getRow($f_res['title'],$f_res['field'],++$i,$style);
	                    }
	                break;

		        }
		    }
		    if((($i % 2) != 0) ){
				echo '</div>';
		    }
		    if($i >= 9 ){
				echo '</div>';
			} 
			if($i > 8){ ?> 
				<div class="jsjobs-searchwrapper">
		    		<div class="js-col-md-12 jsjob-refine-wrapper jsjob-refine-wrapper-center-alignment">
		    			<div class="js-col-md-12" id="jsjobs-showmore"><?php echo Text::_('Show More');?></div>
					</div>
				</div>
			<?php } ?>
			<div id="jsjobs-refine-actions">
		        <div class="js-col-md-12 bottombutton js-form">
		        	<button class="search_button" id="submit_btn" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button>
	        		<button class="search_button" id="reset_btn" onclick="resetpopupform();"><?php echo Text::_('Reset'); ?></button>
		        </div>
			</div>
		</div>
		
		<input type="hidden" name="option" value="<?php echo $this->option;?>">
		<input type="hidden" name="c" value="job">
		<input type="hidden" name="view" value="job">
		<input type="hidden" name="layout" value="jobs">
		<input type="hidden" name="Itemid" value="<?php echo $this->Itemid;?>">

	</form>
</div>

<style type="text/css">
	div#jsjobs-hide{display: none;width: 100%;}
    div#map_container{height: 300px;}
    div#map_container_quickview{height: 300px;}
</style>
<?php
if($hidemapscript){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; 
        $default_longitude = $this->config['default_longitude'];
        $default_latitude = $this->config['default_latitude'];

    ?>
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<?php } ?>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
	    $(".jsjob-multiselect").chosen({
	        placeholder_text_multiple : "<?php echo Text::_('Select Some Options'); ?>"
	    });
	    $("div#refineSearch").click(function() {
	    	showPopup();
		});

		$("div#jsjob-popup-background,img#popup_cross").click(function() {
	    	closePopup();
		});
	    
	    getTokenInput();
	    
	    <?php 
	    if($hidemapscript){ ?>
	   		loadMap();
	    <?php
	    } ?>


	    $("div#jsjobs-showmore").click(function() {
  			$("div#jsjobs-showmore").hide();
  			$("div#jsjobs-hide").css("display","block");
		});


        $("select#category").chosen().change(function (){
            var category = $("select#category").val();
            if(category){
                jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=subcategoriesForSearch', {val: category}, function (data) {
                    if (data) {
                        jQuery("select#jobsubcategory").html(data);
                        $("select#jobsubcategory").trigger("chosen:updated");
                    }
                });
            }
        });
        /*
		var no=1;
		jQuery(window).scroll(function () {
		    if(no==1)
		    {
		        if (jQuery(window).scrollTop() > jQuery(document).height() / 2) {
		            
					var scrolltask = jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr("data-scrolltask");
					var offset = jQuery("div#jsjobs-wrapper").find("a.scrolltask").attr("data-offset");
					if(scrolltask != null && scrolltask != '' && scrolltask != "undefined"){
						jQuery("div#jsjobs-wrapper").find("a.scrolltask").remove();
						var s_ajaxurl = "index.php?option=com_jsjobs&task=job.getnextjobs&pagenum=" + offset;
						var jobtype = '<?php echo Factory::getApplication()->input->get('jt'); ?>';
						var category = '<?php echo Factory::getApplication()->input->get('cat'); ?>';
						var company = '<?php echo Factory::getApplication()->input->get('cd'); ?>';
						jQuery.get(s_ajaxurl,{jt:jobtype,cat:category,cd:company},function(data){
							jQuery("div#jsjobs-wrapper").append(data);
						});
					}
		        }
		    }
		});
		*/
	});

	function closePopup(){
		jQuery("div#jsjob-search-popup,div#jsjobs-listpopup").slideUp('slow');
	    setTimeout(function () {
	        jQuery("div#jsjob-popup-background").hide();
	    }, 700);
	}

	function showPopup(){
        jQuery("div#jsjob-popup-background").show();
		jQuery("div#jsjob-search-popup").slideDown('slow');
	}

	function loadMap1(latitude,longitude) {
	    var latlng = new google.maps.LatLng(latitude, longitude);
	    zoom = 6;
	    var myOptions = {
	        zoom: zoom,
	        center: latlng,
	        scrollwheel: false,
	        mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	    var map = new google.maps.Map(document.getElementById("map_container_quickview"), myOptions);
	    var lastmarker = new google.maps.Marker({
	        postiion: latlng,
	        map: map,

	    });
	    var marker = new google.maps.Marker({
	        position: latlng,
	        map: map,

	    });
	    marker.setMap(map);
   	}

   	<?php 
   	if($hidemapscript){ ?> 
		function loadMap() {

		var maincheck = <?php echo $this->searchjobconfig["search_job_coordinates"]; ?>;
		if(maincheck == 1){ 

		    var default_latitude = '<?php echo $default_latitude; ?>';
		    var default_longitude = '<?php echo $default_longitude; ?>';
		    if(jQuery('input#latitude').length > 0 && jQuery('input#longitude').length > 0){
		    	var latitude = document.getElementById('latitude').value;
		    	var longitude = document.getElementById('longitude').value;
		    }else{
		    	var latitude = '';
		    	var longitude = '';
		    }

		    if (latitude != '' && longitude != '') {
		        default_latitude = latitude;
		        default_longitude = longitude;
		    }
		    var latlng = new google.maps.LatLng(default_latitude, default_longitude);
		    zoom = 10;
		    var myOptions = {
		        zoom: zoom,
		        center: latlng,
		        scrollwheel: false,
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
		    var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
		    var lastmarker = new google.maps.Marker({
		        postiion: latlng,
		        map: map,

		    });
		    /*var marker = new google.maps.Marker({
		        position: latlng,
		        map: map,

		    });
		    marker.setMap(map);
		    lastmarker = marker;
		    document.getElementById('latitude').value = marker.position.lat();
		    document.getElementById('longitude').value = marker.position.lng();
		    */
		    lastmarker = '';

		    google.maps.event.addListener(map, "click", function (e) {
		        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
		        geocoder = new google.maps.Geocoder();
		        geocoder.geocode({'latLng': latLng}, function (results, status) {
		            if (status == google.maps.GeocoderStatus.OK) {
		                if (lastmarker != '')
		                    lastmarker.setMap(null);
		                var marker = new google.maps.Marker({
		                    position: results[0].geometry.location,
		                    map: map,

		                });
		                marker.setMap(map);
		                lastmarker = marker;
		                document.getElementById('latitude').value = marker.position.lat();
		                document.getElementById('longitude').value = marker.position.lng();

		            } else {
		                alert("Geocode was not successful for the following reason: " + status);
		            }
		        });
	    	});
	    }
   		}
   	<?php
   }
   	?>

    function checkmapcooridnate() {
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;
        var radius = document.getElementById('radius').value;
        if (latitude != '' && longitude != '') {
            if (radius != '') {
                this.form.submit();
            } else {
                alert("<?php echo Text::_("Please enter the coordinate radius","js-jobs"); ?>");
                return false;
            }
        }
    }

    //token input
    function getTokenInput() {
        var cityArray = '<?php echo Uri::root()."index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>';
        
        jQuery("#city").tokenInput(cityArray, {
            theme: "jsjobs",
            preventDuplicates: true,
            hintText: "<?php echo Text::_('Type In A Search Term'); ?>",
            noResultsText: "<?php echo Text::_('No Results'); ?>",
            searchingText: "<?php echo Text::_('Searching'); ?>",
            prePopulate: <?php if(empty($this->multicities))  echo "''"; else echo $this->multicities;?>
        });
    }
	
	//Reset Search Form
	function resetpopupform(){

        var form = jQuery('form#adminForm');
        form.find("input[type=text], input[type=email], input[type=password], textarea").val("");
        form.find('input:checkbox').removeAttr('checked');
        form.find('select').prop('selectedIndex', 0);
        form.find('input[type="radio"]').prop('checked', false);

		jQuery(".jsjob-multiselect").val('').trigger('chosen:updated');
		jQuery('input#city').tokenInput("clear");

		jQuery(form).append('<input type="hidden" name="popresetbtn" value="true">');
	}
</script>

<div id="js_jobs_main_popup_back"></div>
<div id="js_jobs_main_popup_area">
	<div id="js_jobs_main_popup_head">
		<div id="jspopup_title"><?php echo Text::_('Apply Now');?></div>
		<img id="jspopup_image_close" src="<?php echo Uri::root();?>components/com_jsjobs/images/popup-close.png" />
	</div>
	<div id="jspopup_work_area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("img#jspopup_image_close,div#js_jobs_main_popup_back").click(function(){
			jQuery("div#js_jobs_main_popup_area").slideUp('slow');
		    setTimeout(function () {
		        jQuery("div#js_jobs_main_popup_back").hide();
		        jQuery("div#jspopup_work_area").html('');
		    }, 700);
		});
	});
    jQuery("body").delegate('a[href="#"]', "click", function (e) {
        e.preventDefault();
    });

</script>
