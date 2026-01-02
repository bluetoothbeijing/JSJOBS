ALTER TABLE `#__js_job_fieldsordering` 
  ADD `isuserfield` tinyint(1) NOT NULL,
  ADD `userfieldtype` varchar(250) NOT NULL,
  ADD `userfieldparams` text NOT NULL,
  ADD `search_user` tinyint(1) NOT NULL,
  ADD `search_visitor` tinyint(1) NOT NULL,
  ADD `cannotsearch` tinyint(1) NOT NULL,
  ADD `showonlisting` tinyint(1) NOT NULL,
  ADD `cannotshowonlisting` tinyint(1) NOT NULL,
  ADD `depandant_field` varchar(250) NOT NULL,
  ADD `readonly` tinyint(4) NOT NULL,
  ADD `size` int(11) NOT NULL,
  ADD `maxlength` int(11) NOT NULL,
  ADD `cols` int(11) NOT NULL,
  ADD `rows` int(11) NOT NULL,
  ADD `j_script` text NOT NULL;

ALTER TABLE `#__js_job_jobs` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_companies` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resume` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumeaddresses` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumeemployers` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumeinstitutes` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumelanguages` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumereferences` ADD `params` longtext DEFAULT NULL;

ALTER TABLE `#__js_job_resumesearches` ADD `searchparams` longtext NOT NULL, ADD `params` longtext NOT NULL;

ALTER TABLE `#__js_job_jobsearches` ADD `searchparams` longtext NOT NULL, ADD `params` longtext NOT NULL;

CREATE TABLE IF NOT EXISTS `#__js_job_emailtemplates_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailfor` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `employer` tinyint(1) NOT NULL,
  `jobseeker` tinyint(1) NOT NULL,
  `jobseeker_visitor` tinyint(1) NOT NULL,
  `employer_visitor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

INSERT INTO `#__js_job_emailtemplates_config` (`id`, `emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES
(10, 'applied_resume_status', 1, 1, 1, 1, 1),
(9, 'jobapply_jobapply', 1, 1, 1, 1, 1),
(8, 'add_new_job_visitor', 1, 1, 1, 1, 1),
(7, 'add_new_resume_visitor', 1, 1, 1, 1, 1),
(6, 'resume_status', 1, 1, 1, 1, 1),
(5, 'add_new_resume', 1, 1, 1, 1, 1),
(4, 'job_status', 1, 1, 1, 1, 1),
(3, 'add_new_job', 1, 1, 0, 1, 1),
(2, 'company_status', 1, 1, 0, 1, 1),
(1, 'add_new_company', 1, 1, 1, 1, 1),
(11, 'employer_purchase_package', 1, 1, 1, 1, 1),
(12, 'jobseeker_purchase_package', 1, 1, 1, 1, 1),
(13, 'employer_purchase', 1, 1, 1, 1, 1),
(14, 'jobseeker_purchase', 1, 1, 1, 1, 0),
(16, 'resume_status_visitor', 1, 1, 1, 1, 1),
(17, 'job_status_visitor', 1, 1, 1, 1, 1),
(18, 'add_new_department', 1, 1, 1, 1, 1),
(19, 'company_delete', 1, 1, 1, 1, 1),
(20, 'company_delete', 1, 1, 1, 1, 1),
(21, 'job_delete', 1, 1, 1, 1, 1),
(22, 'resume_delete', 1, 1, 1, 1, 1);

