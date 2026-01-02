ALTER TABLE `#__js_job_jobs_temp` ADD COLUMN companylogo VARCHAR(100) null AFTER companyaliasid;

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('number_of_cities_for_autocomplete', '15', 'default');

UPDATE `#__js_job_config` SET configname = 'tmenu_emsearchresume' WHERE configname = 'tmenu_emappliedresume';

UPDATE `#__js_job_config` SET `configname` = 'tmenu_vis_emsearchresume' WHERE `configname` = 'tmenu_vis_emappliedresume';

UPDATE `#__js_job_config` SET `configvalue` = '1.1.0.0' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '1100' WHERE `configname` = 'versioncode';

