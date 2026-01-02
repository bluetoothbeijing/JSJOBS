INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('company_seo', '[name][location]', 'seo'), ('resume_seo', '[title][location]', 'seo'), ('job_seo', '[title][company][location]', 'seo');
INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES ('register_employer_redirect_page', '', 'register'),('register_jobseeker_redirect_page', '', 'register'),('default_login_from', '1', 'default');

UPDATE `#__js_job_config` SET `configvalue` = '1.2.5' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '125' WHERE `configname` = 'versioncode';
