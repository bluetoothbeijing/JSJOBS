<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
 * Created on:	May 22, 2015
 ^
 + Project: 	JS Tickets
 ^
*/

defined ('_JEXEC') or die('Not Allowed');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;	

jimport('joomla.application.component.controller');

class JSJobsControllerGdpr extends JSController{

	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
	}

	function saveusereraserequest(){
		Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
		$Itemid =  Factory::getApplication()->input->get('Itemid');
		$data = Factory::getApplication()->input->post->getArray();
		if($data['id'] <> '')
			$id = $data['id'];
		$gdpr = $this->getmodel('gdpr', 'JSJobsModel');
		$result = $gdpr->storeUserEraseRequest($data);
		$link = 'index.php?option=com_jsjobs&c=gdpr&layout=adderasedatarequest&Itemid='.$Itemid;
		JSJOBSActionMessages::setMessage($result, 'gdprrequest');
        $this->setRedirect(Route::_($link), $msg);
	}

	function removeusereraserequest() {
		Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        $id = Factory::getApplication()->input->get('id','');
        if(is_numeric($id)){
        	$gdpr = $this->getmodel('gdpr','JSJobsModel');
        	$result = $gdpr->deleteUserEraseRequest($id);
        	$msg = JSJOBSActionMessages::setMessage($result, 'gdprrequest');
		}
		$link = "index.php?option=com_jsjobs&c=gdpr&layout=adderasedatarequest";
        $this->setRedirect($link, $msg);
    }

    function exportusereraserequest(){
    	$user = Factory::getUser();
        $uid = $user->id;
        if(is_numeric($uid) && $uid > 0){
        	$gdpr = $this->getmodel('gdpr','JSJobsModel');
        	$return_value = $gdpr->setUserExportByuid($uid);
        	if (!empty($return_value)) {
	            // Push the report now!
	            $msg = Text::_('User Data');
	            $name = 'export-overall-reports';
	            header("Content-type: application/octet-stream");
	            header("Content-Disposition: attachment; filename=" . $name . ".xls");
	            header("Pragma: no-cache");
	            header("Expires: 0");
	            header("Lacation: excel.htm?id=yes");
	            ob_clean();
        	    flush();
	            print $return_value;
	            exit;
	        }else{
	        	$msg = Text::_("No data found for export.");
	        	$link = "index.php?option=com_jsjobs&c=gdpr&layout=adderasedatarequest";
        		$this->setRedirect($link,$msg);
	        }
	    }else{
        	$link = "index.php?option=com_jsjobs&c=gdpr&layout=adderasedatarequest";
        	$this->setRedirect($link);
        }
    }

    function display($cachable = false, $urlparams = false){
		$document = Factory::getDocument();
		$viewName = 'gdpr';
		$layoutName = Factory::getApplication()->input->get('layout', 'adderasedatarequest');
		$viewType = $document->getType();
		$view = $this->getView($viewName, $viewType);
		$view->setLayout($layoutName);
		$view->display();
	}
}
?>