INSERT INTO `#__js_job_emailtemplates` ( `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
(0, 'company-delete', NULL, 'JS Jobs: Your Company {COMPANY_NAME} has been deleted', '<div style="background: #6DC6DD; height: 20px;"></div>\n<p style="color: #2191ad;">Dear {COMPANY_OWNER_NAME} ,</p>\n<p style="color: #4f4f4f;">Your company <strong>{COMPANY_NAME}</strong> has been deleted.</p>\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply! </p>\n</div>\n', NULL, '2009-08-17 17:54:48'),
( 0, 'job-delete', NULL, 'JS Jobs: Your job {JOB_TITLE} has been deleted.', '<div style="background: #6DC6DD; height: 20px;"></div>\n<p style="color: #2191ad;">Dear {EMPLOYER_NAME} ,</p>\n<p style="color: #4f4f4f;">{COMPANY_NAME} job <strong>{JOB_TITLE}</strong> has been deleted.</p>\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-17 22:12:43'),
( 0, 'resume-delete', '', 'JS Jobs: Your Resume {RESUME_TITLE} has been deleted', '<div style="background: #6DC6DD; height: 20px;"></div>\n\n<p style="color: #2191ad;">\nDear {JOBSEEKER_NAME} ,</p>\n<p style="color: #4f4f4f;">\nYour Resume <strong>{RESUME_TITLE}</strong> has been deleted.</p>\n\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\n\n<p><span style="color: red;">\n<strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span>\n<br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!\n</p>\n</div>\n', NULL, '2009-08-17 17:54:48'),
( 0, 'jobapply-jobseeker', NULL, 'JS Jobs: Applied for {JOB_TITLE} job', '<div style="background: #6DC6DD; height: 20px;"></div>\n<p style="color: #2191ad;">Hello {JOBSEEKER_NAME} ,</p>\n<p style="color: #4f4f4f;">you have to applied for <strong>{JOB_TITLE}</strong> job in <strong>{COMPANY_NAME}</strong> company by <strong>{RESUME_TITLE}</strong> resume</p>\n<p style="color: #4f4f4f;">your resume has been {RESUME_APPLIED_STATUS} .</p>\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-18 16:46:16'),
( 0, 'resume-new-vis', NULL, 'JS Jobs:  New resume {RESUME_TITLE} has beed received', '<div style="background: #6DC6DD; height: 20px;"></div>\n<p style="color: #2191ad;">Hello {JOBSEEKER_NAME} ,</p>\n<p style="color: #4f4f4f;">We receive new resume.<strong>{RESUME_TITLE}</strong></p>\n<p style="color: #4f4f4f;">your resume has been <strong>{RESUME_STATUS}</strong></p>\n<p style="color: #4f4f4f;">Click here to view {RESUME_LINK}.</p>\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');


INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('last_version', '114', 'default'), ('last_step_updater', '1150', 'default');

UPDATE `#__js_job_categories` SET alias = replace(alias , '&' , 'and');

UPDATE `#__js_job_subcategories` SET alias = REPLACE(alias, '&', 'and');

UPDATE `#__js_job_fieldsordering` SET `sys` = '1' , required = 1 WHERE fieldfor = 1 AND field = 'contactemail';

CREATE TABLE `#__js_job_userfields_bak` LIKE `#__js_job_userfields`;
INSERT `#__js_job_userfields_bak` SELECT * FROM `#__js_job_userfields`;

CREATE TABLE `#__js_job_userfield_data_bak` LIKE `#__js_job_userfield_data`;
INSERT `#__js_job_userfield_data_bak` SELECT * FROM `#__js_job_userfield_data`;

CREATE TABLE `#__js_job_userfieldvalues_bak` LIKE `#__js_job_userfieldvalues`;
INSERT `#__js_job_userfieldvalues_bak` SELECT * FROM `#__js_job_userfieldvalues`;


UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 302 and id <= 303;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 1, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 304 and id <= 305;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 1, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 304 and id <= 305;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 0, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 308;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 309;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 306;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 307;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 1, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 310;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 311;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 312;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 0, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 313;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 314;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 315 and id <= 316;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 318 and id <= 320;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 323;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 324;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 325 and id <= 326;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 0, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 327;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 0, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 521;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0 , showonlisting = 1, search_user = 1, search_visitor = 1 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id = 522;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1 , showonlisting = 0, search_user = 0, search_visitor = 0 WHERE fieldfor = 3 and section = 1 and isuserfield = 0 and id >= 913 and id <= 915;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 2 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 3 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 4 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 5 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 6 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 7 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 3 and section = 8 and isuserfield = 0;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 1 and isuserfield = 0 and id >= 4 and id <= 11;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 1 WHERE fieldfor = 1 and isuserfield = 0 and id >= 1 and id <= 3;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 1 WHERE fieldfor = 1 and isuserfield = 0 and id = 12;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 1 and isuserfield = 0 and id = 20;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 1 and isuserfield = 0 and id = 23;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 1 and isuserfield = 0 and id = 25;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 1 and isuserfield = 0 and id = 26;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 101;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 102;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 103;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 104;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 105;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 106;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 107;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 108;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 109;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 110;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id >= 111 and id <= 113;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 0 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 114;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id >= 115 and id <= 118;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 119;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id >= 120 and id <= 122;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 1 WHERE fieldfor = 2 and isuserfield = 0 and id = 125;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 0, search_user = 1, search_visitor = 1, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 127;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id >= 130 and id <= 132;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 137;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 864;
UPDATE `#__js_job_fieldsordering` SET cannotshowonlisting = 1 , cannotsearch = 1, search_user = 0, search_visitor = 0, showonlisting = 0 WHERE fieldfor = 2 and isuserfield = 0 and id = 916;
UPDATE `#__js_job_fieldsordering` SET fieldtitle = 'Highest Education' WHERE field LIKE '%heighest%';

UPDATE `#__js_job_config` SET `configvalue` = '1.1.5' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '115' WHERE `configname` = 'versioncode';
