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

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelSharingResume extends JSModel {

    protected $_db = null;
    protected $_client_auth_key = null;
    protected $_siteurl = null;
    protected $_sharingsitemodel = null;

    function __construct() {
        parent::__construct();
    }

    function getLocalJobid($jobid) {
    }

    function getServerResumeid($id) {
    }

    function getLocalResumeid($id) {
    }

    function getResumeViewbyId($jobid, $id, $uid) {
    }

    function deleteResume($resumeid) {
    }

    function storeResume($resumeid,$jobid,$resumedata) {
    }

    function storeResumeSection($tablename,$rowid) {
    }

    function storeResumeRating($is_own_resume,$is_own_job,$jobid,$resumeid,$uid,$newrating,$rowid){
    }

    function getResumeDetail($jobid,$localresumeid,$uid){
    }

    function getResumeSectionByResumeidAndSectionName($resumeid,$resumesection){
    }

    function getAllResumeFiles($resumeid){
    }
}
