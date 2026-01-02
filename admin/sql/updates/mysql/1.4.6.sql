SET SQL_MODE='ALLOW_INVALID_DATES';

UPDATE `#__js_job_config` SET `configvalue` = '1.4.6' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '146' WHERE `configname` = 'versioncode';




ALTER TABLE `#__js_job_resume` ADD airesumesearchtext MEDIUMTEXT NULL AFTER params, ADD FULLTEXT airesumesearchtext (airesumesearchtext);
ALTER TABLE `#__js_job_resume` ADD airesumesearchdescription MEDIUMTEXT NULL AFTER airesumesearchtext, ADD FULLTEXT airesumesearchdescription (airesumesearchdescription);
ALTER TABLE `#__js_job_resume` ENGINE = INNODB;


ALTER TABLE `#__js_job_jobs` ENGINE = INNODB;
ALTER TABLE `#__js_job_jobs` ADD aijobsearchtext MEDIUMTEXT NULL AFTER params, ADD FULLTEXT aijobsearchtext (aijobsearchtext);
ALTER TABLE `#__js_job_jobs` ADD aijobsearchdescription MEDIUMTEXT NULL AFTER aijobsearchtext, ADD FULLTEXT aijobsearchdescription (aijobsearchdescription);

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('job_search_ai_form', '0', 'job');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('job_list_ai_filter', '0', 'job');

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('show_suggested_jobs_btn', '0', 'job');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('show_suggested_jobs_dashboard', '0', 'job');

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('resume_search_ai_form', '0', 'resume');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('resume_list_ai_filter', '0', 'resume');

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('show_suggested_resumes_btn', '0', 'resume');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('show_suggested_resumes_dashboard', '0', 'resume');

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('admin_list_ai_filter', '0', 'job');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('admin_resume_list_ai_filter', '0', 'job');

INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('admin_show_suggested_resumes_button', '0', 'job');
INSERT INTO `#__js_job_config` (configname, configvalue, configfor) VALUES ('admin_show_update_data_base_message', '1', 'jsjob');
