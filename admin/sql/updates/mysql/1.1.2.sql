UPDATE `#__js_job_fieldsordering` set ordering = ordering + 1 where fieldfor = 3 and ordering > 20;
UPDATE `#__js_job_fieldsordering` set ordering = ordering + 1 where fieldfor = 3 and ordering > 20;
UPDATE `#__js_job_fieldsordering` set ordering = ordering + 1 where fieldfor = 3 and ordering > 20;

INSERT INTO `#__js_job_fieldsordering` set fieldtitle='Driving License', ordering=21, section=1, required=0, sys=0, field='driving_license', isvisitorpublished=1, cannotunpublish=0, published=1, fieldfor=3;
INSERT INTO `#__js_job_fieldsordering` set fieldtitle='License No.', ordering=22, section=1, required=0, sys=0, field='license_no', isvisitorpublished=1, cannotunpublish=0, published=1, fieldfor=3;
INSERT INTO `#__js_job_fieldsordering` set fieldtitle='License Country', ordering=23, section=1, required=0, sys=0, field='license_country', isvisitorpublished=1, cannotunpublish=0, published=1, fieldfor=3;

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('searchjobtag', '4', 'default'), ('companygoldexpiryindays', '5', 'company'), ('companyfeaturedexpiryindays', '5', 'company'), ('resumegoldexpiryindays', '5', 'resume'), ('resumefeaturedexpiryindays', '5', 'resume'), ('subcategory_limit', '5', 'default'), ('email_employer_new_job', '1', 'email'), ('jobs_graph', '1', 'emcontrolpanel'), ('resume_graph', '1', 'emcontrolpanel'), ('box_newestresume', '1', 'emcontrolpanel'), ('box_appliedresume', '1', 'emcontrolpanel'), ('mystuff_area', '1', 'emcontrolpanel'), ('mystats_area', '1', 'emcontrolpanel'), ('vis_jobs_graph', '1', 'emcontrolpanel'), ('vis_resume_graph', '1', 'emcontrolpanel'), ('vis_box_newestresume', '1', 'emcontrolpanel'), ('vis_box_appliedresume', '1', 'emcontrolpanel'), ('vis_mystuff_area', '1', 'emcontrolpanel'), ('vis_mystats_area', '1', 'emcontrolpanel'), ('jsactivejobs_graph', '1', 'jscontrolpanel'), ('jsmystuff_area', '1', 'jscontrolpanel'), ('jsmystats_area', '1', 'jscontrolpanel'), ('jsnewestjobs_box', '1', 'jscontrolpanel'), ('jsappliedresume_box', '1', 'jscontrolpanel'), ('vis_jsactivejobs_graph', '1', 'jscontrolpanel'), ('vis_jsmystuff_area', '1', 'jscontrolpanel'), ('vis_jsmystats_area', '1', 'jscontrolpanel'), ('vis_jsnewestjobs_box', '1', 'jscontrolpanel'), ('vis_jsappliedresume_box', '1', 'jscontrolpanel'),('search_job_reqiuredtravel', '1', 'searchjob'),('search_job_gender', '1', 'searchjob'),('search_job_education', '1', 'searchjob'),('search_job_careerlevel', '1', 'searchjob'),('search_job_workpermit', '1', 'searchjob');
INSERT IGNORE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('search_job_experience', '1', 'searchjob');

ALTER TABLE `#__js_job_companies` ADD COLUMN `endgolddate` date DEFAULT NULL;
ALTER TABLE `#__js_job_companies` ADD COLUMN `endfeatureddate` date DEFAULT NULL;
ALTER TABLE `#__js_job_companies` ADD COLUMN `notifications` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `#__js_job_jobs` ADD COLUMN `notifications` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__js_job_jobs` ADD COLUMN `joblink` varchar(400) NOT NULL;
ALTER TABLE `#__js_job_jobs` ADD COLUMN `jobapplylink` tinyint(1) NOT NULL;
INSERT INTO `#__js_job_fieldsordering` set fieldtitle='Job Apply Link', ordering=43, section='', required=0, sys=0, field='jobapplylink', isvisitorpublished=0, cannotunpublish=0, published=0, fieldfor=2;

ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `jobsubcategory` varchar(60) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `careerlevel` varchar(60) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `currencyid` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `metakeywords` varchar(300) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `salaryrangetype` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `salaryrangestart` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `salaryrangeend` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `requiredtravel` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `experiencemin` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `experiencemax` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `gender` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `agestart` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `ageend` int(11) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `longitude` varchar(50) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `latitude` varchar(50) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `radius` varchar(5) NOT NULL;
ALTER TABLE `#__js_job_jobsearches` ADD COLUMN `radiuslengthtype` tinyint(1) NOT NULL;

ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `category` varchar(60) null;
ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `jobtype` varchar(60) null;
ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `jobstatus` varchar(60) null;
ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `company` varchar(60) null;
ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `heighesteducation` varchar(60) null;
ALTER TABLE `#__js_job_jobsearches` MODIFY COLUMN `shift` varchar(60) null;

