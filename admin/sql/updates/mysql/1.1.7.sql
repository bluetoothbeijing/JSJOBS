INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES 
	('show_fe_goldjob_button', '1', 'job'), 
	('show_fe_featuredjob_button', '1', 'job'), 
	('show_fe_goldcompany_button', '1', 'company'), 
	('show_fe_featuredcompany_button', '1', 'company'),
	('show_fe_goldresume_button', '1', 'resume'),
	('show_fe_featuredresume_button', '1', 'resume'),
	('companydefaultlogopath', '', 'job');

DELETE FROM `#__js_job_fieldsordering`
	WHERE id = 317 AND field = 'section_moreoptions' AND section = 1 AND fieldfor = 3;

UPDATE `#__js_job_fieldsordering` 
	SET `fieldtitle` = 'Institute city' 
	WHERE `fieldtitle` =  'institute_city' AND id = 874 AND section =  3 AND fieldfor = 3;

UPDATE `#__js_job_fieldsordering` SET `section` = 7 
	WHERE id = 899 AND section = 80 AND fieldfor = 3;

UPDATE `#__js_job_config` SET `configvalue` = '1.1.7' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '117' WHERE `configname` = 'versioncode';
