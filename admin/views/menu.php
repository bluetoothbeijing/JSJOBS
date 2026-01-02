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
use Joomla\CMS\Language\Text;

    $ff = Factory::getApplication()->input->get('ff', 0);
    if ($ff == 0) $ff = Factory::getApplication()->input->get('fieldfor', 0);
    $c = Factory::getApplication()->input->get('c');
    $layout = Factory::getApplication()->input->get('layout');
    $ff = Factory::getApplication()->input->get('ff');
    $tf = Factory::getApplication()->input->get('tf');
    $task = Factory::getApplication()->input->get('task');
?>

<div id="jsstadmin-logo">
    <a title="<?php echo Text::_('JS JObs'); ?>" class="jsst-anchor" href="">
        <img alt="<?php echo Text::_('Logo'); ?>" src="components/com_jsjobs/include/images/left_menu_icons/logo.png" />
    </a>
    <img id="jsstadmin-menu-toggle" src="components/com_jsjobs/include/images/left_menu_icons/menu.png" />
</div>
<ul id="js-tk-links" class="tree"  data-widget="tree">
    <li class="js-divlink treeview <?php if($c == 'jsjobs' || $c =='jobtype' || $c =='jobstatus' || $c =='shift' || $c =='highesteducation' || $c =='age' || $c =='careerlevel' || $c =='experience' || $layout =='info' || $layout =='stopone' || $layout =='translation' || $c =='currency') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel"  title="<?php echo Text::_('Admin'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/admin.png" class="jsst_menu-icon" alt="<?php echo Text::_('Admin'); ?>"  />
            <span class="jsst_text"><?php echo Text::_('Admin'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'controlpanel') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel">
                    <?php echo Text::_('Control Panel'); ?></span>
                </a>
            </li>
            <li class="<?php if($layout == 'stepone' || $layout == 'steptwo' || $layout == 'stepthree') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=stepone">
                    <?php echo Text::_('JS Jobs Update'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'jobtype') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=jobtypes">
                    <?php echo Text::_('Job Types'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'jobstatus') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=jobstatus">
                    <?php echo Text::_('Job Status'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'shift') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=shift&view=shift&layout=shifts">
                    <?php echo Text::_('Shifts'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'highesteducation') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=highesteducations">
                    <?php echo Text::_('Highest Education'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'age') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=age&view=age&layout=ages">
                    <?php echo Text::_('Ages'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'careerlevel') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=careerlevels">
                    <?php echo Text::_('Career Levels'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'experience') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=experience&view=experience&layout=experience">
                    <?php echo Text::_('Experience'); ?>
                </a>
            </li>
            <?php /*
            <li class="<?php if($layout == 'info') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=info">
                    <?php echo Text::_('Information'); ?>
                </a>
            </li>
            */?>
            <li class="<?php if($c == 'currency') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=currency&view=currency&layout=currency">
                    <?php echo Text::_('Currencies'); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php /*
    <div class="js-divlink">
        <a href="index.php?option=com_jsjobs&c=jobsharing&view=jobsharing&layout=jobshare">
            <img src="components/com_jsjobs/include/images/left_menu_icons/shearing.png" />
        </a>
        <a href="#" class="js-parent <?php if($c == 'jobsharing') echo 'lastshown'; ?>"><span class="text"><?php echo Text::_('Sharing Service'); ?><img class="arrow" src="components/com_jsjobs/include/images/left_menu_icons/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jsjobs&c=jobsharing&view=jobsharing&layout=jobshare"><span class="text"> <?php echo Text::_('Job Sharing'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jsjobs&c=jobsharing&view=jobsharing&layout=jobsharelog"><span class="text"> <?php echo Text::_('Job Share Log'); ?></span></a>
        </div>
    </div>
    */  ?>
    <li class="js-divlink treeview <?php if($c == 'configuration' || $c == 'paymentmethodconfiguration') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations" title="<?php echo Text::_('Configurations'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/configration.png" class="jsst_menu-icon" alt="<?php echo Text::_('Configurations'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Configurations'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'configurations') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations">
                    <?php echo Text::_('Configurations'); ?>
                </a>
            </li>
            <?php /* <li class="<?php if($layout == 'configurationsemployer') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurationsemployer">
                    <?php echo Text::_('Employer'); ?>
                </a>
            </li> */ ?>
            <?php /* <li class="<?php if($layout == 'configurationsjobseeker') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurationsjobseeker">
                    <?php echo Text::_('Job Seeker'); ?>
                </a>
            </li> */ ?>
            <li class="<?php if($layout == 'themes') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Themes'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li>
            <?php /* <li class="<?php if($layout == 'cronjob') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Cron Job'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li>
            <li class="<?php if($layout == 'paymentmethodconfig') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Payment Methods'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li> */ ?>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'company'  || $ff == '1') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=company&view=company&layout=companies" title="<?php echo Text::_('Companies'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/company.png" class="jsst_menu-icon" alt="<?php echo Text::_('Companies'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Companies'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'companies' || $layout == 'formcompany') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=company&view=company&layout=companies">
                    <?php echo Text::_('Companies'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'companiesqueue') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=company&view=company&layout=companiesqueue">
                    <?php echo Text::_('Approval Queue'); ?>
                </a>
            </li>
            <li class="<?php if($ff == '1') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=fieldsordering&ff=1">
                    <?php echo Text::_('Fields'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'companiesexport') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Export').' '.Text::_('Companies'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'job' || $ff == '2') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" title="<?php echo Text::_('Jobs'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/jobs.png" class="jsst_menu-icon" alt="<?php echo Text::_('Jobs'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Jobs'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'jobs' || $layout =='formjob') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs">
                    <?php echo Text::_('Jobs'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'jobqueue') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobqueue">
                    <?php echo Text::_('Approval Queue'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'fieldsordering' && $ff == '2') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=fieldsordering&ff=2">
                    <?php echo Text::_('Fields'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'searchfields') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=searchfields&ff=2">
                    <?php echo Text::_('Search').' '.Text::_('Fields'); ?>
                </a>
            </li>
            <li class="<?php if( $layout == 'jobalert' || $task =='jobalert.editjobalert') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Job Alert'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li>
            <li class="<?php if($layout == 'jobsexport') echo 'active'; ?>">
                <a class="js-child disable-child" href="javascript:void(0)">
                    <?php echo Text::_('Export').' '.Text::_('Jobs'); ?>
                    <img src="components/com_jsjobs/include/images/icon/pro-icon.png">
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'resume' || $ff == '3') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps" title="<?php echo Text::_('Resume'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/resume.png" class="jsst_menu-icon" alt="<?php echo Text::_('Resume'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Resume'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'empapps' || $layout == 'formresume') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps">
                    <?php echo Text::_('Resume'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'appqueue') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=appqueue">
                    <?php echo Text::_('Approval Queue'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'fieldsordering' || $layout == 'formuserfield') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=fieldsordering&ff=3">
                    <?php echo Text::_('Fields'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'searchfields') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=searchfields&ff=3">
                    <?php echo Text::_('Search').' '.Text::_('Fields'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'department' || $c == 'systemerror') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=department&view=department&layout=departments" title="<?php echo Text::_('Departments'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/departments.png" class="jsst_menu-icon" alt="<?php echo Text::_('Departments'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Departments'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'departments' ||$layout == 'formdepartment' || $task == 'department.edit') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=department&view=department&layout=departments">
                    <?php echo Text::_('Departments'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'departmentqueue') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=department&view=department&layout=departmentqueue">
                    <?php echo Text::_('Approval Queue'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'gdpr') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=erasedatarequests" title="<?php echo Text::_('GDPR'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/gdpr.png" class="jsst_menu-icon" alt="<?php echo Text::_('GDPR'); ?>" />        
            <span class="jsst_text"><?php echo Text::_('GDPR'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'gdprfields' || $layout == 'addgdprfield') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=gdprfields">
                    <?php echo Text::_('GDPR Fields'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'erasedatarequests') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=erasedatarequests">
                    <?php echo Text::_('Erase Data Requests'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'premiumplugin') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=premiumplugin&view=premiumplugin&layout=premiumplugins" title="<?php echo Text::_('Premium Plugins'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/premium.png" class="jsst_menu-icon" alt="<?php echo Text::_('Premium Plugins'); ?>" />        
            <span class="jsst_text"><?php echo Text::_('Premium Plugins'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'premiumplugins') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=premiumplugin&view=premiumplugin&layout=premiumplugins">
                    <?php echo Text::_('Premium Plugins'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'employerpackages' || $c =='jobseekerpackages') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages" title="<?php echo Text::_('Packages'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/packages.png" class="jsst_menu-icon" alt="<?php echo Text::_('Packages'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Packages'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($c == 'employerpackages') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages">
                    <?php echo Text::_('Employer Packages'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'jobseekerpackages') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=jobseekerpackages">
                    <?php echo Text::_('Job Seeker Packages'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'paymenthistory' || $layout =='userstate_companies' || $layout =='userstate_resumes') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory" title="<?php echo Text::_('Payments'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/payment.png" class="jsst_menu-icon" alt="<?php echo Text::_('Payments'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Payments'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'employerpaymenthistory' || $layout =='employerpaymentdetails') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory">
                    <?php echo Text::_('Employer History'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'jobseekerpaymenthistory' || $layout == 'jobseekerpaymentdetails') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory">
                    <?php echo Text::_('Job Seeker History'); ?>
                </a>
            </li>
            <li class="<?php if($layout == 'payment_report' || $layout == 'userstate_companies' || $layout =='userstate_resumes') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=payment_report">
                    <?php echo Text::_('Payment Report'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink disabled-menu">
        <a href="#" title="<?php echo Text::_('Messages'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/grey/messages.png" class="jsst_menu-icon" alt="<?php echo Text::_('Messages'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Messages'); ?></span>
        </a>
        <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=proversion" class="jsst_menu-pro-btn" title="Pro Version"><?php echo Text::_('Pro Version'); ?></a>
    </li>
    <li class="js-divlink disabled-menu">
        <a href="#" title="<?php echo Text::_('Folders'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/grey/folder.png" class="jsst_menu-icon" alt="<?php echo Text::_('Folders'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Folders'); ?></span>
        </a>
        <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=proversion" class="jsst_menu-pro-btn" title="Pro Version"><?php echo Text::_('Pro Version'); ?></a>
    </li>
    <li class="js-divlink treeview <?php if($c == 'category' || $c =='subcategory') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=category&view=category&layout=categories" title="<?php echo Text::_('Categories'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/category.png" class="jsst_menu-icon" alt="<?php echo Text::_('Categories'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Categories'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($c == 'category' || $layout == 'subcategories' || $task =='subcategory.editsubcategories') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=category&view=category&layout=categories">
                    <?php echo Text::_('Categories'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'salaryrange' || $c =='salaryrangetype') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange" title="<?php echo Text::_('Salary Range'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/salary.png" class="jsst_menu-icon" alt="<?php echo Text::_('Salary Range'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Salary Range'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($task == 'salaryrange.editjobsalaryrange' || $layout == 'salaryrange') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange">
                    <?php echo Text::_('Salary Range'); ?>
                </a>
            </li>
            <li class="<?php if($task == 'salaryrangetype.editjobsalaryrangrtype' || $layout == 'salaryrangetype') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=salaryrangetype">
                    <?php echo Text::_('Salary Range Type'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'userrole') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users" title="<?php echo Text::_('User Roles'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/role.png" class="jsst_menu-icon" alt="<?php echo Text::_('User Roles'); ?>" />
            <span class="jsst_text"><?php echo Text::_('User Roles'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($c == 'userrole') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users">
                    <?php echo Text::_('Users'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'emailtemplate') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-cm" title="<?php echo Text::_('Email Templates'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/email_tempeltes.png" class="jsst_menu-icon" alt="<?php echo Text::_('Email Templates'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Email Templates'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'emailtemplateoptions') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplateoptions">
                    <?php echo Text::_('Options'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-cm') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-cm">
                    <?php echo Text::_('New Company'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'cm-ap') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-ap">
                    <?php echo Text::_('Company Approval'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'cm-rj') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-rj">
                    <?php echo Text::_('Company Rejection'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'cm-dl') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-dl">
                    <?php echo Text::_('Company').' '.Text::_('Delete'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-ob') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-ob">
                    <?php echo Text::_('New Job').'('.Text::_('Admin').')'; ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-ob-em') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-ob-em">
                    <?php echo Text::_('New Job').'('.Text::_('Employer').')'; ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ob-ap') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-ap">
                    <?php echo Text::_('Job Approval'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ob-rj') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-rj">
                    <?php echo Text::_('Job Rejecting'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ob-dl') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-dl">
                    <?php echo Text::_('Job Delete'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ap-rs') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ap-rs">
                    <?php echo Text::_('Applied Resume Status'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-rm') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rm">
                    <?php echo Text::_('New Resume'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-rm-vis') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rm-vis">
                    <?php echo Text::_('New Resume').' '.Text::_('Visitor'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'rm-ap') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-ap">
                    <?php echo Text::_('Resume Approval'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'rm-rj') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-rj">
                    <?php echo Text::_('Resume Rejecting'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'rm-dl') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-dl">
                    <?php echo Text::_('Resume').' '.Text::_('Delete'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ba-ja') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ba-ja">
                    <?php echo Text::_('Job Apply'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'js-ja') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=js-ja">
                    <?php echo Text::_('Job Apply').' '.Text::_('Jobseeker'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-md') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-md">
                    <?php echo Text::_('New Department'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-rp') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rp">
                    <?php echo Text::_('Employer Purchase'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ew-js') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-js">
                    <?php echo Text::_('Job Seeker Purchase'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'ms-sy') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ms-sy">
                    <?php echo Text::_('Message'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'jb-at') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-at">
                    <?php echo Text::_('Job Alert'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'jb-at-vis') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-at-vis">
                    <?php echo Text::_('Employer').'('.Text::_('Visitor').')'.Text::_('Job'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'jb-to-fri') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-to-fri">
                    <?php echo Text::_('Job To Friend'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'jb-pkg-pur') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-pkg-pur">
                    <?php echo Text::_('Job Seeker Package Purchased'); ?>
                </a>
            </li>
            <li class="<?php if($tf == 'emp-pkg-pur') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=emp-pkg-pur">
                    <?php echo Text::_('Employer Package Purchased'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'country' || $c == 'addressdata' || $c =='state' || $c =='city') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=country&view=country&layout=countries" title="<?php echo Text::_('Countries'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/country.png" class="jsst_menu-icon" alt="<?php echo Text::_('Countries'); ?>" />   
            <span class="jsst_text"><?php echo Text::_('Countries'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($c == 'country' || $c =='state' || $c =='city') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=country&view=country&layout=countries">
                    <?php echo Text::_('Countries'); ?>
                </a>
            </li>
            <li class="<?php if($c == 'addressdata') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=addressdata&view=addressdata&layout=loadaddressdata">
                    <?php echo Text::_('Load Address Data'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($layout == 'translation') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=translation" title="<?php echo Text::_('Translations'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/language-icon.png" class="jsst_menu-icon" alt="<?php echo Text::_('User Roles'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Translations'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($layout == 'translation') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=translation">
                    <?php echo Text::_('Translations'); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="js-divlink treeview <?php if($c == 'help') echo 'active'; ?>">
        <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=help" title="<?php echo Text::_('Help'); ?>">
            <img src="components/com_jsjobs/include/images/left_menu_icons/help.png" class="jsst_menu-icon" alt="<?php echo Text::_('User Roles'); ?>" />
            <span class="jsst_text"><?php echo Text::_('Help'); ?></span>
        </a>
        <ul class="js-innerlink treeview-menu">
            <li class="<?php if($c == 'userrole') echo 'active'; ?>">
                <a class="js-child" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=help">
                    <?php echo Text::_('Help'); ?>
                </a>
            </li>
        </ul>
    </li>
    
</ul>

<script type="text/javascript">

    jQuery(document).ready(function(){
        var cookielist = document.cookie.split(';'); 
        for (var i=0; i<cookielist.length; i++) {
            if (cookielist[i].trim() == "jsst_collapse_admin_menu=1") { 
                jQuery("div#jsjobsfooter").addClass('menu-collasped-active-footer');
                jQuery("div#jsjobs-footer").addClass('menu-collasped-active-footer');
                jQuery("#jsjobs-wrapper").addClass("menu-collasped-active");
                break;
            }
        }
    });
    jQuery(document).ready(function(){
        jQuery("img#js-admin-responsive-menu-link").click(function(e){
            e.preventDefault();
            if(jQuery("div#jsjobs-menu").css('display') == 'none'){
                jQuery("div#jsjobs-menu").show();
                jQuery("div#jsjobs-menu").width(280);
                jQuery("div#jsjobs-menu").find('a.js-parent,a.js-parent2').show();
                jQuery('a.js-parent.lastshown').next().find('a.js-child').css('display','block');
                jQuery('a.js-parent.lastshown').find('img.arrow').attr("src","components/com_jsjobs/include/images/left_menu_icons/arrow2.png");
                jQuery('a.js-parent.lastshown').find('span').css('color','#ffffff');
            }else{
                jQuery("div#jsjobs-menu").hide();
            }
        });

        jQuery('div#jsjobs-menu div.js-divlink a').not('a.js-parent').not('a.js-child').on("touchstart", function (e) {
            'use strict'; //satisfy code inspectors
            // var link = jQuery(this); //preselect the link
            // if (link.hasClass('touch')) {
            //     return true;
            // }else {
            //     link.addClass('touch');
            //     jQuery('div#jsjobs-menu div.js-divlink a').not(this).not('a.js-parent').not('a.js-child').removeClass('touch');
            //     e.preventDefault();
            //     var div = jQuery('div#jsjobs-menu');
            //     openMenu(div);
            //     return false; //extra, and to make sure the function has consistent return points
            // }
        });

        // new code
        var pageWrapper = jQuery("#jsjobs-wrapper");
        var sideMenuArea = jQuery("#jsjobs-menu");

        jQuery("#jsstadmin-menu-toggle").on("click", function () {

            if (pageWrapper.hasClass("menu-collasped-active")) {
                pageWrapper.removeClass("menu-collasped-active");
                jQuery('div#jsjobsfooter').removeClass('menu-collasped-active-footer');
                jQuery('div#jsjobs-footer').removeClass('menu-collasped-active-footer');
                document.cookie = 'jsst_collapse_admin_menu=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
            }else{
                pageWrapper.addClass("menu-collasped-active");
                jQuery("div#jsjobsfooter").addClass('menu-collasped-active-footer');
                jQuery("div#jsjobs-footer").addClass('menu-collasped-active-footer');
                document.cookie = 'jsst_collapse_admin_menu=1; expires=Sat, 01 Jan 2050 00:00:00 UTC; path=/';
            }

        });

        // to set anchor link active on menu collpapsed
        // jQuery('.jsstadmin-sidebar-menu li.treeview a').on('click', function() {
        //     if (!(pageWrapper.hasClass("menu-collasped-active"))) {
        //         window.location.href = jQuery(this).attr("href");
        //     }
        // })

    });
</script>
<?php
// Check condition to show the message
$admin_show_update_data_base_message = JSModel::getJSModel('configuration')->getConfigValue('admin_show_update_data_base_message');

if ($admin_show_update_data_base_message == 1) {
    // Load Joomla message queue
    $app = Factory::getApplication();

    // Define message and button HTML
    $token = Factory::getSession()->getFormToken(); // Joomla CSRF token
    $message  = '<div style="display: flex; justify-content: space-between; align-items: center;">';
    $message .= '<div><strong>Database Update Needed:</strong> A critical update for JS Jobs is required to maintain performance and prevent issues. Please update now.</div>';
    $message .= '<a href="index.php?option=com_jsjobs&c=jsjobs&task=updatedatebaseforai&'.$token.'=1" class="btn btn-primary jsjobs-update-btn" style="margin-left: 20px;margin-top:20px;">Update Now</a>';
    $message .= '</div>';

    // Enqueue the message to admin
    $app->enqueueMessage($message, 'notice'); // types: 'message', 'notice', 'warning', 'error'
}
?>
