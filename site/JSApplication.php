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
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Version;

if (!defined('JVERSION')) {
    $version = new Version;
    $joomla = $version->getShortVersion();
    $jversion = substr($joomla, 0, 3);
    define('JVERSION', $jversion);
}
if (JVERSION < 3) {
    jimport('joomla.application.component.model');
    jimport('joomla.application.component.view');
    jimport('joomla.application.component.controller');

    if (!class_exists('JSModel', false)) {

        abstract class JSModel extends JModel {

            function __construct() {
                parent::__construct();
            }

            Static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }
            
            // for resume on admin
            static function getJSSiteModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

            static function getSharingAdminModel($model){
                require_once JPATH_SITE . '/administrator/components/com_jsjobs/models/sharing/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModelSharing' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSView', false)) {

        abstract class JSView extends JView {

            function __construct() {
                parent::__construct();
            }

            Static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSController', false)) {

        abstract class JSController extends JController {

            function __construct() {
                parent::__construct();
            }

        }

    }
} elseif (JVERSION < 6) {

    if (!class_exists('JSModel', false)) {

        abstract class JSModel extends JModelLegacy {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

            // for resume on admin
            static function getJSSiteModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }
            
            static function getSharingAdminModel($model){
                require_once JPATH_SITE . '/administrator/components/com_jsjobs/models/sharing/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModelSharing' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSView', false)) {

        abstract class JSView extends JViewLegacy {

            function __construct() {
                parent::__construct();
            }

            Static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSController', false)) {

        abstract class JSController extends JControllerLegacy {

            function __construct() {
                parent::__construct();
            }

        }

    }
} else {

    if (!class_exists('JSModel', false)) {

        abstract class JSModel extends BaseDatabaseModel {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

            // for resume on admin
            static function getJSSiteModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }
            
            static function getSharingAdminModel($model){
                require_once JPATH_SITE . '/administrator/components/com_jsjobs/models/sharing/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModelSharing' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSView', false)) {

        abstract class JSView extends HtmlView {

            function __construct() {
                parent::__construct();
            }

            Static function getJSModel($model) {
                require_once JPATH_SITE . '/components/com_jsjobs/models/' . strtolower($model) . '.php';
                $modelclass = 'JSJobsModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSController', false)) {

        abstract class JSController extends BaseController {

            function __construct() {
                parent::__construct();
            }

        }

    }
}

$commonModel = JSModel::getJSModel('common');
defined('VIRTUEMARTJSJOBS') or define('VIRTUEMARTJSJOBS',$commonModel->b64ForDecode('Y29tbW9u'));
defined('VIRTUEMARTJSJOB') or define('VIRTUEMARTJSJOB',$commonModel->b64ForDecode('am9i'));
defined('VIRTUEMARTJSJOBSFUN') or define('VIRTUEMARTJSJOBSFUN',$commonModel->b64ForDecode('aXNQbHVnaW5FbmFibGVk'));
defined('JSJOBSVMS') or define('JSJOBSVMS',$commonModel->b64ForDecode('ZW1wbG95ZXI='));
defined('JSJOBSVMSFUN') or define('JSJOBSVMSFUN',$commonModel->b64ForDecode('aXNQbHVnaW5FbmFibGVkRnVuYw=='));

?>
