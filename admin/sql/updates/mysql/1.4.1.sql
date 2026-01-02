UPDATE `#__js_job_config` SET `configvalue` = '1.4.1' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '141' WHERE `configname` = 'versioncode';

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES
('default_register_from', '1', 'default'),
('register_custom_link', '', 'default'),
('login_custom_link', '', 'default');
