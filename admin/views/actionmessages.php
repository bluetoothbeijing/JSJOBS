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


class JSJOBSActionMessages {
    /*
     * setLayoutMessage
     * @params $message = Your message to display
     * @params $type = Messages types => 'error','notice','warning','message'
     */

    public static function setMessage($result, $entity, $msgtype = 'message') {

        $application = Factory::getApplication(); // Joomla main application object

        $entity_name = JSJOBSActionMessages::getEntityName($entity); // Entity name should be returned

        switch ($result) {
            case WAITING_FOR_APPROVAL:
                $msg = $entity_name . ' ' . Text::_('is waiting for approval');
                break;
            case SAVED:
                $msg = $entity_name . ' ' . Text::_('has been successfully saved');
                break;
            case SAVE_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been saved');
                break;
            case DELETED:
                $msg = $entity_name . ' ' . Text::_('has been deleted');
                break;
            case DELETE_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been deleted');
                break;
            case PUBLISHED:
                $msg = $entity_name . ' '. Text::_('has been published');
                break;
            case PUBLISH_ERROR:
                $msg = $entity_name . ' '. Text::_('has not been published');
                break;
            case UN_PUBLISHED:
                $msg = $entity_name . ' '.  Text::_('has been unpublished');
                break;
            case UN_PUBLISH_ERROR:
                $msg = $entity_name . ' '. Text::_('has not been unpublished');
                break;
            case DEFAULT_UN_PUBLISH_ERROR:
                $msg = $entity_name . ' '. Text::_('has been set as default can not be unpublished');
                break;
            case REJECTED:
                $msg = $entity_name . ' ' . Text::_('has been rejected');
                break;
            case REJECT_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been rejected');
                break;
            case APPROVED:
                $msg = $entity_name . ' ' . Text::_('has been approved');
                break;
            case APPROVE_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been approved');
                break;
            case SET_DEFAULT:
                $msg = $entity_name . ' ' . Text::_('has been set as default');
                break;
            case UNPUBLISH_DEFAULT_ERROR:
                $msg = Text::_('Unpublished field cannot set the default');
                break;
            case SET_DEFAULT_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been set as default');
                break;
            case STATUS_CHANGED:
                $msg = $entity_name . ' ' . Text::_('status has been updated');
                break;
            case STATUS_CHANGED_ERROR:
                $msg = $entity_name . ' ' . Text::_('status has not been updated');
                break;
            case IN_USE:
                $msg = $entity_name . ' ' . Text::_('in use can not be deleted');
                break;
            case ALREADY_EXIST:
                $msg = $entity_name . ' ' . Text::_('already exist');
                break;
            case FILE_TYPE_ERROR:
                $msg = Text::_('Error in the file type');
                break;
            case OPERATION_CANCELLED:
                $msg = Text::_('Operation Cancelled');
                break;
            case SHARING_IMPROPER_NAME:
                $msg = Text::_('Sharing server did not accept because of the improper name');
                break;
            case SHARING_AUTH_FAIL:
                $msg = Text::_('Authentication fails on sharing server');
                break;
            case SHARING_SYNCHRONIZE_ERROR:
                $msg = Text::_('Problem synchronize with sharing server');
                break;
            case REQUIRED_FIELDS:
                $msg = Text::_('All required fields must be filled');
                $msg = Text::_('Please fill all required fields');
                break;
            case NOT_YOUR:
                $msg = Text::_('This is not your') . ' ' . $entity_name;
                break;
            case FILE_SIZE_ERROR:
                $msg = Text::_('Error in uploading file').'. '.Text::_('File size is greater than allowed size');
                break;
            case NOT_APPROVED:
                $msg = $entity_name . ' ' . Text::_('Not Approved');
                break;
            case CAN_NOT_ADD_NEW:
                $msg = Text::_('Can not add new'). ' '.$entity_name;
                break;
            case SET_AS_REQUIRED:
                $msg = Text::_('Fields successfully set the required field');
                break;
            case SET_AS_NOT_REQUIRED:
                $msg = Text::_('Fields successfully set not required field');
                break;
            case ORDERING_UP:
                $msg = $entity_name.' '.Text::_('Order up successfully');
                break;
            case ORDERING_DOWN:
                $msg = $entity_name.' '.Text::_('Order down successfully');
                break;
            case ERASED:
                $msg = $entity_name . ' ' . Text::_('has been erased successfully');
                break;
            case ERASE_ERROR:
                $msg = $entity_name . ' ' . Text::_('has not been erased');
                break;
            default:
                $msg = Text::_($result);
                break;
        }
        $application->enqueueMessage($msg, $msgtype);
        return;
    }

