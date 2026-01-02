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
        case 'jobcat':
            $pathway->addItem(Text::_('Job Categories'), '');
            break;
    }
}
?>
