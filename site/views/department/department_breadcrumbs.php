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
        case 'mydepartments':
            $pathway->addItem(Text::_('My Departments'), '');
            break;
        case 'formdepartment':
            if (isset($result)) {
                $pathway->addItem(Text::_('My Departments'), $commonpath . '&c=department&view=department&layout=mydepartments&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Edit Department Information'), '');
            } else {
                $pathway->addItem(Text::_(' Department'), '');
            }
            break;
        case 'view_department':
            $pathway->addItem(Text::_('My Departments'), $commonpath . '&c=department&view=department&layout=mydepartments&Itemid=' . $itemid);
            $pathway->addItem(Text::_('View Department'), '');
            break;
    }
}
?>
