<?php

/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:     Buruj Solutions
  + Contact:        www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  Jan 11, 2009
  ^
  + Project:        JS Jobs
 * File Name:   views/employer/tmpl/controlpanel.php
  ^
 * Description: template view for control panel
  ^
 * History:     NONE
  ^
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;



require JPATH_COMPONENT_ADMINISTRATOR . '/views/report/tmpl/tfpdf.php';

class PDF_HTML extends tFPDF {

    var $B = 0;
    var $I = 0;
    var $U = 0;
    var $HREF = '';
    var $ALIGN = '';

    function WriteHTML($html) {
        //HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                //Text
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                elseif ($this->ALIGN == 'center')
                    $this->Cell(0, 5, $e, 0, 1, 'C');
                else
                    $this->Write(5, $e);
            }
            else {
                //Tag
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    //Extract properties
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $prop = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $prop[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $prop);
                }
            }
        }
    }

    function OpenTag($tag, $prop) {
        //Opening tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $prop['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
        if ($tag == 'P')
            $this->ALIGN = isset($prop['ALIGN']) ? $prop['ALIGN'] : '';
        if ($tag == 'HR') {
            if (!empty($prop['WIDTH']))
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin - $this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x, $y, $x + $Width, $y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag) {
        //Closing tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
        if ($tag == 'P')
            $this->ALIGN = '';
    }

    function SetStyle($tag, $enable) {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s)
            if ($this->$s > 0)
                $style.=$s;
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt) {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

}

$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);


global $mainframe;

$section_personal = 1;
$section_basic = 1;
$section_addresses = 0;
$section_educations = 0;
$section_employers = 0;
$section_skills = 0;
$section_resume = 0;
$section_references = 0;
$section_languages = 0;

if (!isset($this->fieldsordering))
    echo 'Please refresh the page';
foreach ($this->fieldsordering as $ordering) {
    foreach ($ordering as $field) {
        if ($field->field == "section_address") {
            $section_addresses = $field->published;
        } elseif ($field->field == "section_institute") {
            $section_educations = $field->published;
        } elseif ($field->field == "section_employer") {
            $section_employers = $field->published;
        } elseif ($field->field == "section_skills") {
            $section_skills = $field->published;
        } elseif ($field->field == "section_resume") {
            $section_resume = $field->published;
        } elseif ($field->field == "section_reference") {
            $section_references = $field->published;
        } elseif ($field->field == "section_language") {
            $section_languages = $field->published;
        }
    }
}
$fieldsordering = $this->fieldsordering;

$customfieldobj = getCustomFieldClass();

$pdf_output = '';
if (isset($this->resume)) { // check for empty values
    if ($this->resume->first_name == '') {
        $this->resume->first_name = 'N/A';
    }
    if ($this->resume->middle_name == '') {
        $this->resume->middle_name = 'N/A';
    }
    if ($this->resume->last_name == '') {
        $this->resume->first_name = 'N/A';
    }
    if ($this->resume->application_title == '') {
        $this->resume->application_title = 'N/A';
    }
    if ($this->resume->nationalitycountry == '') {
        $this->resume->nationalitycountry = 'N/A';
    }
    if ($this->resume->gender == '') {
        $this->resume->gender = 'N/A';
    }elseif($this->resume->gender == 1){
        $this->resume->gender = Text::_('Male');
    }else{
        $this->resume->gender = Text::_('Female');
    }
    if ($this->resume->iamavailable == '') {
        $this->resume->iamavailable = 'N/A';
    }else{
        $this->resume->iamavailable = Text::_('JYES');
    }
    if ($this->resume->searchable == '') {
        $this->resume->searchable = 'N/A';
    }
    if ($this->resume->home_phone == '') {
        $this->resume->home_phone = 'N/A';
    }
    if ($this->resume->work_phone == '') {
        $this->resume->work_phone = 'N/A';
    }
    if ($this->resume->categorytitle == '') {
        $this->resume->categorytitle = 'N/A';
    }
    if ($this->resume->subcategorytitle == '') {
        $this->resume->subcategorytitle = 'N/A';
    }
    if ($this->resume->jobtypetitle == '') {
        $this->resume->jobtypetitle = 'N/A';
    }
    if ($this->resume->heighesteducationtitle == '') {
        $this->resume->heighesteducationtitle = 'N/A';
    }
    if ($this->resume->date_start == '0000-00-00 00:00:00') {
        $this->resume->date_start = '1970-01-01 00:00:00';
    }

    if(!empty($this->resume->experienceid)){
        $this->resume->total_experience = Text::_($this->resume->experiencetitle);
    }
    if ($this->resume->total_experience == '') {
        $this->resume->total_experience = 'N/A';
    }
    if ($this->resume->date_of_birth == '0000-00-00 00:00:00') {
        $this->resume->date_of_birth = '1970-01-01 00:00:00';
    }
    if ($this->resume->skills == '') {
        $this->resume->skills = 'N/A';
    }
}

