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
        case 'job_apply':

            if ($nav == 26) { // list jobs by category
                $pathway->addItem(Text::_('Job Categories'), $commonpath . '&c=category&view=category&layout=jobcat&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Jobs List By Category'), $commonpath . '&c=category&view=category&layout=list_jobs&cat=' . $jobresult[0]->jobcategory . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Apply Now'), '');
            } else if ($nav == 28) { // search job result
                $pathway->addItem(Text::_('Search Job'), $commonpath . '&c=job&view=job&layout=jobsearch&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Job Search Results'), $commonpath . '&c=job&view=job&layout=job_searchresults&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Apply Now'), '');
            } else if ($nav == 25) { // newest jobs
                $pathway->addItem(Text::_('Newest Jobs'), $commonpath . '&c=job&view=job&layout=jobs&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Apply Now'), '');
            } else if ($nav == 39) { // company jobs
                $pathway->addItem(Text::_('Jobs'), $commonpath . '&c=company&view=company&layout=company_jobs&cd=' . $jobresult[0]->companyid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Apply Now'), '');
            } else if ($nav == 27) { //list jobs by subcategory
                $pathway->addItem(Text::_('Job Categories'), $commonpath . '&c=job&view=job&layout=list_jobs&cat=' . $jobcat . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Jobs List By Category'), $commonpath . '&c=category&view=category&layout=jobcat&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Apply Now'), '');
            } else {
                $pathway->addItem(Text::_('Apply Now'), '');
            }

            break;
        case 'myappliedjobs':
            $pathway->addItem(Text::_('My Applied Jobs'), '');
            break;
        case 'alljobsappliedapplications':
            $pathway->addItem(Text::_('Applied Resume'), '');
            break;
        case 'job_appliedapplications':
            $pathway->addItem(Text::_('My Jobs'), $commonpath . '&c=job&view=job&layout=myjobs&Itemid=' . $itemid);
            $pathway->addItem(Text::_('Job Applied Resume'), '');
            break;
    }
}
?>
