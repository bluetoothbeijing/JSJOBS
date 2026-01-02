alter table `#__js_job_ages` add column isdefault tinyint(1) after status;
alter table `#__js_job_ages` add column ordering int(11) after isdefault;
update `#__js_job_ages`  set  isdefault=0;
update `#__js_job_ages`  set  ordering=id;

alter table `#__js_job_careerlevels` add column isdefault tinyint(1) after status;
alter table `#__js_job_careerlevels` add column ordering int(11) after isdefault;
update `#__js_job_careerlevels`  set  isdefault=0;
update `#__js_job_careerlevels`  set  ordering=id;

alter table `#__js_job_categories` add column isdefault tinyint(1) after isactive;
alter table `#__js_job_categories` add column ordering int(11) after isdefault;
update `#__js_job_categories`  set  isdefault=0;
update `#__js_job_categories`  set  ordering=id;

alter table `#__js_job_currencies` add column isdefault tinyint(1) after status;
alter table `#__js_job_currencies` add column ordering int(11) after isdefault;
update `#__js_job_currencies`  set  isdefault=0;
update `#__js_job_currencies`  set  ordering=id;


alter table `#__js_job_experiences` add column isdefault tinyint(1) after status;
alter table `#__js_job_experiences` add column ordering int(11) after isdefault;
update `#__js_job_experiences`  set  isdefault=0;
update `#__js_job_experiences`  set  ordering=id;

alter table `#__js_job_heighesteducation` add column isdefault tinyint(1) after isactive;
alter table `#__js_job_heighesteducation` add column ordering int(11) after isdefault;
update `#__js_job_heighesteducation`  set  isdefault=0;
update `#__js_job_heighesteducation`  set  ordering=id;

alter table `#__js_job_jobstatus` add column isdefault tinyint(1) after isactive;
alter table `#__js_job_jobstatus` add column ordering int(11) after isdefault;
update `#__js_job_jobstatus`  set  isdefault=0;
update `#__js_job_jobstatus`  set  ordering=id;

alter table `#__js_job_jobtypes` add column isdefault tinyint(1) after isactive;
alter table `#__js_job_jobtypes` add column ordering int(11) after isdefault;
update `#__js_job_jobtypes`  set  isdefault=0;
update `#__js_job_jobtypes`  set  ordering=id;

alter table `#__js_job_salaryrange` add column status tinyint(1) after serverid;
alter table `#__js_job_salaryrange` add column isdefault tinyint(1) after serverid;
alter table `#__js_job_salaryrange` add column ordering int(11) after isdefault;
update `#__js_job_salaryrange`  set  status=1;
update `#__js_job_salaryrange`  set  isdefault=0;
update `#__js_job_salaryrange`  set  ordering=id;

alter table `#__js_job_salaryrangetypes` add column isdefault tinyint(1) after status;
alter table `#__js_job_salaryrangetypes` add column ordering int(11) after isdefault;
update `#__js_job_salaryrangetypes`  set  isdefault=0;
update `#__js_job_salaryrangetypes`  set  ordering=id;

alter table `#__js_job_shifts` add column isdefault tinyint(1) after isactive;
alter table `#__js_job_shifts` add column ordering int(11) after isdefault;
update `#__js_job_shifts`  set  isdefault=0;
update `#__js_job_shifts`  set  ordering=id;

alter table `#__js_job_subcategories` add column isdefault tinyint(1) after status;
alter table `#__js_job_subcategories` add column ordering int(11) after isdefault;
update `#__js_job_subcategories`  set  isdefault=0;
update `#__js_job_subcategories`  set  ordering=id;

alter table `#__js_job_resume` add column jobsalaryrangetype int(11) after jobsalaryrange;
alter table `#__js_job_resume` add column djobsalaryrangetype int(11) after desired_salary;

INSERT INTO  `#__js_job_fieldsordering` VALUES('','filter','Filter',35,'',2,1,1,0,0,0),('','emailsetting','Email Setting',36,'',2,1,1,0,0,0) ;

INSERT INTO  `#__js_job_config`  VALUES('image_file_type','gif,jpg,jpeg,png','default');
INSERT INTO `#__js_job_config`  VALUES('document_file_type','txt,doc,docx,Pdf,opt,rtf','default');


update`#__js_job_fieldsordering` set sys=0;
update `#__js_job_fieldsordering` set sys=1 where fieldfor=1 AND ( field='jobcategory' or field='name');
update `#__js_job_fieldsordering` set required=sys where fieldfor=1;

update `#__js_job_fieldsordering` set cannotunpublish=0 where fieldfor=2 AND (field='jobstatus' or field='experience' or field='heighesteducation') ;
update `#__js_job_fieldsordering` set cannotunpublish=1 where fieldfor=2 AND field='description';

update `#__js_job_fieldsordering` set sys=cannotunpublish where fieldfor=2;
update `#__js_job_fieldsordering` set required=sys where fieldfor=2;

update `#__js_job_fieldsordering` set cannotunpublish=0 where fieldfor=3 AND (field='startdate' or field='totalexperience' or field='heighesteducation' or field='jobtype' or field='salary' or field='category' or field='subcategory') ;
update `#__js_job_fieldsordering` set sys=cannotunpublish where fieldfor=3;
update `#__js_job_fieldsordering` set required=sys where fieldfor=3;


INSERT INTO `#__js_job_config` VALUES('showgoldjobsinsearchjobs','','default'),('showfeaturedjobsinsearchjobs','','default'),('noofgoldjobsinsearch','','default'),('nooffeaturedjobsinsearch','','default'),('jobsloginlogout',1,'jscontrolpanel'),('emploginlogout',1,'emcontrolpanel'),('showapplybutton',1,'default'),('system_timeout','00:00','default'),('captchause','00:00','default'),('applybuttonredirecturl','index.php','default');

UPDATE `#__js_job_config` AS config  SET configvalue='1.0.9.1' WHERE config.configname='version';
UPDATE `#__js_job_config` AS config  SET configvalue='1091' WHERE config.configname='versioncode';
