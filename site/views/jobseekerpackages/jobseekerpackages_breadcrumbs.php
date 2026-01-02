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
        case 'package_buynow':
            if ($nav == 21) {
                $pathway->addItem(Text::_('Packages'), $commonpath . '&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Package Buy Now'), '');
            } elseif ($nav == 22) {
                $pathway->addItem(Text::_('Packages'), $commonpath . '&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Package Details'), $commonpath . '&c=jobseekerpackages&view=jobseekerpackages&layout=package_details&gd=' . $package->id . '&Itemid=' . $itemid);
                $pathway->addItem(Text::_('Package Buy Now'), '');
            }
            break;
        case 'package_details':
            $pathway->addItem(Text::_('Packages'), $commonpath . '&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $itemid);
            $pathway->addItem(Text::_('Package Details'), '');
            break;
        case 'packages':
            $pathway->addItem(Text::_('Packages'), '');
            break;
    }
}
?>
