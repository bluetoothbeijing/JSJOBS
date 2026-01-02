ALTER TABLE `#__js_job_fieldsordering` ADD `search_ordering` int(11) NULL AFTER `cannotsearch`;

UPDATE `#__js_job_fieldsordering` set search_ordering = ordering WHERE cannotsearch=0;

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('showtotalnjobs', '1', 'default');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('visitorview_emp_resume', '1', 'default');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('show_fe_tellafriend_button', '1', 'job');

ALTER TABLE `#__js_job_resumefiles` CHANGE `filetype` `filetype` varchar(255) COLLATE 'latin1_swedish_ci' NULL AFTER `filename`;

UPDATE `#__js_job_config` SET `configvalue` = '1.2.2' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '122' WHERE `configname` = 'versioncode';
