<?php

/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
 ^
 + Project:    JS Jobs
 ^
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;


function getResumeFieldBySection($section){
    if(!is_numeric($section)) return false;
    $db = Factory::getDbo();
    $query = "SELECT uf.* 
                FROM `#__js_job_userfields` AS uf
                JOIN `#__js_job_fieldsordering` AS fo ON fo.field = uf.id
                WHERE uf.fieldfor = 3 AND fo.section = ".$section;
    $db->setQuery($query);
    $uf = $db->loadObjectList();
    $userfields = array();
    foreach($uf AS $f){
        $query = "SELECT * FROM `#__js_job_userfieldvalues` WHERE field = ".$f->id;
        $db->setQuery($query);
        $fv = $db->loadObjectList();
        $field = array('field' => $f, 'fieldvalues' => $fv);
        $userfields[] = $field;
    }
    return $userfields;    
}

function insertResumeFieldsOrdering($section, $userfields, $fieldfor){
    if(!is_numeric($section)) return false;
    $db = Factory::getDbo();
    // Insert into the fieldordering
    $query = "SELECT MAX(ordering) FROM `#__js_job_fieldsordering` WHERE fieldfor = 3 AND section = ".$section;
    $db->setQuery($query);
    $ordering = $db->loadResult();
    $ordering = $ordering + 1;

    foreach($userfields AS $field){
        $f = $field['field'];
        $fv = $field['fieldvalues'];
        $params = '';
        if($f->type == 'select'){
            $p = array();
            foreach($fv AS $pv){
                if(!empty($pv->fieldtitle)){
                    $p[] = $pv->fieldtitle;
                }
            }
            if(!empty($p)){
                $params = json_encode($p);                        
            }
        }
        if($f->type == 'select'){
            $f->type = 'combo';
        }
        if($f->type == 'emailaddress'){
            $f->type = 'email';
        }
        $query = "INSERT INTO `#__js_job_fieldsordering`
                    (field,fieldtitle,fieldfor,section,ordering,published,isvisitorpublished,search_visitor,isuserfield,userfieldtype,userfieldparams,sys,cannotunpublish) VALUES
                    ('js_".str_replace(' ','',$f->name)."_".$section."','".$f->title."', ".$fieldfor." ,".$section.",".$ordering.",".$f->published.",".$f->published.",0,1,'".$f->type."','".$params."',0,0)";
        $db->setQuery($query);
        $db->execute();
        $ordering = $ordering + 1;
    }
    return;    
}

