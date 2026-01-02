INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('vis_emuserdata_employer', '0', 'default');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('vis_jsuserdata_jobseeker', '0', 'default');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('userdata_employer', '1', 'emcontrolpanel');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('userdata_jobseeker', '1', 'jscontrolpanel');

CREATE TABLE IF NOT EXISTS `#__js_job_erasedatarequests` (`id` int(11) NOT NULL AUTO_INCREMENT,`uid` int(11) NOT NULL,`subject` varchar(250) NOT NULL,`message` text NOT NULL,`status` int(11) NOT NULL,`created` datetime NOT NULL,PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__js_job_emailtemplates_config` (`id`, `emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES (NULL, 'erase_data', '0', '1', '1', '0', '0');
INSERT INTO `#__js_job_emailtemplates_config` (`id`, `emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES (NULL, 'receive_erase_data_request', '1', '0', '0', '0', '0');

INSERT INTO `#__js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (NULL, 0, 'erase-user-data', NULL, '{SITE_TITLE}: User Erase Data Request', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n<p style=\"color: #2191ad;\">Hello {USERNAME} ,</p>\r\n<p style=\"color: #4f4f4f;\">Your data has been erased successfully.</p>\r\n<p style=\"color: #4f4f4f;\"><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /> This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\"> </div>', NULL, '2020-04-02 07:28:47');
INSERT INTO `#__js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (NULL, 0, 'erase-date-request-receive', NULL, '{SUBJECT}', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n<p style=\"color: #2191ad;\">Hello admin,</p>\r\n<p style=\"color: #4f4f4f;\">{NAME} has been requested to erase data.</p>\r\n<p style=\"color: #4f4f4f;\"><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /> This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\"> </div>', NULL, '2020-04-02 07:28:47');

UPDATE `#__js_job_config` SET `configvalue` = '1.3.0' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '130' WHERE `configname` = 'versioncode';
