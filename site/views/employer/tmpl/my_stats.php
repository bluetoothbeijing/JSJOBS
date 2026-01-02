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
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}


$document->addScript('components/com_jsjobs/js/responsivetable.js');

?>
<div id="js_jobs_main_wrapper">
    <div id="js_menu_wrapper">
        <?php
        if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
            foreach ($this->jobseekerlinks as $lnk) {
                ?>                     
                <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
            foreach ($this->employerlinks as $lnk) {
                ?>
                <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
  <div id="jsjobs-main-wrapper">
      <span class="jsjobs-main-page-title"><?php echo Text::_('Stats'); ?></span>
      <?php if ($this->config['cur_location'] == '1') { ?>
        <div class="jsjobs-breadcrunbs-wrp">
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
                      <li>
                          <?php echo Text::_('Stats'); ?>
                      </li>
                  </ul>
              </div>
        </div>
      <?php } ?>
  <?php
    if ($this->mystats_allowed == VALIDATE) { // employer
        $isodd = 0;
        ?>
            <?php
            if ($this->ispackagerequired != 1) {
                $message = "<strong>" . Text::_('Package Not Required') . "</strong>";
                ?>
                <div id="stats-package-message">
                    <img class="package-massage-img" src="<?php echo Uri::root();?>components/com_jsjobs/images/icon-massage-stats.png"> <?php echo $message; ?>
                </div>

                <?php
            } ?>
            <span class="jsjobs-stats-title"><?php echo Text::_('My Stats'); ?></span>
            <div class="jsjobs-listing-stats-wrapper">
                <div class="jsjobs-icon-wrap">
                   <img class="jsjobs-img" src="<?php echo Uri::root();?>components/com_jsjobs/images/statsicon/company.png">
                   <span class="stats-data-value"><?php echo $this->totalcompanies; ?></span>
                   <span class="stats-data-title"><?php echo Text::_('Companies'); ?></span>
                </div>
                <div class="jsjobs-icon-wrap">
                    <img class="jsjobs-img" src="<?php echo Uri::root();?>components/com_jsjobs/images/statsicon/jobs.png">
                    <span class="stats-jobs-value"><?php echo $this->totaljobs; ?></span>
                    <span class="stats-data-title"><?php echo Text::_('Jobs'); ?></span>
                </div>
            </div>            
            <div class="jsjobs-listing-stats-wrapper">
               <table id="js-table">
                   <thead>
                       <th>
                           <?php echo Text::_('Jobs'); ?>
                       </th>
                       <th class="center">
                           <?php echo Text::_('Allow'); ?>
                       </th>
                       <th class="center">
                           <?php echo Text::_('Published'); ?>
                       </th>
                       <th class="center">
                           <?php echo Text::_('Expired'); ?>
                       </th>
                       <th class="center">
                           <?php echo Text::_('Available'); ?>
                       </th>
                       <tbody>
                           <tr class="bodercolor5_rs">
                               <td class="color3 bodercolor5">
                                  <?php echo Text::_('Jobs'); ?> 
                               </td>
                               <td class="center color">
                               <?php
                                    if ($this->ispackagerequired != 1) {
                                        echo Text::_('Unlimited');
                                    } elseif ($this->jobsallow == -1) {
                                        echo Text::_('Unlimited');
                                    } else
                                        echo $this->jobsallow;
                                ?>
                                </td>
                               <td class="center color2">
                                     <?php echo $this->publishedjob; ?>
                               </td>
                               <td class="center color4">
                                     <?php echo $this->expiredjob; ?>
                               </td>
                               <td class="center color5">
                                    <?php
                                        if ($this->ispackagerequired != 1) {
                                            echo Text::_('Unlimited');
                                        } elseif ($this->jobsallow == -1) {
                                            echo Text::_('Unlimited');
                                        } else {
                                            $available_jobs = $this->jobsallow - $this->totaljobs;
                                            echo $available_jobs;
                                        }
                                    ?> 
                               </td>
                           </tr>
                       </tbody>
                   </thead>
               </table>            
            </div>            
            <div class="jsjobs-listing-stats-wrapper">
               <table id="js-table" class="second">
                  <thead>
                      <th>
                         <?php echo Text::_('Companies'); ?> 
                      </th>
                      <th class="center">
                          <?php echo Text::_('Allow'); ?>
                      </th>
                      <th class="center">
                          <?php echo Text::_('Published'); ?>
                      </th>
                      <th class="center">
                          <?php echo Text::_('Expired'); ?>
                      </th>
                      <th class="center">
                          <?php echo Text::_('Available'); ?>
                      </th>
                  </thead>
                  <tbody>
                      <tr class="bodercolor5_rs">
                          <td  class="color3 bodercolor5"><?php echo Text::_('Companies'); ?></td>
                          <td class="center color">
                               <?php
                                if ($this->ispackagerequired != 1) {
                                    echo Text::_('Unlimited');
                                } elseif ($this->companiesallow == -1) {
                                    echo Text::_('Unlimited');
                                } else
                                    echo $this->companiesallow;
                                ?>
                          </td>
                          <td class="center color2">
                              <?php echo $this->totalcompanies; ?>
                          </td>
                          <td class="center color4">
                               <?php echo '0'; ?>
                          </td>
                          <td class="center color5">
                              <?php
                                if ($this->ispackagerequired != 1) {
                                    echo Text::_('Unlimited');
                                } elseif ($this->companiesallow == -1) {
                                    echo Text::_('Unlimited');
                                } else {
                                    $available_companies = $this->companiesallow - $this->totalcompanies;
                                    echo $available_companies;
                                }
                              ?> 
                          </td>
                      </tr>
                  </tbody>
               </table> 
            </div>
        <?php
        } 
        ?>
        </div>
        <?php

        switch ($this->mystats_allowed) {
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                break;
        }
    }//ol
    ?>	
<div id="jsjobsfooter">
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
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
  // responsive tables
  var headertext = [];
  headers = document.querySelectorAll("#js-table.second th");
  tablerows = document.querySelectorAll("#js-table.second th");
  tablebody = document.querySelector("#js-table.second tbody");
  for(var i = 0; i < headers.length; i++) {
    var current = headers[i];
    headertext.push(current.textContent.replace(/\r?\n|\r/,""));
  } 
  for (var i = 0; row = tablebody.rows[i]; i++) {
    for (var j = 0; col = row.cells[j]; j++) {
      col.setAttribute("data-th", headertext[j]);
    } 
  }
});  
</script>