ALTER TABLE `#__js_job_jobsearches` DROP COLUMN `experience`;

ALTER TABLE `#__js_job_resume` ADD COLUMN `experienceid` int(11) DEFAULT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `startgolddate` date NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `endgolddate` date NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `startfeatureddate` date NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `endfeaturedate` date NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `notifications` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__js_job_resume` ADD COLUMN `jobsalaryrangestart` int(11) DEFAULT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `jobsalaryrangeend` int(11) NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `desiredsalarystart` int(11) DEFAULT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `desiredsalaryend` int(11) NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `videotype` tinyint(1) NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `isnotify` int(11) NOT NULL;
ALTER TABLE `#__js_job_resume` ADD COLUMN `notify_type` int(11) NOT NULL;

ALTER TABLE `#__js_job_resumeinstitutes` ADD COLUMN `fromdate` datetime DEFAULT NULL;
ALTER TABLE `#__js_job_resumeinstitutes` ADD COLUMN `todate` datetime DEFAULT NULL;
ALTER TABLE `#__js_job_resumeinstitutes` ADD COLUMN `iscontinue` tinyint(4) DEFAULT NULL;

ALTER TABLE `#__js_job_resumelanguages` MODIFY COLUMN `language_where_learned` varchar(250) null;

INSERT INTO `#__js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (NULL, '0', 'job-new-employer', NULL, 'JS Jobs : New Job {JOB_TITLE} has beed received', '<div style="background: #6DC6DD; height: 20px;"> </div>
<p style="color: #2191ad;">Dear {EMPLOYER_NAME} ,</p>
<p style="color: #4f4f4f;">We receive new job from you detail as below.</p>
<p style="color: #4f4f4f;">Job: {JOB_TITLE}.</p>
<p style="color: #4f4f4f;">Company: {COMPANY_NAME}.</p>
<p style="color: #4f4f4f;">Department: {DEPARTMENT_NAME}.</p>
<p style="color: #4f4f4f;">Category: {CATEGORY_TITLE}.</p>
<p style="color: #4f4f4f;">Job Type: {JOB_TYPE_TITLE}.</p>
<p style="color: #4f4f4f;">Status: {JOB_STATUS}.</p>
<p style="color: #4f4f4f;">Click here to view  {JOB_LINK}.</p>
<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">
<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wonot receive your reply!</p>
</div>', NULL, '2009-08-18 16:46:16');


ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `firstname` varchar(100) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `middlename` varchar(100) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `lastname` varchar(100) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `currency` int(11) DEFAULT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `salaryrangestart` int(11) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `salaryrangeend` int(11) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `salaryrangetype` int(11) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `keywords` varchar(200) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `zipcode` varchar(10) NOT NULL;
ALTER TABLE `#__js_job_resumesearches` ADD `experiencemin` INT( 11 ) NOT NULL AFTER `searchcity`;
ALTER TABLE `#__js_job_resumesearches` ADD `experiencemax` INT( 11 ) NOT NULL AFTER `experiencemin`;
ALTER TABLE `#__js_job_resumesearches` ADD `subcategoryid` INT( 11 ) NOT NULL AFTER `experiencemax`;
ALTER TABLE `#__js_job_resumesearches` DROP COLUMN `experience`;
ALTER TABLE `#__js_job_resumesearches` DROP COLUMN `salaryrange`;


UPDATE `#__js_job_config` SET `configvalue` = '' WHERE `configname`='authentication_client_key';
UPDATE `#__js_job_config` SET `configvalue` = '1.1.2' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '112' WHERE `configname` = 'versioncode';