function updateResumeBySection($section,$userfields,$tablename){
    if(!is_numeric($section)) return false;
    if(!is_array($userfields)) return false;
    if(is_numeric($tablename)) return false;
    $db = Factory::getDbo();
    $query = "SELECT ufd.referenceid AS id 
                FROM `#__js_job_userfields` AS uf 
                JOIN `#__js_job_userfield_data` AS ufd ON ufd.field = uf.id 
                JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id
                WHERE uf.fieldfor = 3 AND ff.section = ".$section."
                GROUP BY referenceid";
    $db->setQuery($query);
    $jobs = $db->loadObjectList();
    if($jobs){
        foreach($jobs AS $jb){
            $p = array();
            $query = "SELECT ud.* 
                        FROM `#__js_job_userfields` AS uf 
                        JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id 
                        JOIN `#__js_job_userfield_data` AS ud ON ud.field = uf.id
                        WHERE uf.fieldfor = 3 AND ff.section = ".$section." AND ud.referenceid = ".$jb->id;
            $db->setQuery($query);
            $ufv = $db->loadObjectList();
            if($ufv){
                foreach($ufv AS $tufv){
                    foreach($userfields AS $uf){
                        $f = $uf['field'];
                        if($tufv->field == $f->id){
                            $v = $uf['fieldvalues'];
                            $ft = "js_".str_replace(' ', '', $f->name).'_'.$section;
                            $fv = '';
                            if($f->type == 'combo' OR $f->type == 'select'){
                                foreach($v AS $vfv){
                                    if($vfv->id == $tufv->data){
                                        $fv = $vfv->fieldtitle;
                                        break;
                                    }
                                }
                            }else{
                                $fv = $tufv->data;
                            }
                            $p[$ft] = $fv;
                            $query = "DELETE FROM `#__js_job_userfield_data` WHERE id = ".$tufv->id;
                            $db->setQuery($query);
                            $db->execute();
                        }
                    }
                }
                if(!empty($p)){
                    $params = json_encode($p);
                    $params = $db->escape($params);
                    $query = "UPDATE `#__js_job_".$tablename."` SET params = '".$params."' WHERE id = ".$jb->id;
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
    }
    foreach($userfields AS $fd){
        $f = $fd['field'];
        // Deleting extra records from fieldsordering
        $query = "DELETE FROM `#__js_job_fieldsordering` WHERE id = ".$f->id;
        $db->setQuery($query);
        $db->execute();
    }
    return;
}


function getUserFieldsByFieldFor($fieldfor){
    if(!is_numeric($fieldfor)) return false;
    $db = Factory::getDbo();
    $query = "SELECT * FROM `#__js_job_userfields` WHERE fieldfor = ".$fieldfor;
    $db->setQuery($query);
    $uf = $db->loadObjectList();
    $userfields = array();
    foreach($uf AS $f){
        $query = "SELECT * FROM `#__js_job_userfieldvalues` WHERE field = ".$f->id;
        $db->setQuery($query);
        $fv = $db->loadObjectList();
        $field = array('field' => $f, 'fieldvalues' => $fv);
        $userfields[] = $field;
    }
    return $userfields;
}

function getMaxFieldOrderingByFieldFor($fieldfor){
    if(!is_numeric($fieldfor)) return false;
    $db = Factory::getDbo();
    $ordering = 0;
    $query = "SELECT MAX(ordering) FROM `#__js_job_fieldsordering` WHERE fieldfor = 1";
    $db->setQuery($query);
    $ordering = $db->loadResult();
    $ordering = $ordering + 1;
    return $ordering;    
}

function insertUserFieldsByFieldFor($userfields,$ordering,$fieldfor){
    if(!is_numeric($fieldfor)) return false;
    if(!is_array($userfields)) return false;
    $db = Factory::getDbo();
    foreach($userfields AS $field){
        $f = $field['field'];
        $fv = $field['fieldvalues'];
        $params = '';
        if($f->type == 'select'){
            $p = array();
            foreach($fv AS $pv){
                if(!empty($pv->fieldtitle)){
                    $p[] = $pv->fieldtitle;
                }
            }
            if(!empty($p)){
                $params = json_encode($p);                        
            }
        }
        if($f->type == 'select'){
            $f->type = 'combo';
        }
        if($f->type == 'emailaddress'){
            $f->type = 'email';
        }
        $query = "INSERT INTO `#__js_job_fieldsordering`
                    (field,fieldtitle,fieldfor,section,ordering,published,isvisitorpublished,search_visitor,isuserfield,userfieldtype,userfieldparams,sys,cannotunpublish) VALUES
                    ('js_".str_replace(' ','',$f->name)."','".$f->title."', ".$fieldfor." ,10,".$ordering.",".$f->published.",".$f->published.",0,1,'".$f->type."','".$params."',0,0)";
        $db->setQuery($query);
        $db->execute();
        $ordering = $ordering + 1;
        // Deleting extra records from fieldsordering
        $query = "DELETE FROM `#__js_job_fieldsordering` WHERE field = '".$f->id."' AND fieldfor = ".$fieldfor;
        $db->setQuery($query);
        $db->execute();
    }

    return;
}

$db = Factory::getDbo();
$query = "SHOW TABLES LIKE '".$db->getPrefix()."js_job_userfields'";
$db->setQuery($query);
$table = $db->loadResult();

if($table){    
    // Company User Fields    
    $config = JSModel::getJSModel('configuration')->getConfigValue('last_step_updater');

    // Company Field insert not completed
    $userfields = getUserFieldsByFieldFor(1); // fieldfor company    
    if($config == 1150){ 
        // Insert into the fieldordering
        $ordering = getMaxFieldOrderingByFieldFor(1); // fieldfor company
        insertUserFieldsByFieldFor($userfields,$ordering,1); // fieldfor company

        $query = "UPDATE `#__js_job_config` SET configvalue = '1151' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1151;
    }
    // Company table not populated
    if($config == 1151){
        $query = "SELECT ufd.referenceid AS id 
                    FROM `#__js_job_userfields` AS uf 
                    JOIN `#__js_job_userfield_data` AS ufd ON ufd.field = uf.id 
                    WHERE uf.fieldfor = 1
                    GROUP BY referenceid";
        $db->setQuery($query);
        $companies = $db->loadObjectList();
        if($companies){
            foreach($companies AS $c){
                $p = array();
                $query = "SELECT ud.* 
                            FROM `#__js_job_userfields` AS uf 
                            JOIN `#__js_job_userfield_data` AS ud ON ud.field = uf.id
                            WHERE uf.fieldfor = 1 AND ud.referenceid = ".$c->id;
                $db->setQuery($query);
                $ufv = $db->loadObjectList();
                if($ufv){
                    foreach($ufv AS $tufv){
                        foreach($userfields AS $uf){
                            $f = $uf['field'];
                            if($tufv->field == $f->id){
                                $v = $uf['fieldvalues'];
                                $ft = "js_".str_replace(' ', '', $f->name);
                                $fv = '';
                                if($f->type == 'combo' OR $f->type == 'select'){
                                    foreach($v AS $vfv){
                                        if($vfv->id == $tufv->data){
                                            $fv = $vfv->fieldtitle;
                                            break;
                                        }
                                    }
                                }else{
                                    $fv = $tufv->data;
                                }
                                $p[$ft] = $fv;
                            }
                        }
                        $query = "DELETE FROM `#__js_job_userfield_data` WHERE id = ".$tufv->id;
                        $db->setQuery($query);
                        $db->execute();

                    }
                    if(!empty($p)){
                        $params = json_encode($p);
                        $params = $db->escape($params);
                        $query = "UPDATE `#__js_job_companies` SET params = '".$params."' WHERE id = ".$c->id;
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            }
        }
        $query = "UPDATE `#__js_job_config` SET configvalue = '1152' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1152;
    }

    // Job Field insert not completed
    $userfields = getUserFieldsByFieldFor(2); // fieldfor job
    if($config == 1152){ 
        // Insert into the fieldordering
        $ordering = getMaxFieldOrderingByFieldFor(2); // fieldfor job
        insertUserFieldsByFieldFor($userfields,$ordering,2); // fieldfor job

        $query = "UPDATE `#__js_job_config` SET configvalue = '1153' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1153;
    }
    // Job table not populated
    if($config == 1153){
        $query = "SELECT ufd.referenceid AS id 
                    FROM `#__js_job_userfields` AS uf 
                    JOIN `#__js_job_userfield_data` AS ufd ON ufd.field = uf.id 
                    WHERE uf.fieldfor = 2
                    GROUP BY referenceid";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();
        if($jobs){
            foreach($jobs AS $jb){
                $p = array();
                $query = "SELECT ud.* 
                            FROM `#__js_job_userfields` AS uf 
                            JOIN `#__js_job_userfield_data` AS ud ON ud.field = uf.id
                            WHERE uf.fieldfor = 2 AND ud.referenceid = ".$jb->id;
                $db->setQuery($query);
                $ufv = $db->loadObjectList();
                if($ufv){
                    foreach($ufv AS $tufv){
                        foreach($userfields AS $uf){
                            $f = $uf['field'];
                            if($tufv->field == $f->id){
                                $v = $uf['fieldvalues'];
                                $ft = "js_".str_replace(' ', '', $f->name);
                                $fv = '';
                                if($f->type == 'combo' OR $f->type == 'select'){
                                    foreach($v AS $vfv){
                                        if($vfv->id == $tufv->data){
                                            $fv = $vfv->fieldtitle;
                                            break;
                                        }
                                    }
                                }else{
                                    $fv = $tufv->data;
                                }
                                $p[$ft] = $fv;
                            }
                        }
                        $query = "DELETE FROM `#__js_job_userfield_data` WHERE id = ".$tufv->id;
                        $db->setQuery($query);
                        $db->execute();

                    }
                    if(!empty($p)){
                        $params = json_encode($p);
                        $params = $db->escape($params);
                        $query = "UPDATE `#__js_job_jobs` SET params = '".$params."' WHERE id = ".$jb->id;
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            }
        }
        $query = "UPDATE `#__js_job_config` SET configvalue = '1154' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1154;
    }
    // Resume personal Field insert not completed
    $userfields = getResumeFieldBySection(1);
    if($config == 1154){ 
        insertResumeFieldsOrdering(1, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1155' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1155;
    }    
    // Resume personal table not populated
    if($config == 1155){
        updateResumeBySection(1,$userfields,'resume');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1156' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1156;
    }
    // Resume address Field insert not completed
    $userfields = getResumeFieldBySection(2);
    if($config == 1156){ 
        insertResumeFieldsOrdering(2, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1157' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1157;
    }
    // Resume address table not populated
    if($config == 1157){
        updateResumeBySection(2,$userfields,'resumeaddresses');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1158' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1158;
    }
    // Resume institute Field insert not completed
    $userfields = getResumeFieldBySection(3);
    if($config == 1158){ 
        insertResumeFieldsOrdering(3, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1159' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1159;
    }
    // Resume institute table not populated
    if($config == 1159){
        updateResumeBySection(3,$userfields,'resumeinstitutes');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1160' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1160;
    }
    // Resume employer Field insert not completed
    $userfields = getResumeFieldBySection(4);
    if($config == 1160){ 
        insertResumeFieldsOrdering(4, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1161' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1161;
    }
    // Resume employer table not populated
    if($config == 1161){
        updateResumeBySection(4,$userfields,'resumeemployers');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1162' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1162;
    }
    // Resume skills Field insert not completed
    $userfields = getResumeFieldBySection(5);
    if($config == 1162){ 
        insertResumeFieldsOrdering(5, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1163' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1163;
    }
    // Resume skills table not populated
    if($config == 1163){
        $query = "SELECT ufd.referenceid AS id 
                    FROM `#__js_job_userfields` AS uf 
                    JOIN `#__js_job_userfield_data` AS ufd ON ufd.field = uf.id 
                    JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id
                    WHERE uf.fieldfor = 3 AND ff.section = 5
                    GROUP BY referenceid";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();
        if($jobs){
            foreach($jobs AS $jb){
                $p = array();
                $query = "SELECT ud.* 
                            FROM `#__js_job_userfields` AS uf 
                            JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id 
                            JOIN `#__js_job_userfield_data` AS ud ON ud.field = uf.id
                            WHERE uf.fieldfor = 3 AND ff.section = 5 AND ud.referenceid = ".$jb->id;
                $db->setQuery($query);
                $ufv = $db->loadObjectList();
                if($ufv){
                    foreach($ufv AS $tufv){
                        foreach($userfields AS $uf){
                            $f = $uf['field'];
                            if($tufv->field == $f->id){
                                $v = $uf['fieldvalues'];
                                $ft = "js_".str_replace(' ', '', $f->name).'_5';
                                $fv = '';
                                if($f->type == 'combo' OR $f->type == 'select'){
                                    foreach($v AS $vfv){
                                        if($vfv->id == $tufv->data){
                                            $fv = $vfv->fieldtitle;
                                            break;
                                        }
                                    }
                                }else{
                                    $fv = $tufv->data;
                                }
                                $p[$ft] = $fv;
                                $query = "DELETE FROM `#__js_job_userfield_data` WHERE id = ".$tufv->id;
                                $db->setQuery($query);
                                $db->execute();
                            }
                        }
                    }                    
                    if(!empty($p)){
                        // Since using same table resume so that
                        $query = "SELECT params FROM `#__js_job_resume` WHERE id = ".$jb->id;
                        $db->setQuery($query);
                        $oldparams = $db->loadResult();
                        if(strlen($oldparams) > 0){
                            $oldparamsarray = json_decode($oldparams);
                            foreach($oldparamsarray AS $key => $value){
                                $p[$key] = $value;
                            }
                        }
                        $params = json_encode($p);
                        $params = $db->escape($params);
                        $query = "UPDATE `#__js_job_resume` SET params = '".$params."' WHERE id = ".$jb->id;
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            }
        }
        $query = "UPDATE `#__js_job_config` SET configvalue = '1164' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1164;
    }
    // Resume resume Field insert not completed
    $userfields = getResumeFieldBySection(6);
    if($config == 1164){ 

        insertResumeFieldsOrdering(6, $userfields, 3);

        $query = "UPDATE `#__js_job_config` SET configvalue = '1165' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1165;
    }
    // Resume resume table not populated
    if($config == 1165){
        $query = "SELECT ufd.referenceid AS id 
                    FROM `#__js_job_userfields` AS uf 
                    JOIN `#__js_job_userfield_data` AS ufd ON ufd.field = uf.id 
                    JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id
                    WHERE uf.fieldfor = 3 AND ff.section = 6
                    GROUP BY referenceid";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();
        if($jobs){
            foreach($jobs AS $jb){
                $p = array();
                $query = "SELECT ud.* 
                            FROM `#__js_job_userfields` AS uf 
                            JOIN `#__js_job_fieldsordering` AS ff ON ff.field = uf.id 
                            JOIN `#__js_job_userfield_data` AS ud ON ud.field = uf.id
                            WHERE uf.fieldfor = 3 AND ff.section = 6 AND ud.referenceid = ".$jb->id;
                $db->setQuery($query);
                $ufv = $db->loadObjectList();
                if($ufv){
                    foreach($ufv AS $tufv){
                        foreach($userfields AS $uf){
                            $f = $uf['field'];
                            if($tufv->field == $f->id){
                                $v = $uf['fieldvalues'];
                                $ft = "js_".str_replace(' ', '', $f->name).'_6';
                                $fv = '';
                                if($f->type == 'combo' OR $f->type == 'select'){
                                    foreach($v AS $vfv){
                                        if($vfv->id == $tufv->data){
                                            $fv = $vfv->fieldtitle;
                                            break;
                                        }
                                    }
                                }else{
                                    $fv = $tufv->data;
                                }
                                $p[$ft] = $fv;
                            }
                        }
                    }
                    if(!empty($p)){
                        // Since using same table resume so that
                        $query = "SELECT params FROM `#__js_job_resume` WHERE id = ".$jb->id;
                        $db->setQuery($query);
                        $oldparams = $db->loadResult();
                        if(strlen($oldparams) > 0){
                            $oldparamsarray = json_decode($oldparams);
                            foreach($oldparamsarray AS $key => $value){
                                $p[$key] = $value;
                            }
                        }
                        $params = json_encode($p);
                        $params = $db->escape($params);
                        $query = "UPDATE `#__js_job_resume` SET params = '".$params."' WHERE id = ".$jb->id;
                        $db->setQuery($query);
                        $db->execute();
                    }
                    $query = "DELETE FROM `#__js_job_userfield_data` WHERE referenceid = ".$tufv->id;
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
        $query = "UPDATE `#__js_job_config` SET configvalue = '1166' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1166;
    }
    // Resume reference Field insert not completed
    $userfields = getResumeFieldBySection(7);
    if($config == 1166){ 
        insertResumeFieldsOrdering(7, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1167' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1167;
    }
    // Resume reference table not populated
    if($config == 1167){
        updateResumeBySection(7,$userfields,'resumereferences');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1168' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1168;
    }
    // Resume language Field insert not completed
    $userfields = getResumeFieldBySection(8);
    if($config == 1168){ 
        insertResumeFieldsOrdering(8, $userfields, 3);
        $query = "UPDATE `#__js_job_config` SET configvalue = '1169' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1169;
    }
    // Resume language table not populated
    if($config == 1169){
        updateResumeBySection(8,$userfields,'resumelanguages');
        $query = "UPDATE `#__js_job_config` SET configvalue = '1170' WHERE configname = 'last_step_updater'";
        $db->setQuery($query);
        $db->execute();
        $config = 1170;
    }
    
    $query = "DROP TABLE IF EXISTS `#__js_job_userfields`";
    $db->setQuery($query);
    $db->execute();
    $query = "DROP TABLE IF EXISTS `#__js_job_userfieldvalues`";
    $db->setQuery($query);
    $db->execute();
    $query = "DROP TABLE IF EXISTS `#__js_job_userfield_data`";
    $db->setQuery($query);
    $db->execute();
    $query = "UPDATE `#__js_job_config` SET configvalue = '115' WHERE configname = 'last_version'";
    $db->setQuery($query);
    $db->execute();
    $query = "UPDATE `#__js_job_config` SET configvalue = '1170' WHERE configname = 'last_step_updater'";
    $db->setQuery($query);
    $db->execute();
    
    // Job Searches Table
    $query = "SELECT * FROM `#__js_job_jobsearches`";
    $db->setQuery($query);
    $jobsearches = $db->loadObjectList();
    if(is_array($jobsearches)){
		foreach($jobsearches AS $search){
			$params = array();
			$params['jobtitle'] = $search->jobtitle;
			$params['category'] = $search->category;
			$params['jobtype'] = $search->jobtype;
			$params['srangestart'] = $search->salaryrangestart;
			$params['srangeend'] = $search->salaryrangeend;
			$params['srangetype'] = $search->salaryrangetype;
			$params['shift'] = $search->shift;
			$params['education'] = $search->heighesteducation;
			$params['experiencemin'] = $search->experiencemin;
			$params['experiencemax'] = $search->experiencemax;
			$params['duration'] = $search->durration;
			$params['startpublishing'] = $search->startpublishing;
			$params['stoppublishing'] = $search->stoppublishing;
			$params['company'] = $search->company;
			$params['city'] = $search->city;
			$params = json_encode($params);
			$query = "UPDATE `#__js_job_jobsearches` SET searchparams = '".$db->escape($params)."' WHERE id = ".$search->id;
			$db->setQuery($query);
			$db->execute();
		}
	}
	
    // Resume Searches Table
    $query = "SELECT * FROM `#__js_job_resumesearches`";
    $db->setQuery($query);
    $resumesearches = $db->loadObjectList();
	if(is_array($resumesearches)){
		foreach($resumesearches AS $search){
			$params = array();
			$params['name'] = $search->application_title;
			$params['nationality'] = $search->nationality;
			$params['jobcategory'] = $search->category;
			$params['jobtype'] = $search->jobtype;
			$params['heighestfinisheducation'] = $search->education;
			$params['iamavailable'] = $search->iamavailable;
			$params = json_encode($params);
			$query = "UPDATE `#__js_job_resumesearches` SET searchparams = '".$db->escape($params)."' WHERE id = ".$search->id;
			$db->setQuery($query);
			$db->execute();
		}
	}
    
}        
?>
