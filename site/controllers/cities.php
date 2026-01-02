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

jimport('joomla.application.component.controller');

class JSJobsControllerCities extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function getaddressdatabycityname() {
        $cityname = Factory::getApplication()->input->getString('q');
        // echo " - city Name = ".$cityname." - ";
        $city = $this->getmodel('Cities', 'JSJobsModel');
        $result = $city->getAddressDataByCityName($cityname);
        $json_response = json_encode($result);
        echo $json_response;
        Factory::getApplication()->close();
    }

    function savecity() {
        Factory::getSession()->checkToken( 'get' ) or die( 'Invalid Token' );
        $input = Factory::getApplication()->input->get('citydata','','string');
        $citiesModel = $this->getmodel('cities', 'JSJobsModel');
        $result = $citiesModel->storeCity($input);
        if (is_array($result)) {
            $return_value = json_encode(array('id' => $result[1], 'name' => $result[2], 'latitude' => $result[3], 'longitude' => $result[4])); // send back the cityid newely created
        } elseif ($result == 2) {
            $return_value = Text::_('Error in saving records, please try again');
        } elseif ($result == 3) {
            $return_value = Text::_('Error while saving new state');
        } elseif ($result == 4) {
            $return_value = Text::_('Country not found');
        } elseif ($result == 5) {
            $return_value = Text::_('Location format is not correct, please enter data in this format city name, country name');
        }
        echo $return_value;
        Factory::getApplication()->close();
    }

    function display($cachable = false, $urlparams = false) { // correct employer controller display function manually.
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'default');
        $layoutName = Factory::getApplication()->input->get('layout', 'default');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
