INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('isenabled_virtuemart', '0', 'virtuemart');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('vmjsbasic', 'CiAgICAgICAgJHF1ZXJ5ID0gIlNFTEVDVCBjb25maWd2YWx1ZSBGUk9NIGAjX19qc19qb2JfY29uZmlnYCBXSEVSRSBgY29uZmlnbmFtZWA9J3ZtanNsYSciOwogICAgICAgIA==', NULL);
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('isenabled_jomsocial', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_postjob', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_postresume', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_postcompany', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jmjsbasic', 'JHF1ZXJ5ID0gIlNFTEVDVCBjb25maWd2YWx1ZSBGUk9NIGAjX19qc19qb2JfY29uZmlnYCBXSEVSRSBgY29uZmlnbmFtZWA9J2ptanNsYSciOw==', NULL);
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_allowpostcompany', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_allowpostjob', '0', 'jomsocial');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('jomsocial_allowpostresume', '0', 'jomsocial');

ALTER TABLE `#__js_job_fieldsordering` ADD `issocialpublished` tinyint(1) NOT NULL DEFAULT '0' AFTER `cannotunpublish`;
UPDATE `#__js_job_fieldsordering` SET `issocialpublished` = 1 WHERE `fieldfor`=1 AND `field` IN ('name','jobcategory','url','description','logo','city','address1');
UPDATE `#__js_job_fieldsordering` SET `issocialpublished` = 1 WHERE `fieldfor`=2 AND `field` IN ('jobtitle','company','department','jobcategory','jobtype','jobstatus','gender','age','heighesteducation','experience','noofjobs','startpublishing','stoppublishing','city','description','qualifications','prefferdskills','jobapplylink');
UPDATE `#__js_job_fieldsordering` SET `issocialpublished` = 1 WHERE `fieldfor`=3 AND `field` IN ('section_personal','application_title','first_name','last_name','gender','photo','job_category','jobtype','heighestfinisheducation','total_experience','date_start','section_address','address_city','address','section_institute','institute','institute_city','institute_address','institute_study_area','section_employer','employer','employer_position','employer_from_date','employer_to_date','employer_city','employer_address','section_skills','skills','section_resume','resume','section_reference','reference','reference_name','reference_city','reference_relation','section_language','language');


ALTER TABLE `#__js_job_employerpackages` ADD `virtuemartproductid` int(11) NULL;
ALTER TABLE `#__js_job_jobseekerpackages` ADD `virtuemartproductid` int(11) NULL;

UPDATE `#__js_job_config` SET `configvalue` = '1.2.3' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '123' WHERE `configname` = 'versioncode';




