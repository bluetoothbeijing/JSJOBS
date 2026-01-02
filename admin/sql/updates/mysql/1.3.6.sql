SET SQL_MODE='ALLOW_INVALID_DATES';

ALTER TABLE `#__js_job_companies`  
	MODIFY `logo` blob DEFAULT NULL,  
	MODIFY `smalllogo` tinyblob DEFAULT NULL,  
	MODIFY `aboutcompany` mediumblob DEFAULT NULL,  
	MODIFY `description` text DEFAULT NULL,  
	MODIFY `created` datetime NOT NULL DEFAULT current_timestamp(),  
	MODIFY `metadescription` text DEFAULT NULL,  
	MODIFY `metakeywords` text DEFAULT NULL;


ALTER TABLE `#__js_job_coverletters`  
	MODIFY `published` tinyint(1) DEFAULT NULL;

ALTER TABLE `#__js_job_departments`  
	MODIFY `description` text DEFAULT NULL;

ALTER TABLE `#__js_job_emailtemplates`  
	MODIFY `body` text DEFAULT NULL;

ALTER TABLE `#__js_job_employerpackages`  
	MODIFY `companiesallow` int(11) NOT NULL DEFAULT '0',  
	MODIFY `jobsallow` int(11) NOT NULL DEFAULT '0',  
	MODIFY `viewresumeindetails` int(11) NOT NULL DEFAULT '0',  
	MODIFY `resumesearch` int(11) NOT NULL DEFAULT '0',  
	MODIFY `saveresumesearch` int(11) NOT NULL DEFAULT '0',  
	MODIFY `featuredcompaines` int(11) NOT NULL DEFAULT '0',  
	MODIFY `goldcompanies` int(11) NOT NULL DEFAULT '0',  
	MODIFY `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,  
	MODIFY `featuredjobs` int(11) NOT NULL DEFAULT '0',  
	MODIFY `goldjobs` int(11) NOT NULL DEFAULT '0',  
	MODIFY `description` text DEFAULT NULL,  
	MODIFY `virtuemartproductid` int(11) DEFAULT NULL;

ALTER TABLE `#__js_job_fieldsordering`  
	MODIFY `sys` tinyint(1) NOT NULL DEFAULT '0',  
	MODIFY `cannotunpublish` TINYINT(1) NOT NULL DEFAULT '0',  
	MODIFY `userfieldparams` text DEFAULT NULL,  
	MODIFY `search_user` tinyint(1) NOT NULL DEFAULT '0',  
	MODIFY `search_visitor` tinyint(1) NOT NULL DEFAULT '0',  
	MODIFY `cannotsearch` tinyint(1) DEFAULT NULL,  
	MODIFY `showonlisting` tinyint(1) NOT NULL DEFAULT '0',  
	MODIFY `depandant_field` varchar(250) DEFAULT NULL,  
	MODIFY `readonly` tinyint(4) DEFAULT NULL,  
	MODIFY `size` int(11) DEFAULT NULL,  
	MODIFY `maxlength` int(11) DEFAULT NULL,  
	MODIFY `cols` int(11) DEFAULT NULL,  
	MODIFY `rows` int(11) DEFAULT NULL,  
	MODIFY `j_script` text DEFAULT NULL;  

ALTER TABLE `#__js_job_jobs`  
	MODIFY `description` text DEFAULT NULL,  
	MODIFY `qualifications` text DEFAULT NULL,  
	MODIFY `prefferdskills` text DEFAULT NULL,  
	MODIFY `applyinfo` text DEFAULT NULL,  
	MODIFY `created` datetime NOT NULL DEFAULT current_timestamp(),  
	MODIFY `modified` datetime NOT NULL DEFAULT current_timestamp(),  
	MODIFY `startpublishing` datetime NOT NULL DEFAULT current_timestamp(),  
	MODIFY `stoppublishing` datetime NOT NULL DEFAULT current_timestamp(),  
	MODIFY `metadescription` text DEFAULT NULL,  
	MODIFY `metakeywords` text DEFAULT NULL,  
	MODIFY `agreement` text DEFAULT NULL,  
	MODIFY `joblink` varchar(400) DEFAULT NULL,  
	MODIFY `jobapplylink` tinyint(1) DEFAULT NULL;

ALTER TABLE `#__js_job_jobseekerpackages`  
	MODIFY `resumeallow` int(11) NOT NULL DEFAULT '0',  
	MODIFY `coverlettersallow` int(11) NOT NULL DEFAULT '0',  
	MODIFY `applyjobs` int(11) NOT NULL DEFAULT '0',  
	MODIFY `jobsearch` int(11) NOT NULL DEFAULT '0',  
	MODIFY `savejobsearch` int(11) NOT NULL DEFAULT '0',  
	MODIFY `featuredresume` int(11) NOT NULL DEFAULT '0',  
	MODIFY `goldresume` int(11) NOT NULL DEFAULT '0',  
	MODIFY `description` text DEFAULT NULL,  
	MODIFY `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,  
	MODIFY `virtuemartproductid` int(11) DEFAULT NULL;

ALTER TABLE `#__js_job_paymenthistory`  
	MODIFY `packageexpireindays` int(11) DEFAULT NULL,  
	MODIFY `packagedescription` text DEFAULT NULL;

ALTER TABLE `#__js_job_resume`  
	MODIFY `resume` text DEFAULT NULL,  
	MODIFY `skills` text DEFAULT NULL,  
	MODIFY `startgolddate` date DEFAULT NULL,  
	MODIFY `endgolddate` date DEFAULT NULL,  
	MODIFY `startfeatureddate` date DEFAULT NULL,  
	MODIFY `endfeaturedate` date DEFAULT NULL,  
	MODIFY `jobsalaryrangeend` int(11) DEFAULT NULL,  
	MODIFY `desiredsalaryend` int(11) DEFAULT NULL,  
	MODIFY `videotype` tinyint(1) DEFAULT NULL,  
	MODIFY `isnotify` int(11) DEFAULT NULL,  
	MODIFY `notify_type` int(11) DEFAULT NULL;

ALTER TABLE `#__js_job_resumeaddresses`  
	MODIFY `address` text DEFAULT NULL,  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumereferences`  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumeemployers`  
	MODIFY `employer_resp` text DEFAULT NULL,  
	MODIFY `employer_leave_reason` text DEFAULT NULL,  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumefiles`  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumeinstitutes`  
	MODIFY `institute_study_area` text DEFAULT NULL,  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumelanguages`  
	MODIFY `last_modified` datetime DEFAULT NULL;

ALTER TABLE `#__js_job_resumesearches`  
	MODIFY `searchparams` longtext DEFAULT NULL,  
	MODIFY `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_erasedatarequests`  
	MODIFY `message` text DEFAULT NULL;
  
UPDATE `#__js_job_config` SET `configvalue` = '1.3.6' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '136' WHERE `configname` = 'versioncode';
