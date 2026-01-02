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
        case 'formresume':
            if ($nav == 29) { //my resume 
                $pathway->addItem(Text::_('My Resume'), $commonpath . '&c=resume&view=resume&layout=myresumes&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Resume Form'), '');
            } else {
                $pathway->addItem(Text::_('Resume Form'), '');
            }
            break;
        case 'resumesearch':
            $pathway->addItem(Text::_('Resume Search'), '');
            break;
        case 'myresumes':
            $pathway->addItem(Text::_('My Resume'), '');
            break;
        case 'my_resumesearches':
            $pathway->addItem(Text::_('Resume Save Searches'), '');
            break;
        case 'resumebycategory':
            $pathway->addItem(Text::_('Resume By Categories'), '');
            break;
        case 'resume_bycategory':
            $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resumebycategory&Itemid=' . $itemid);
            $pathway->addItem(Text::_('Resume By Categories'), '');
            break;
        case 'resume_bysubcategory':
            $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resumebycategory&Itemid=' . $itemid);
            $pathway->addItem(Text::_('Sub Category'), '');
            break;
        case 'resume_searchresults':
            $pathway->addItem(Text::_('Resume Search'), $commonpath . '&c=resume&view=resume&layout=resumesearch&Itemid=' . $itemid);
            $pathway->addItem(Text::_('Resume Search Result'), '');
            break;
        case 'view_resume':
            if ($nav == 1) { //my resume 
                $pathway->addItem(Text::_('My Resume'), $commonpath . '&c=resume&view=resume&layout=myresumes&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 2) { //view resume
                $pathway->addItem(Text::_('My Jobs'), $commonpath . '&c=job&view=job&layout=myjobs&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Job Applied Resume'), $commonpath . '&c=jobapply&view=jobapply&layout=job_appliedapplications&bd=' . $jobaliasid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 3) { //search resume
                $pathway->addItem(Text::_('Resume Search'), $commonpath . '&c=resume&view=resume&layout=resumesearch&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Resume Search Result'), $commonpath . '&c=resume&view=resume&layout=resume_searchresults&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 7) { //folder resume
                $pathway->addItem(Text::_('My Folders'), $commonpath . '&c=folder&view=folder&layout=myfolders&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Folder Resume'), $commonpath . '&c=folder&view=folder&layout=folder_resumes&fd=' . $folderid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 4) { //Resume By Categories 
                $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resumebycategory&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resume_bycategory&cat=' . $catid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 5) { //Resume By Categories 
                $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resumebycategory&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Resume By Categories'), $commonpath . '&c=resume&view=resume&layout=resume_bycategory&cat=' . $catid . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 6) { //resume rss
                $pathway->addItem(Text::_('View Resume'), '');
            } elseif ($nav == 7) { // my applied jobs
                $pathway->addItem(Text::_('My Applied Jobs'), $commonpath.'&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $itemid);
            }
            break;
        case 'viewresumesearch':
            break;
    }
}
?>