function addSection($title, &$y, &$pdf, $published) {
    if ($published == 1) {
        $pdf->SetDrawColor(223, 223, 223);
        $pdf->SetFillColor(246, 247, 248);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->SetLineWidth(1);
        $pdf->SetFont('DejaVu', '', 13);
        $pdf->SetLineWidth(0.1);
        $pdf->MultiCell(190, 8, $title, 1, 'J', true);
        $y = $pdf->GetY();
    }
    return;
}

function addSubSection($title, &$y, &$pdf, $published) {
    if ($published == 1) {
        $pdf->SetDrawColor(223, 223, 223);
        $pdf->SetFillColor(246, 247, 248);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->SetLineWidth(1);
        $pdf->SetFont('DejaVu', '', 13);
        $pdf->SetLineWidth(0.1);
        $pdf->SetY($y + 2);
        $pdf->SetX(30);
        $pdf->MultiCell(150, 8, $title, 1, 'J', true);
        $y = $pdf->GetY();
    }
    return;
}
//echo $pdf->h - $pdf->lMargin - $pdf->tMargin;exit;
function addRow($title, $value, &$y, &$pdf, $published) {
    if ($published == 1) {
        $pdf->SetFont("DejaVu", "", 11);
        $pdf->SetY($y);
        $pdf->MultiCell(60, 8, $title, 0, 'R');
        $oldy1 = $pdf->GetY();
        if ($y > (270))
            $y = 10;
        $pdf->SetY($y);
        $pdf->SetX(70);
        $pdf->SetFont("DejaVu", "", 10);
        $pdf->MultiCell(130, 8, $value, 0, 'J');
        $oldy2 = $pdf->GetY();
        $y = ($oldy1 >= $oldy2) ? $oldy1 : $oldy2;
    }
}

function getResumeUserField($object_obj, $field, $customfieldobj, &$y, &$pdf) {
    if(Factory::getApplication()->isClient('administrator')){
        $issharing = JSModel::getJSModel('configuration')->getConfigValue('authentication_client_key');
    }else{
        $issharing = JSModel::getJSModel('configurations')->getConfigValue('authentication_client_key');
    }
    if($issharing != ''){
        if (isset($userfield) AND is_object($userfield)) {
            /* user field hide in case of the job sharing
            for ($k = 0; $k < 15; $k++) {
                $field_title = 'fieldtitle_' . $k;
                $field_value = 'fieldvalue_' . $k;
                if (!empty($userfield->$field_title) && $userfield->$field_title != null) {
                    $pdf->SetFont("Arial", "B", 10);
                    $pdf->SetY($y);
                    $pdf->MultiCell(60, 8, $userfield->$field_title, 0, 'R');
                    $oldy1 = $pdf->GetY();
                    if ($y > (270))
                        $y = 10;
                    $pdf->SetY($oldy1);
                    $pdf->SetX(70);
                    $pdf->SetFont("Arial", "", 10);
                    $pdf->MultiCell(130, 8, $userfield->$field_value, 0, 'J');
                    $oldy2 = $pdf->GetY();
                    $y = ($oldy1 >= $oldy2) ? $oldy1 : $oldy2;
                }
            }
            */
        }
    }else{
        $field = $field->field;
        $arr = $customfieldobj->showCustomFields($field, 11 ,$object_obj );
        if(!$arr)
            return '';
        $title = $arr['title'];
        $value = $arr['value'];


        $pdf->SetFont("DejaVu", "", 11);
        $pdf->SetY($y);
        $pdf->MultiCell(60, 8, $title, 0, 'R');
        $oldy1 = $pdf->GetY();
        if ($y > (270))
            $y = 10;
        $pdf->SetY($y);
        $pdf->SetX(70);
        $pdf->SetFont("DejaVu", "", 10);
        $pdf->MultiCell(130, 8, $value, 0, 'J');
        $oldy2 = $pdf->GetY();
        $y = ($oldy1 >= $oldy2) ? $oldy1 : $oldy2;
    }
}