    private static function getEntityName($entity) {
        $name = "";
        $entity = strtolower($entity);
        switch ($entity) {
            case 'salaryrange':$name = Text::_('Salary Range');break;
            case 'addressdata':$name = Text::_('Address Data');break;
            case 'age':$name = Text::_('Age');break;
            case 'careerlevel':$name = Text::_('Career Level');break;
			case 'filter':$name = Text::_('Filter');break;
            case 'category':$name = Text::_('Category');break;
            case 'city':$name = Text::_('City');break;
            case 'coverletter':$name = Text::_('Cover Letter');break;
            case 'company':$name = Text::_('Company');break;
            case 'featuredcompany':$name = Text::_('Featured Company');break;
            case 'goldcompany':$name = Text::_('Gold Company');break;
            case 'country':$name = Text::_('Country');break;
            case 'currency':$name = Text::_('Currency');break;
            case 'customfield':$name = Text::_('Field');break;
            case 'department':$name = Text::_('Department');break;
            case 'employerpackages':$name = Text::_('Employer Package');break;
            case 'experience':$name = Text::_('Experience');break;
            case 'fieldordering':$name = Text::_('Resume Field');break;
            case 'folder':$name = Text::_('Folder');break;
            case 'folderresume':$name = Text::_('Folder Resume');break;
            case 'highesteducation':$name = Text::_('Highest Education');break;
            case 'job':$name = Text::_('Job');break;
            case 'featuredjob':$name = Text::_('Featured Job');break;
            case 'goldjob':$name = Text::_('Gold Job');break;
            case 'jobalert':$name = Text::_('Job Alert');break;
            case 'jobseekerpackages':$name = Text::_('Job Seeker Package');break;
            case 'jobstatus':$name = Text::_('Job Status');break;
            case 'jobtype':$name = Text::_('Job Type');break;
            case 'message':$name = Text::_('Message');break;
            case 'paymenthistory':$name = Text::_('Payment History');break;
            case 'paymentmethodconfiguration':$name = Text::_('Payment Method Configuration');break;
            case 'resume':$name = Text::_('Resume');break;
            case 'featuredresume':$name = Text::_('Featured Resume');break;
            case 'goldresume':$name = Text::_('Gold Resume');break;
            case 'salaryrange':$name = Text::_('Salary Range');break;
            case 'salaryrangetype':$name = Text::_('Salary Range Type');break;
            case 'shift':$name = Text::_('Shift');break;
            case 'state':$name = Text::_('State');break;
            case 'subcategory':$name = Text::_('Sub Category');break;
            case 'user':$name = Text::_('User');break;
            case 'userrole':$name = Text::_('User Role');break;
            case 'stateandcounty':$name = Text::_('State And County');break;
            case 'stateandcity':$name = Text::_('State And City');break;
            case 'countyandcity':$name = Text::_('County And City');break;
            case 'statecountyandcity':$name = Text::_('State County And City');break;
            case 'categoryandsubcategory':$name = Text::_('Category And Sub Category');break;
            case 'configuration':$name = Text::_('Configuration');break;
            case 'email':$name = Text::_('Email');break;
            case 'search':$name = Text::_('Search');break;
            case 'shortlistedjob':$name = Text::_('Shortlisted Job');break;
            case 'package':$name = Text::_('Package');break;
            case 'emailtemplate':$name = Text::_('Email Template');break;
            case 'settings':$name = Text::_('Settings');break;
            case 'field':$name = Text::_('Field');break;
            case 'resumeuserfield':$name = Text::_('Resume User Field');break;
            case 'shortlistcandidate':$name = Text::_('Shortlist Candidate');break;
            case 'payment':$name = Text::_('Payment');break;
            case 'gdpr' : $name = Text::_('GDPR Fields'); break;
            case 'userdataerase' : $name = Text::_('User Data'); break;
            case 'gdprrequest' : $name = Text::_('Erase Data request'); break;

        }
        return $name;
    }

}

?>
