REPLACE INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('google_map_api_key', '', 'default');
UPDATE `#__js_job_fieldsordering` SET sys = 0, cannotunpublish = 0 WHERE field="jobcategory" AND fieldfor = 1;

UPDATE `#__js_job_config` SET `configvalue` = '1.1.4' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '114' WHERE `configname` = 'versioncode';