$y = 0; // init for the resume pdf vars
if (isset($this->resume)) {
    foreach ($fieldsordering['personal'] as $field) {
        switch ($field->field) {
            case 'section_personal':
                if ($field->published == 1) {
                    $pdf->SetFont("DejaVu", "", 15);
                    $pdf->SetFillColor(68, 68, 66);
                    $pdf->SetTextColor(253, 253, 253);
                    $resumetitle = '';
                    $resumetitle .= $this->resume->first_name;
                    $resumetitle .= ' ' . $this->resume->last_name;
                    $pdf->MultiCell(190, 8, Text::_('Resume') . ':  ' . $resumetitle, 0, 'J', true);
                    $pdf->WriteHTML('<hr>', true);
                    addSection(Text::_('Personal Information'), $y, $pdf, $field->published);
                }
                break;
            case "application_title":
                addRow( $field->fieldtitle , $this->resume->application_title, $y, $pdf, $field->published);
                break;
            case "first_name":
                addRow($field->fieldtitle, $this->resume->first_name, $y, $pdf, $field->published);
                break;
            case "middle_name":
                addRow($field->fieldtitle, $this->resume->middle_name, $y, $pdf, $field->published);
                break;
            case "last_name":
                addRow($field->fieldtitle, $this->resume->last_name, $y, $pdf, $field->published);
                break;
            case "email_address":
                addRow($field->fieldtitle, $this->resume->email_address, $y, $pdf, $field->published);
                break;
            case "nationality":
                addRow($field->fieldtitle, $this->resume->nationalitycountry, $y, $pdf, $field->published);
                break;
            case "date_of_birth":
                $date_of_birth = '';
                if($this->resume->date_of_birth != ''){
                    $date_of_birth = HTMLHelper::_('date', $this->resume->date_of_birth, $this->config['date_format']);
                }
                addRow($field->fieldtitle, $date_of_birth, $y, $pdf, $field->published);
                break;
            case "gender":
                addRow($field->fieldtitle, $this->resume->gender, $y, $pdf, $field->published);
                break;
            case "iamavailable":
                addRow($field->fieldtitle, $this->resume->iamavailable, $y, $pdf, $field->published);
                break;
            case "searchable":
                $value = ($this->resume->searchable == 1) ? Text::_('JYES') : Text::_('JNO');
                addRow($field->fieldtitle, $value, $y, $pdf, $field->published);
                break;
            case "home_phone":
                addRow($field->fieldtitle, $this->resume->home_phone, $y, $pdf, $field->published);
                break;
            case "work_phone":
                addRow($field->fieldtitle, $this->resume->work_phone, $y, $pdf, $field->published);
                break;
            case "cell":
                addRow($field->fieldtitle, $this->resume->cell, $y, $pdf, $field->published);
                break;
            case "job_category":
                addRow($field->fieldtitle, $this->resume->categorytitle, $y, $pdf, $field->published);
                break;
            case "job_subcategory":
                addRow($field->fieldtitle, $this->resume->subcategorytitle, $y, $pdf, $field->published);
                break;
            case "desired_salary":
                if($this->config['currency_align'] == 1){ // Left align
                    $value = $this->resume->dsymbol . ' ' . $this->resume->drangestart . ' - ' . $this->resume->drangeend . ' ' . $this->resume->dsalarytype;
                }elseif($this->config['currency_align'] == 2){ // Right align
                    $value = $this->resume->drangestart . ' - ' . $this->resume->drangeend . ' ' . $this->resume->dsymbol . ' ' . $this->resume->dsalarytype;
                }
                addRow($field->fieldtitle, $value, $y, $pdf, $field->published);
                break;
            case "salary":
                if($this->config['currency_align'] == 1){ // Left align
                    $value = $this->resume->symbol . ' ' . $this->resume->rangestart . ' - ' . $this->resume->rangeend . ' ' . $this->resume->salarytype;
                }elseif($this->config['currency_align'] == 2){ // Right align
                    $value = $this->resume->rangestart . ' - ' . $this->resume->rangeend . ' ' . $this->resume->symbol . ' ' . $this->resume->salarytype;
                }
                addRow($field->fieldtitle, $value, $y, $pdf, $field->published);
                break;
            case "jobtype":
                addRow($field->fieldtitle, $this->resume->jobtypetitle, $y, $pdf, $field->published);
                break;
            case "heighestfinisheducation":
                addRow($field->fieldtitle, $this->resume->heighesteducationtitle, $y, $pdf, $field->published);
                break;
            case "date_start":
                $date_start = '';
                if($this->resume->date_start != ''){
                    $date_start = HTMLHelper::_('date', $this->resume->date_start, $this->config['date_format']);
                }
                addRow($field->fieldtitle, $date_start, $y, $pdf, $field->published);
                break;
            
            case "total_experience":
                addRow($field->fieldtitle, $this->resume->experiencetitle, $y, $pdf, $field->published);
                break;

            case "driving_license":
                $value_var = $this->resume->driving_license == 0 ? Text::_('JNO') : Text::_('JYES') ;
                addRow($field->fieldtitle, $value_var , $y, $pdf, $field->published);
                break;
            case "license_no":
                addRow($field->fieldtitle, $this->resume->license_no, $y, $pdf, $field->published);
                break;

            case "license_country":
                addRow($field->fieldtitle, $this->resume->licensecountryname, $y, $pdf, $field->published);
                break;

            default:
                getResumeUserField($this->resume, $field, $customfieldobj,$y, $pdf);
                break;
        }
    }
}
if (!count($this->addresses) == 0)
    addSection(Text::_('Address'), $y, $pdf, $section_addresses);

