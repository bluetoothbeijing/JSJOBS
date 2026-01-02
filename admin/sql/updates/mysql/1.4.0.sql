UPDATE `#__js_job_config` SET `configvalue` = '1.4.0' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '140' WHERE `configname` = 'versioncode';

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES
('vis_jsnewestjobs_counts', '1', 'jscontrolpanel'),
('newestjobs_counts', '1', 'jscontrolpanel'),
('vis_jsappliedjobs_counts', '1', 'jscontrolpanel'),
('appliedjobs_counts', '1', 'jscontrolpanel'),
('vis_jsmyresumes_counts', '1', 'jscontrolpanel'),
('myresumes_counts', '1', 'jscontrolpanel'),
('vis_jsmyjobsearches_counts', '1', 'jscontrolpanel'),
('myjobsearches_counts', '1', 'jscontrolpanel'),
('vis_jsjobsloginlogout', '1', 'jscontrolpanel'),
('vis_jsjobshortlist', '1', 'jscontrolpanel'),
('jobshortlist', '1', 'jscontrolpanel'),
('vis_emmyjobs_counts', '1', 'emcontrolpanel'),
('myjobs_counts', '1', 'emcontrolpanel'),
('vis_emappliedresume_counts', '1', 'emcontrolpanel'),
('appliedresume_counts', '1', 'emcontrolpanel'),
('vis_emmycompanies_counts', '1', 'emcontrolpanel'),
('mycompanies_counts', '1', 'emcontrolpanel'),
('vis_emmyresumesearches_counts', '1', 'emcontrolpanel'),
('myresumesearches_counts', '1', 'emcontrolpanel'),
('vis_ememploginlogout', '1', 'emcontrolpanel');
