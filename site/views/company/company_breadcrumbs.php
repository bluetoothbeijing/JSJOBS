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

$commonpath = "index.php?option=com_jsjobs";
$pathway = $mainframe->getPathway();
if ($config['cur_location'] == 1) {
    switch ($layout) {
        case 'formcompany':
            if ($result[0]) { // for edit form company
                //$pathway->addItem(Text::_('Employer Control Panel'), $commonpath.'&view=employer&layout=controlpanel&Itemid='.$itemid);
                $pathway->addItem(Text::_('My Companies'), $commonpath . '&c=company&view=company&layout=mycompanies&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else {
                $pathway->addItem(Text::_('New Company'), '');
            }
            break;
        case 'mycompanies':
            $pathway->addItem(Text::_('My Companies'), '');
            break;
        case 'company_jobs':
            if (!empty($jobs) && $jobs[0]->companyname != '')
                $ptitle = $jobs[0]->companyname;
            if (isset($ptitle))
                $ptitle = $ptitle . ' ' . Text::_('Jobs');
            else
                $ptitle = Text::_('Jobs');
            $pathway->addItem($ptitle, '');
            break;
        case 'view_company':
            if ($nav == 31) { //my companies
                $pathway->addItem(Text::_('My Companies'), $commonpath . '&c=company&view=company&layout=mycompanies&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } elseif ($nav == 32) { //list jobs
                $pathway->addItem(Text::_('Job Categories'), $commonpath . '&c=category&view=category&layout=jobcat&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Jobs List By Category'), $commonpath . '&c=category&view=category&layout=list_jobs&cn=&cat=' . $jobcat . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } elseif ($nav == 33) { //job search
                $pathway->addItem(Text::_('Search Job'), $commonpath . '&c=job&view=job&layout=jobsearch&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Job Search Results'), $commonpath . '&c=job&view=job&layout=job_searchresults=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 34) { //my applied jobs
                $pathway->addItem(Text::_('My Applied Jobs'), $commonpath . '&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 35) { //newest jobs
                $pathway->addItem(Text::_('Newest Jobs'), $commonpath . '&c=job&view=job&layout=jobs&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 36) {  //jsmessages jobseeker
                $pathway->addItem(Text::_('Messages'), $commonpath . '&c=message&view=message&layout=jsmessages&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 37) {  //empmessages employer
                $pathway->addItem(Text::_('Messages'), $commonpath . '&c=message&view=message&layout=empmessages&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 38) {  //COMPANY JOBS
                $pathway->addItem(Text::_('Messages'), $commonpath . '&c=company&view=company&layout=company_jobs&cd=' . $company->aliasid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 41) {  //My JOBS
                $pathway->addItem(Text::_('My Jobs'), $commonpath . '&c=job&view=job&layout=myjobs&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else if ($nav == 42) {  //List Companies 
                //$pathway->addItem(Text::_('All Companies'), $commonpath . '&c=company&view=company&layout=listallcompanies&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Company Information'), '');
            } else {
                $pathway->addItem(Text::_('Company Information'), '');
            }
            break;
    }
}
?>