if ($section_addresses == 1) {
    $i = 0;
    if(isset($this->addresses) && !empty($this->addresses))
    foreach ($this->addresses as $address) {
        if(!($address instanceof Object)){
            $address = (Object) $address;
        }
        $i++;
        foreach ($fieldsordering['address'] as $field) {
            switch ($field->field) {
                case 'section_address':
                    addSubSection(Text::_('Address'), $y, $pdf, $field->published);
                    break;
                case 'address_city':
                    if ($field->published == 0) {
                        $address->address_cityname = "N/A";
                        $address->address_statename = "N/A";
                        $address->address_countryname = "N/A";
                    }
                    if ($address->address_cityname == '') {
                        $address->address_cityname = "N/A";
                    }
                    if ($address->address_statename == '') {
                        $address->address_statename = "N/A";
                    }
                    if ($address->address_countryname == '') {
                        $address->address_countryname = "N/A";
                    }

                    addRow(Text::_('City'), $address->address_cityname, $y, $pdf, $field->published);
                    addRow(Text::_('State'), $address->address_statename, $y, $pdf, $field->published);
                    addRow(Text::_('Country'), $address->address_countryname, $y, $pdf, $field->published);
                    break;
                case "address_zipcode":
                    if ($field->published == 0 || $address->address_zipcode == '') {
                        $address->address_zipcode = "N/A";
                    }
                    addRow($field->fieldtitle, $address->address_zipcode, $y, $pdf, $field->published);
                    break;
                case "address":
                    if ($field->published == 0 || $address->address == '') {
                        $address->address = "N/A";
                    }
                    addRow($field->fieldtitle, $address->address, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($address, $field, $customfieldobj,$y, $pdf);
                    break;
            }
        }
    }
}
if (!count($this->institutes) == 0)
    addSection(Text::_('Institutes'), $y, $pdf, $section_educations);

if ($section_educations == 1) {
    $i = 0;
    if(isset($this->institutes) && !empty($this->institutes))
    foreach ($this->institutes as $institute) {
        if(!($institute instanceof Object)){
            $institute = (Object) $institute;
        }
        $i++;
        foreach ($fieldsordering['institute'] as $field) {
            switch ($field->field) {
                case 'section_education':
                    addSubSection(Text::_('Institute'), $y, $pdf, $field->published);
                    break;
                case "institute":
                    if ($field->published == 0 || $institute->institute == '') {
                        $institute->institute = "N/A";
                    }
                    addRow($field->fieldtitle, $institute->institute, $y, $pdf, $field->published);
                    break;
                case 'institute_city':
                    if ($field->published == 0) {
                        $institute->institute_cityname = "N/A";
                        $institute->institute_statename = "N/A";
                        $institute->institute_countryname = "N/A";
                    }
                    if ($institute->institute_cityname == '') {
                        $institute->institute_cityname = "N/A";
                    }
                    if ($institute->institute_statename == '') {
                        $institute->institute_statename = "N/A";
                    }
                    if ($institute->institute_countryname == '') {
                        $institute->institute_countryname = "N/A";
                    }

                    addRow(Text::_('City'), $institute->institute_cityname, $y, $pdf, $field->published);
                    addRow(Text::_('State'), $institute->institute_statename, $y, $pdf, $field->published);
                    addRow(Text::_('Country'), $institute->institute_countryname, $y, $pdf, $field->published);
                    break;
                case "institute_address":
                    if ($field->published == 0 || $institute->institute_address == '') {
                        $institute->institute_address = "N/A";
                    }
                    addRow($field->fieldtitle, $institute->institute_address, $y, $pdf, $field->published);
                    break;
                case "institute_certificate_name":
                    if ($field->published == 0 || $institute->institute_certificate_name == '') {
                        $institute->institute_certificate_name = "N/A";
                    }
                    addRow($field->fieldtitle, $institute->institute_certificate_name, $y, $pdf, $field->published);
                    break;
                case "institute_study_area":
                    if ($field->published == 0 || $institute->institute_study_area == '') {
                        $institute->institute_study_area = "N/A";
                    }
                    addRow($field->fieldtitle, $institute->institute_study_area, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($institute, $field, $customfieldobj ,$y, $pdf);
                    break;
            }
        }
    }
}
if (!count($this->employers) == 0)
    addSection(Text::_('Employers'), $y, $pdf, $section_employers);

if ($section_employers == 1) {
    $i = 0;
    if(isset($this->employers) && !empty($this->employers))
    foreach ($this->employers as $employer) {
        if(!($employer instanceof Object)){
            $employer = (Object) $employer;
        }
        $i++;
        foreach ($fieldsordering['employer'] as $field) {
            switch ($field->field) {
                case 'section_employer':
                    addSubSection(Text::_('Employer'), $y, $pdf, $field->published);
                    break;
                case "employer":
                    if ($field->published == 0 || $employer->employer == '') {
                        $employer->employer = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer, $y, $pdf, $field->published);
                    break;
                case "employer_position":
                    if ($field->published == 0 || $employer->employer == '') {
                        $employer->employer = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_position, $y, $pdf, $field->published);
                    break;
                case "employer_resp":
                    if ($field->published == 0 || $employer->employer_resp == '') {
                        $employer->employer_resp = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_resp, $y, $pdf, $field->published);
                    break;
                case "employer_pay_upon_leaving":
                    if ($field->published == 0 || $employer->employer_pay_upon_leaving == '') {
                        $employer->employer_pay_upon_leaving = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_pay_upon_leaving, $y, $pdf, $field->published);
                    break;
                case "employer_supervisor":
                    if ($field->published == 0 || $employer->employer_supervisor == '') {
                        $employer->employer_supervisor = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_supervisor, $y, $pdf, $field->published);
                    break;
                case "employer_from_date":
                    if ($field->published == 0 || $employer->employer_from_date == '') {
                        $employer->employer_from_date = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_from_date, $y, $pdf, $field->published);
                    break;
                case "employer_to_date":
                    if ($field->published == 0 || $employer->employer_to_date == '') {
                        $employer->employer_to_date = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_to_date, $y, $pdf, $field->published);
                    break;
                case "employer_leave_reason":
                    if ($field->published == 0 || $employer->employer_leave_reason == '') {
                        $employer->employer_leave_reason = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_leave_reason, $y, $pdf, $field->published);
                    break;
                case "employer_city":
                    if ($employer->employer_cityname == "") {
                        $employer->employer_cityname = "N/A";
                    }
                    if ($employer->employer_statename == "") {
                        $employer->employer_statename = "N/A";
                    }
                    if ($employer->employer_countryname == "") {
                        $employer->employer_countryname = "N/A";
                    }
                    if (!$field->published) {
                        $employer->employer_cityname = "N/A";
                        $employer->employer_statename = "N/A";
                        $employer->employer_countryname = "N/A";
                    }

                    addRow(Text::_('City'), $employer->employer_cityname, $y, $pdf, $field->published);
                    addRow(Text::_('State'), $employer->employer_statename, $y, $pdf, $field->published);
                    addRow(Text::_('Country'), $employer->employer_countryname, $y, $pdf, $field->published);
                    break;
                case "employer_zip":
                    if ($field->published == 0 || $employer->employer_zip == '') {
                        $employer->employer_zip = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_zip, $y, $pdf, $field->published);
                    break;
                case "employer_phone":
                    if ($field->published == 0 || $employer->employer_phone == '') {
                        $employer->employer_phone = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_phone, $y, $pdf, $field->published);
                    break;
                case "employer_address":
                    if ($field->published == 0 || $employer->employer_address == '') {
                        $employer->employer_address = "N/A";
                    }
                    addRow($field->fieldtitle, $employer->employer_address, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($employer, $field, $customfieldobj,$y, $pdf);
                    break;
            }
        }
    }
}

// section skills
if (isset($this->resume->skills) && $this->resume->skills != '') {
    if ($section_skills == 1) {
        foreach ($fieldsordering['skills'] as $field) {
            switch ($field->field) {
                case 'section_skills':
                    addSection(Text::_('Skills'), $y, $pdf, $field->published);
                    break;
                case "skills":
                    if ($field->published == 0 || $this->resume->skills == '') {
                        $this->resume->skills = "N/A";
                    }
                    addRow($field->fieldtitle, $this->resume->skills, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($this->resume, $field, $customfieldobj, $y, $pdf);
                    break;
            }
        }
    }
}

// section resume
if (isset($this->resume->resume) && $this->resume->resume != '') {
    if ($section_resume == 1) {
        foreach ($fieldsordering['resume'] as $field) {
            switch ($field->field) {
                case 'section_resume':
                    $pdf->SetDrawColor(223, 223, 223);
                    $pdf->SetFillColor(246, 247, 248);
                    $pdf->SetTextColor(60, 60, 60);
                    $pdf->SetLineWidth(1);
                    $pdf->SetFont('DejaVu', '', 13);
                    $pdf->SetLineWidth(0.1);
                    $pdf->MultiCell(190, 8, Text::_('Resume'), 1, 'J', true);
                    $y = $pdf->GetY();
                    break;
                case "resume":
                    if ($field->published == 0 || $this->resume->resume == '') {
                        $this->resume->resume = "N/A";
                    }
                    $this->resume->resume = getJSJobsPHPFunctionsClass()->jsjobs_strip_tags($this->resume->resume);
                    $pdf->SetFont("DejaVu", "", 11);
                    $pdf->SetY($y);
                    $pdf->WriteHTML($this->resume->resume);
                    $pdf->Ln();
                    $oldy1 = $pdf->GetY();
                    $y = $oldy1;
                    break;
                default:
                    getResumeUserField($this->resume, $field, $customfieldobj,$y, $pdf);
                    break;
            }
        }
    }
}

if (!count($this->references) == 0)
    addSection(Text::_('References'), $y, $pdf, $section_references);

if ($section_references == 1) {
    $i = 0;
    if(isset($this->references) && !empty($this->references))
    foreach ($this->references as $reference) {
        if(!($reference instanceof Object)){
            $reference = (Object) $reference;
        }
        $i++;
        foreach ($fieldsordering['reference'] as $field) {
            switch ($field->field) {
                case 'section_reference':
                    addSubSection(Text::_('Reference'), $y, $pdf, $field->published);
                    break;
                case "reference":
                    if ($field->published == 0 || $reference->reference_zipcode == '') {
                        $reference->reference = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference, $y, $pdf, $field->published);
                    break;
                case 'reference_city':
                    if ($field->published == 0) {
                        $reference->reference_cityname = "N/A";
                        $reference->reference_statename = "N/A";
                        $reference->reference_countryname = "N/A";
                    }
                    if ($reference->reference_cityname == '') {
                        $reference->reference_cityname = "N/A";
                    }
                    if ($reference->reference_statename == '') {
                        $reference->reference_statename = "N/A";
                    }
                    if ($reference->reference_countryname == '') {
                        $reference->reference_countryname = "N/A";
                    }

                    addRow(Text::_('City'), $reference->reference_cityname, $y, $pdf, $field->published);
                    addRow(Text::_('State'), $reference->reference_statename, $y, $pdf, $field->published);
                    addRow(Text::_('Country'), $reference->reference_countryname, $y, $pdf, $field->published);
                    break;
                case "reference_name":
                    if ($field->published == 0 || $reference->reference_name == '') {
                        $reference->reference_name = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_name, $y, $pdf, $field->published);
                    break;
                case "reference_zipcode":
                    if ($field->published == 0 || $reference->reference_zipcode == '') {
                        $reference->reference_zipcode = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_zipcode, $y, $pdf, $field->published);
                    break;
                case "reference_address":
                    if ($field->published == 0 || $reference->reference_address == '') {
                        $reference->reference_address = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_address, $y, $pdf, $field->published);
                    break;
                case "reference_phone":
                    if ($field->published == 0 || $reference->reference_phone == '') {
                        $reference->reference_phone = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_phone, $y, $pdf, $field->published);
                    break;
                case "reference_relation":
                    if ($field->published == 0 || $reference->reference_relation == '') {
                        $reference->reference_relation = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_relation, $y, $pdf, $field->published);
                    break;
                case "reference_years":
                    if ($field->published == 0 || $reference->reference_years == '') {
                        $reference->reference_years = "N/A";
                    }
                    addRow($field->fieldtitle, $reference->reference_years, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($reference, $field, $customfieldobj,$y, $pdf);
                    break;
            }
        }
    }
}

if (!count($this->languages) == 0)
    addSection(Text::_('Languages'), $y, $pdf, $section_languages);

if ($section_languages == 1) {
    $i = 0;
    if(isset($this->languages) && !empty($this->languages))
    foreach ($this->languages as $language) {
        if(!($language instanceof Object)){
            $language = (Object) $language;
        }
        $i++;
        foreach ($fieldsordering['language'] as $field) {
            switch ($field->field) {
                case 'section_language':
                    addSubSection(Text::_('Language'), $y, $pdf, $field->published);
                    break;
                case "language":
                    if ($field->published == 0 || $language->language == '') {
                        $language->language = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language, $y, $pdf, $field->published);
                    break;
                case "language_reading":
                    if ($field->published == 0 || $language->language_reading == '') {
                        $language->language_reading = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_reading, $y, $pdf, $field->published);
                    break;
                case "language_writing":
                    if ($field->published == 0 || $language->language_writing == '') {
                        $language->language_writing = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_writing, $y, $pdf, $field->published);
                    break;
                case "language_address":
                    if ($field->published == 0 || $language->language_address == '') {
                        $language->language_address = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_address, $y, $pdf, $field->published);
                    break;
                case "language_understanding":
                    if ($field->published == 0 || $language->language_understanding == '') {
                        $language->language_understanding = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_understanding, $y, $pdf, $field->published);
                    break;
                case "language_relation":
                    if ($field->published == 0 || $language->language_relation == '') {
                        $language->language_relation = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_relation, $y, $pdf, $field->published);
                    break;
                case "language_where_learned":
                    if ($field->published == 0 || $language->language_where_learned == '') {
                        $language->language_where_learned = "N/A";
                    }
                    addRow($field->fieldtitle, $language->language_where_learned, $y, $pdf, $field->published);
                    break;
                default:
                    getResumeUserField($language, $field, $customfieldobj,$y, $pdf);
                    break;
            }
        }
    }
}
?>
<?php

//$filename = $this->resume->first_name . '-' . $this->resume->last_name . '-resume.pdf';
$filename = 'resume.pdf';
$filename = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $filename);
$filename = str_replace(' ', '-', $filename);
$pdf->Output($filename,"D");
die();
?>
