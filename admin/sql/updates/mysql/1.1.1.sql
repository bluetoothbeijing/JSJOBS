INSERT INTO `#__js_job_config`(`configname`,`configvalue`,`configfor`) VALUES ('max_addresses_in_resumeform', '3', 'resume'), ('max_employers_in_resumeform', '3', 'resume'), ('max_files_in_resumeform', '3', 'resume'), ('max_institutes_in_resumeform', '3', 'resume'), ('max_languages_in_resumeform', '3', 'resume'), ('max_references_in_resumeform', '3', 'resume'), ('visitor_can_add_resume', '1', 'default'), ('document_file_size', '500', 'default'), ('document_max_files', '5', 'default'), ('max_resume_addresses', '4', 'default'), ('max_resume_institutes', '6', 'default'), ('max_resume_employers', '5', 'default'), ('max_resume_references', '10', 'default'), ('max_resume_languages', '8', 'default'), ('show_only_section_that_have_value', '0', 'resume'), ('email_jobseeker_package_purchase', '1', 'email'), ('email_employer_package_purchase', '1', 'email'), ('listjobbytype', '1', 'jscontrolpanel'), ('listallcompanies', '1', 'jscontrolpanel'), ('vis_jslistjobbytype', '1', 'default'), ('vis_jslistallcompanies', '1', 'default'), ('vis_jslistjobshortlist', '1', 'default'), ('listjobshortlist', '1', 'jscontrolpanel'), ('currency_align', '1', 'default'), ('search_resume_location', '1', 'searchresume'), ('newtyped_cities', '0', 'default');
ALTER TABLE `#__js_job_resumesearches` ADD COLUMN `searchcity` int null AFTER `experience`;
ALTER TABLE `#__js_job_userfield_data` ADD COLUMN `subreferenceid` int null AFTER `referenceid`;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 301;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 302;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 303;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 304;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 305;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 306;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 307;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 308;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 309;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 310;
INSERT INTO `#__js_job_fieldsordering` VALUES (301, 'section_personal', 'Personal Information', 0, '1', 3, 1, 1, 1, 1, 1), (302, 'application_title', 'Application Title', 1, '1', 3, 1, 1, 1, 1, 1), (303, 'first_name', 'First Name', 2, '1', 3, 1, 1, 1, 1, 1), (304, 'middle_name', 'Middle Name', 3, '1', 3, 1, 1, 0, 0, 0), (305, 'last_name', 'Last Name', 4, '1', 3, 1, 1, 1, 1, 1), (306, 'email_address', 'Email Address', 5, '1', 3, 1, 1, 1, 1, 1), (307, 'cell', 'Cell', 6, '1', 3, 1, 1, 0, 0, 0), (308, 'nationality', 'Nationality', 7, '1', 3, 1, 1, 0, 0, 0), (309, 'gender', 'Gender', 8, '1', 3, 1, 1, 0, 0, 0), (310, 'photo', 'Photo', 9, '1', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 311;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 312;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 313;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 314;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 315;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 316;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 317;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 318;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 319;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 320;
INSERT INTO `#__js_job_fieldsordering` VALUES (311, 'resumefiles', 'File Upload', 10, '1', 3, 1, 1, 0, 0, 0), (312, 'job_category', 'Category', 11, '1', 3, 1, 1, 0, 0, 0), (313, 'job_subcategory', 'Sub Category', 12, '1', 3, 1, 1, 0, 0, 0), (314, 'jobtype', 'Job Type', 13, '1', 3, 1, 1, 0, 0, 0), (315, 'heighestfinisheducation', 'Heighest Education', 14, '1', 3, 1, 1, 0, 0, 0), (316, 'total_experience', 'Total Experience', 16, '1', 3, 1, 1, 0, 0, 0), (317, 'section_moreoptions', 'More Options', 17, '1', 3, 1, 1, 1, 1, 1), (318, 'home_phone', 'Home Phone', 20, '1', 3, 1, 1, 0, 0, 0), (319, 'work_phone', 'Work Phone', 19, '1', 3, 1, 1, 0, 0, 0), (320, 'date_of_birth', 'Date of Birth', 18, '1', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 321;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 325;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 326;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 327;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 328;
INSERT INTO `#__js_job_fieldsordering` VALUES (321, 'date_start', 'Date you can start', 21, '1', 3, 1, 1, 0, 0, 0), (325, 'salary', 'Salary ', 22, '1', 3, 1, 1, 0, 0, 0), (326, 'desired_salary', 'Desire Salary', 23, '1', 3, 1, 1, 0, 0, 0), (327, 'video', 'Video', 24, '1', 3, 1, 1, 0, 0, 0), (328, 'keywords', 'Keywords', 25, '1', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 332;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 333;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 334;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 335;
INSERT INTO `#__js_job_fieldsordering` VALUES (332, 'section_address', 'Add Address', 26, '2', 3, 1, 1, 0, 0, 0), (333, 'address_city', 'City', 30, '2', 3, 1, 1, 0, 0, 1), (334, 'address_zipcode', 'Zip Code', 28, '2', 3, 1, 1, 0, 0, 1), (335, 'address', 'Address', 27, '2', 3, 1, 1, 0, 0, 1);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 339;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 340;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 341;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 342;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 343;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 344;
INSERT INTO `#__js_job_fieldsordering` VALUES (339, 'address_location', 'Location', 31, '2', 3, 1, 1, 0, 0, 1), (340, 'section_institute', 'Education', 31, '3', 3, 1, 1, 0, 0, 0), (341, 'institute', 'Institute', 32, '3', 3, 1, 1, 0, 0, 0), (342, 'institute_city', 'institute_city', 33, '3', 3, 1, 1, 0, 0, 0), (343, 'institute_address', 'Address', 34, '3', 3, 1, 1, 0, 0, 0), (344, 'institute_certificate_name', 'Certificate Name', 35, '3', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 348;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 349;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 350;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 351;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 352;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 353;
INSERT INTO `#__js_job_fieldsordering` VALUES (348, 'institute_study_area', 'Study Area', 36, '3', 3, 1, 1, 0, 0, 0), (349, 'section_employer', 'Employer', 37, '4', 3, 1, 1, 0, 0, 0), (350, 'employer', 'Employer', 38, '4', 3, 1, 1, 0, 0, 0), (351, 'employer_position', 'Position', 39, '4', 3, 1, 1, 0, 0, 0), (352, 'employer_resp', 'Responsibilities', 40, '4', 3, 1, 1, 0, 0, 0), (353, 'employer_pay_upon_leaving', 'Pay Upon Leaving', 41, '4', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 357;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 358;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 359;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 360;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 361;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 362;
INSERT INTO `#__js_job_fieldsordering` VALUES (357, 'employer_supervisor', 'Supervisor', 42, '4', 3, 1, 1, 0, 0, 0), (358, 'employer_from_date', 'From Date', 43, '4', 3, 1, 1, 0, 0, 0), (359, 'employer_to_date', 'To Date', 44, '4', 3, 1, 1, 0, 0, 0), (360, 'employer_leave_reason', 'Leave Reason', 45, '4', 3, 1, 1, 0, 0, 0), (361, 'employer_city', 'City', 46, '4', 3, 1, 1, 0, 0, 0), (362, 'employer_zip', 'Zip Code', 47, '4', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 366;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 367;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 368;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 369;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 370;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 371;
INSERT INTO `#__js_job_fieldsordering` VALUES (366, 'employer_phone', 'Phone', 48, '4', 3, 1, 1, 0, 0, 0), (367, 'employer_address', 'Address', 49, '4', 3, 1, 1, 0, 0, 0), (368, 'section_skills', 'Skills', 50, '5', 3, 1, 1, 0, 0, 0), (369, 'skills', 'Skills', 51, '5', 3, 1, 1, 0, 0, 0), (370, 'section_resume', 'Resume Editor', 52, '6', 3, 1, 1, 0, 0, 0), (371, 'resume', 'Resume Editor', 53, '6', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 375;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 376;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 377;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 378;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 379;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 380;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 381;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 382;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 383;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 384;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 385;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 386;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 387;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 388;
INSERT INTO `#__js_job_fieldsordering` VALUES (375, 'section_reference', 'Add References', 54, '7', 3, 1, 1, 0, 0, 0), (376, 'reference', 'Reference', 55, '7', 3, 1, 1, 0, 0, 0), (377, 'reference_name', 'Reference Name', 56, '7', 3, 1, 1, 0, 0, 0), (378, 'reference_country', 'Reference Country', 57, '7', 3, 1, 1, 0, 0, 0), (379, 'reference_state', 'Reference State', 58, '80', 3, 1, 1, 0, 0, 0), (380, 'reference_city', 'Reference City', 59, '7', 3, 1, 1, 0, 0, 0), (381, 'reference_zipcode', 'Reference Zipcode', 60, '7', 3, 1, 1, 0, 0, 0), (382, 'reference_address', 'Reference Address', 61, '7', 3, 1, 1, 0, 0, 0), (383, 'reference_phone', 'Reference Phone', 62, '7', 3, 1, 1, 0, 0, 0), (384, 'reference_relation', 'Reference Relation', 63, '7', 3, 1, 1, 0, 0, 0), (385, 'reference_years', 'Reference Years', 64, '7', 3, 1, 1, 0, 0, 0), (386, 'section_language', 'Add Language', 65, '8', 3, 1, 1, 0, 0, 0), (387, 'language', 'Language', 66, '8', 3, 1, 1, 0, 0, 0), (388, 'language_reading', 'Language Reading', 67, '8', 3, 1, 1, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 392;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 393;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 394;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 395;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 396;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 397;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 398;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 399;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 400;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 401;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 402;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 403;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 404;
INSERT INTO `#__js_job_fieldsordering` VALUES (392, 'language_writing', 'Language Writing', 68, '8', 3, 1, 1, 0, 0, 0), (393, 'language_understanding', 'Language Understanding', 69, '8', 3, 1, 1, 0, 0, 0), (394, 'language_where_learned', 'Language Where Learned', 70, '8', 3, 1, 1, 0, 0, 0), (395, 'user', 'User', 3, '', 1, 1, 1, 1, 1, 1);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 408;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 409;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 410;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 412;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 413;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 414;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 415;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 416;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 417;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 418;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 419;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 420;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 421;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 426;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 427;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 428;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 429;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 430;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 431;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 432;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 433;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 434;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 435;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 436;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 437;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 438;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 442;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 443;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 444;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 445;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 446;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 447;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 448;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 449;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 450;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 451;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 452;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 453;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 454;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 455;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 456;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 457;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 471;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 472;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 473;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 474;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 475;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 476;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 477;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 478;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 482;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 483;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 484;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 485;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 486;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 487;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 488;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 489;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 493;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 494;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 495;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 496;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 497;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 498;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 499;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 500;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 504;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 505;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 506;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 507;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 508;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 510;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 511;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 512;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 513;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 514;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 515;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 516;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 517;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 518;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 519;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 520;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 521;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 522;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 523;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 524;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 525;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 526;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 527;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 528;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 529;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 530;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 531;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 532;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 533;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 534;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 535;
INSERT INTO `#__js_job_fieldsordering` VALUES (521, 'searchable', 'Searchable', 26, '1', 3, 1, 1, 0, 0, 0), (522, 'iamavailable', 'I am Available', 26, '1', 3, 1, 1, 0, 0, 0), (523, 'jobcategory', 'Job Category', 1, '', 11, 1, 0, 0, 1, 1), (524, 'name', 'Name', 2, '', 11, 1, 0, 0, 1, 0), (525, 'url', 'URL', 3, '', 11, 0, 0, 0, 0, 0), (526, 'contactname', 'Contact Name', 4, '', 11, 1, 0, 0, 1, 0), (527, 'contactphone', 'Contact Phone', 5, '', 11, 0, 0, 0, 0, 0), (528, 'contactemail', 'Contact Email', 6, '', 11, 1, 0, 0, 1, 0), (529, 'since', 'Since', 8, '', 11, 0, 0, 0, 0, 0), (530, 'companysize', 'Company Size', 9, '', 11, 0, 0, 0, 0, 0), (531, 'income', 'Income', 10, '', 11, 1, 0, 0, 0, 0), (532, 'description', 'Description', 11, '', 11, 1, 0, 0, 0, 0), (533, 'address1', 'Address1', 17, '', 11, 1, 0, 0, 0, 0), (534, 'logo', 'Logo', 19, '', 11, 1, 0, 0, 0, 0), (535, 'contactfax', 'Contact Fax', 7, '', 11, 0, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 538;
INSERT INTO `#__js_job_fieldsordering` VALUES (538, 'city', 'City', 14, '', 11, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 540;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 541;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 542;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 543;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 544;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 545;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 546;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 547;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 548;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 549;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 550;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 551;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 552;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 553;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 554;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 555;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 556;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 557;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 558;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 559;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 560;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 561;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 562;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 563;
INSERT INTO `#__js_job_fieldsordering` VALUES (540, 'zipcode', 'Zipcode', 16, '', 11, 1, 0, 0, 0, 0), (541, 'address2', 'Address2', 18, '', 11, 1, 0, 0, 0, 0), (542, 'jobtitle', 'Job Title', 1, '', 12, 1, 0, 0, 1, 1), (543, 'company', 'Company', 2, '', 12, 1, 0, 0, 1, 1), (544, 'department', 'Department', 3, '', 12, 1, 0, 0, 0, 1), (545, 'jobcategory', 'Job Category', 4, '', 12, 1, 0, 0, 1, 1), (546, 'subcategory', 'Sub Category', 5, '', 12, 1, 0, 0, 0, 0), (547, 'jobtype', 'Job Type', 6, '', 12, 1, 0, 0, 1, 1), (548, 'jobstatus', 'Job Status', 7, '', 12, 1, 0, 0, 1, 1), (549, 'gender', 'Gender', 8, '', 12, 1, 0, 0, 0, 0), (550, 'age', 'Age', 9, '', 12, 1, 0, 0, 0, 0), (551, 'jobsalaryrange', 'Job Salary Range', 10, '', 12, 1, 0, 0, 0, 0), (552, 'jobshift', 'Job Shift', 11, '', 12, 1, 0, 0, 0, 0), (553, 'heighesteducation', 'Heighest Education', 12, '', 12, 1, 0, 0, 1, 0), (554, 'experience', 'Experience', 13, '', 12, 1, 0, 0, 1, 0), (555, 'noofjobs', 'No of Jobs', 14, '', 12, 1, 0, 0, 0, 0), (556, 'duration', 'Duration', 15, '', 12, 1, 0, 0, 0, 0), (557, 'careerlevel', 'Career Level', 16, '', 12, 1, 0, 0, 0, 0), (558, 'workpermit', 'Work Permit', 17, '', 12, 1, 0, 0, 0, 0), (559, 'requiredtravel', 'Required Travel', 18, '', 12, 1, 0, 0, 0, 0), (560, 'video', 'Video', 19, '', 12, 1, 0, 0, 0, 0), (561, 'map', 'Map', 20, '', 12, 1, 0, 0, 0, 1), (562, 'startpublishing', 'Start Publishing', 21, '', 12, 1, 0, 0, 1, 1), (563, 'stoppublishing', 'Stop Publishing', 22, '', 12, 1, 0, 0, 1, 1);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 566;
INSERT INTO `#__js_job_fieldsordering` VALUES (566, 'city', 'City', 25, '', 12, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 568;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 569;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 570;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 571;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 572;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 573;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 574;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 575;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 576;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 577;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 578;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 579;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 580;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 581;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 582;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 583;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 584;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 585;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 586;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 587;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 588;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 589;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 590;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 591;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 592;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 593;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 594;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 595;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 596;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 597;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 598;
INSERT INTO `#__js_job_fieldsordering` VALUES (568, 'sendemail', 'Send Email', 27, '', 12, 1, 0, 0, 0, 0), (569, 'sendmeresume', 'Send me Resume', 28, '', 12, 1, 0, 0, 0, 0), (570, 'description', 'Description', 29, '', 12, 1, 0, 0, 0, 0), (571, 'qualifications', 'Qualifications', 30, '', 12, 1, 0, 0, 0, 0), (572, 'prefferdskills', 'Prefered Skills', 31, '', 12, 1, 0, 0, 0, 0), (573, 'agreement', 'Agreement', 32, '', 12, 1, 0, 0, 0, 1), (574, 'metadescription', 'Meta Description', 33, '', 12, 1, 0, 0, 0, 1), (575, 'metakeywords', 'Meta Keywords', 34, '', 12, 1, 0, 0, 0, 1), (576, 'zipcode', 'Zipcode', 26, '', 12, 1, 0, 0, 0, 0), (577, 'section_personal', 'Personal Information', 0, '10', 13, 1, 0, 0, 1, 0), (578, 'applicationtitle', 'Application Title', 1, '10', 13, 1, 0, 0, 1, 0), (579, 'firstname', 'First Name', 2, '10', 13, 1, 0, 0, 1, 0), (580, 'middlename', 'Middle Name', 3, '10', 13, 0, 0, 0, 0, 0), (581, 'lastname', 'Last Name', 4, '10', 13, 1, 0, 0, 1, 0), (582, 'emailaddress', 'Email Address', 5, '10', 13, 1, 0, 0, 1, 0), (583, 'homephone', 'Home Phone', 6, '10', 13, 1, 0, 0, 0, 0), (584, 'workphone', 'Work Phone', 7, '10', 13, 1, 0, 0, 0, 0), (585, 'cell', 'Cell', 8, '10', 13, 1, 0, 0, 0, 0), (586, 'nationality', 'Nationality', 9, '10', 13, 1, 0, 0, 0, 0), (587, 'gender', 'Gender', 10, '10', 13, 1, 0, 0, 0, 0), (588, 'photo', 'Photo', 12, '10', 13, 1, 0, 0, 0, 0), (589, 'fileupload', 'File Upload', 13, '10', 13, 1, 0, 0, 0, 0), (590, 'section_basic', 'Basic Information', 15, '20', 13, 1, 0, 0, 1, 0), (591, 'category', 'Category', 16, '20', 13, 1, 0, 0, 1, 0), (592, 'salary', 'Salary ', 17, '20', 13, 1, 0, 0, 1, 0), (593, 'jobtype', 'Job Type', 18, '20', 13, 1, 0, 0, 1, 0), (594, 'heighesteducation', 'Heighest Education', 19, '20', 13, 1, 0, 0, 1, 0), (595, 'totalexperience', 'Total Experience', 20, '20', 13, 1, 0, 0, 1, 0), (596, 'startdate', 'Date you can start', 21, '20', 13, 1, 0, 0, 1, 0), (597, 'section_addresses', 'Addresses', 42, '30', 13, 1, 0, 0, 0, 0), (598, 'section_sub_address', 'Current Address', 43, '31', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 602;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 603;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 604;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 605;
INSERT INTO `#__js_job_fieldsordering` VALUES (602, 'address_city', 'City', 47, '31', 13, 1, 0, 0, 0, 0), (603, 'address_zipcode', 'Zip Code', 48, '31', 13, 1, 0, 0, 0, 0), (604, 'address_address', 'Address', 49, '31', 13, 1, 0, 0, 0, 0), (605, 'section_sub_address1', 'Address1', 51, '32', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 609;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 610;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 611;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 612;
INSERT INTO `#__js_job_fieldsordering` VALUES (609, 'address1_city', 'City', 55, '32', 13, 1, 0, 0, 0, 0), (610, 'address1_zipcode', 'Zip Code', 56, '32', 13, 1, 0, 0, 0, 0), (611, 'address1_address', 'Address', 57, '32', 13, 1, 0, 0, 0, 0), (612, 'section_sub_address2', 'Address1', 61, '33', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 616;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 617;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 618;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 619;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 620;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 621;
INSERT INTO `#__js_job_fieldsordering` VALUES (616, 'address2_city', 'City', 65, '33', 13, 1, 0, 0, 0, 0), (617, 'address2_zipcode', 'Zip Code', 66, '33', 13, 1, 0, 0, 0, 0), (618, 'address2_address', 'Address', 67, '33', 13, 1, 0, 0, 0, 0), (619, 'section_education', 'Education', 71, '40', 13, 1, 0, 0, 0, 0), (620, 'section_sub_institute', 'High School', 72, '41', 13, 1, 0, 0, 0, 0), (621, 'institute_institute', 'Institute', 73, '41', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 625;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 626;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 627;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 628;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 629;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 630;
INSERT INTO `#__js_job_fieldsordering` VALUES (625, 'institute_city', 'City', 77, '41', 13, 1, 0, 0, 0, 0), (626, 'institute_address', 'Address', 78, '41', 13, 1, 0, 0, 0, 0), (627, 'institute_certificate', 'Certificate Name', 79, '41', 13, 1, 0, 0, 0, 0), (628, 'institute_study_area', 'Study Area', 80, '41', 13, 1, 0, 0, 0, 0), (629, 'section_sub_institute1', 'University', 82, '42', 13, 1, 0, 0, 0, 0), (630, 'institute1_institute', 'Institute', 83, '42', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 634;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 635;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 636;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 637;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 638;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 639;
INSERT INTO `#__js_job_fieldsordering` VALUES (634, 'institute1_city', 'City', 87, '42', 13, 1, 0, 0, 0, 0), (635, 'institute1_address', 'Address', 88, '42', 13, 1, 0, 0, 0, 0), (636, 'institute1_certificate', 'Certificate Name', 89, '42', 13, 1, 0, 0, 0, 0), (637, 'institute1_study_area', 'Study Area', 90, '42', 13, 1, 0, 0, 0, 0), (638, 'section_sub_institute2', 'Grade School', 92, '43', 13, 1, 0, 0, 0, 0), (639, 'institute2_institute', 'Institute', 93, '43', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 643;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 644;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 645;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 646;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 647;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 648;
INSERT INTO `#__js_job_fieldsordering` VALUES (643, 'institute2_city', 'City', 97, '43', 13, 1, 0, 0, 0, 0), (644, 'institute2_address', 'Address', 98, '43', 13, 1, 0, 0, 0, 0), (645, 'institute2_certificate', 'Certificate Name', 99, '43', 13, 1, 0, 0, 0, 0), (646, 'institute2_study_area', 'Study Area', 100, '43', 13, 1, 0, 0, 0, 0), (647, 'section_sub_institute3', 'Other School', 102, '44', 13, 1, 0, 0, 0, 0), (648, 'institute3_institute', 'Institute', 103, '44', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 652;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 653;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 654;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 655;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 656;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 657;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 658;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 659;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 660;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 661;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 662;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 663;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 664;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 665;
INSERT INTO `#__js_job_fieldsordering` VALUES (652, 'institute3_city', 'City', 107, '44', 13, 1, 0, 0, 0, 0), (653, 'institute3_address', 'Address', 108, '44', 13, 1, 0, 0, 0, 0), (654, 'institute3_certificate', 'Certificate Name', 109, '44', 13, 1, 0, 0, 0, 0), (655, 'institute3_study_area', 'Study Area', 110, '44', 13, 1, 0, 0, 0, 0), (656, 'section_employer', 'Employer', 112, '50', 13, 1, 0, 0, 0, 0), (657, 'section_sub_employer', 'Recent Employer', 113, '51', 13, 1, 0, 0, 0, 0), (658, 'employer_employer', 'Employer', 114, '51', 13, 1, 0, 0, 0, 0), (659, 'employer_position', 'Position', 114, '51', 13, 1, 0, 0, 0, 0), (660, 'employer_resp', 'Responsibilities', 115, '51', 13, 1, 0, 0, 0, 0), (661, 'employer_pay_upon_leaving', 'Pay Upon Leaving', 116, '51', 13, 1, 0, 0, 0, 0), (662, 'employer_supervisor', 'Supervisor', 117, '51', 13, 1, 0, 0, 0, 0), (663, 'employer_from_date', 'From Date', 118, '51', 13, 1, 0, 0, 0, 0), (664, 'employer_to_date', 'To Date', 119, '51', 13, 1, 0, 0, 0, 0), (665, 'employer_leave_reason', 'Leave Reason', 120, '51', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 669;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 670;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 671;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 672;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 673;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 674;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 675;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 676;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 677;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 678;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 679;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 680;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 681;
INSERT INTO `#__js_job_fieldsordering` VALUES (669, 'employer_city', 'City', 124, '51', 13, 1, 0, 0, 0, 0), (670, 'employer_zip', 'Zip Code', 125, '51', 13, 1, 0, 0, 0, 0), (671, 'employer_phone', 'Phone', 126, '51', 13, 1, 0, 0, 0, 0), (672, 'employer_address', 'Address', 127, '51', 13, 1, 0, 0, 0, 0), (673, 'section_sub_employer1', 'Prior Employer 1', 128, '52', 13, 1, 0, 0, 0, 0), (674, 'employer1_employer', 'Employer', 129, '52', 13, 1, 0, 0, 0, 0), (675, 'employer1_position', 'Position', 130, '52', 13, 1, 0, 0, 0, 0), (676, 'employer1_resp', 'Responsibilities', 131, '52', 13, 1, 0, 0, 0, 0), (677, 'employer1_pay_upon_leaving', 'Pay Upon Leaving', 132, '52', 13, 1, 0, 0, 0, 0), (678, 'employer1_supervisor', 'Supervisor', 133, '52', 13, 1, 0, 0, 0, 0), (679, 'employer1_from_date', 'From Date', 134, '52', 13, 1, 0, 0, 0, 0), (680, 'employer1_to_date', 'To Date', 135, '52', 13, 1, 0, 0, 0, 0), (681, 'employer1_leave_reason', 'Leave Reason', 136, '52', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 685;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 686;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 687;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 688;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 689;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 690;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 691;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 692;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 693;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 694;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 695;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 696;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 697;
INSERT INTO `#__js_job_fieldsordering` VALUES (685, 'employer1_city', 'City', 140, '52', 13, 1, 0, 0, 0, 0), (686, 'employer1_zip', 'Zip Code', 141, '52', 13, 1, 0, 0, 0, 0), (687, 'employer1_phone', 'Phone', 142, '52', 13, 1, 0, 0, 0, 0), (688, 'employer1_address', 'Address', 143, '52', 13, 1, 0, 0, 0, 0), (689, 'section_sub_employer2', 'Prior Employer 2', 146, '53', 13, 1, 0, 0, 0, 0), (690, 'employer2_employer', 'Employer', 147, '53', 13, 1, 0, 0, 0, 0), (691, 'employer2_position', 'Position', 148, '53', 13, 1, 0, 0, 0, 0), (692, 'employer2_resp', 'Responsibilities', 149, '53', 13, 1, 0, 0, 0, 0), (693, 'employer2_pay_upon_leaving', 'Pay Upon Leaving', 150, '53', 13, 1, 0, 0, 0, 0), (694, 'employer2_supervisor', 'Supervisor', 151, '53', 13, 1, 0, 0, 0, 0), (695, 'employer2_from_date', 'From Date', 152, '53', 13, 1, 0, 0, 0, 0), (696, 'employer2_to_date', 'To Date', 153, '53', 13, 1, 0, 0, 0, 0), (697, 'employer2_leave_reason', 'Leave Reason', 154, '53', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 701;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 702;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 703;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 704;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 705;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 706;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 707;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 708;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 709;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 710;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 711;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 712;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 713;
INSERT INTO `#__js_job_fieldsordering` VALUES (701, 'employer2_city', 'City', 158, '53', 13, 1, 0, 0, 0, 0), (702, 'employer2_zip', 'Zip Code', 159, '53', 13, 1, 0, 0, 0, 0), (703, 'employer2_phone', 'Phone', 160, '53', 13, 1, 0, 0, 0, 0), (704, 'employer2_address', 'Address', 161, '53', 13, 1, 0, 0, 0, 0), (705, 'section_sub_employer3', 'Prior Employer 3', 166, '54', 13, 1, 0, 0, 0, 0), (706, 'employer3_employer', 'Employer', 167, '54', 13, 1, 0, 0, 0, 0), (707, 'employer3_position', 'Position', 168, '54', 13, 1, 0, 0, 0, 0), (708, 'employer3_resp', 'Responsibilities', 169, '54', 13, 1, 0, 0, 0, 0), (709, 'employer3_pay_upon_leaving', 'Pay Upon Leaving', 170, '54', 13, 1, 0, 0, 0, 0), (710, 'employer3_supervisor', 'Supervisor', 171, '54', 13, 1, 0, 0, 0, 0), (711, 'employer3_from_date', 'From Date', 172, '54', 13, 1, 0, 0, 0, 0), (712, 'employer3_to_date', 'To Date', 173, '54', 13, 1, 0, 0, 0, 0), (713, 'employer3_leave_reason', 'Leave Reason', 174, '54', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 717;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 718;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 719;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 720;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 721;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 722;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 723;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 724;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 725;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 726;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 727;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 728;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 729;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 730;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 731;
INSERT INTO `#__js_job_fieldsordering` VALUES (717, 'employer3_city', 'City', 178, '54', 13, 1, 0, 0, 0, 0), (718, 'employer3_zip', 'Zip Code', 179, '54', 13, 1, 0, 0, 0, 0), (719, 'employer3_phone', 'Phone', 180, '54', 13, 1, 0, 0, 0, 0), (720, 'employer3_address', 'Address', 181, '54', 13, 1, 0, 0, 0, 0), (721, 'section_skills', 'Skills', 186, '60', 13, 1, 0, 0, 0, 0), (722, 'driving_license', 'Driving License', 187, '60', 13, 1, 0, 0, 0, 0), (723, 'license_no', 'License No', 188, '60', 13, 1, 0, 0, 0, 0), (724, 'license_country', 'License Country', 189, '60', 13, 1, 0, 0, 0, 0), (725, 'skills', 'Skills', 190, '60', 13, 1, 0, 0, 0, 0), (726, 'section_resumeeditor', 'Resume Editor', 196, '70', 13, 1, 0, 0, 0, 0), (727, 'editor', 'Editor', 197, '70', 13, 1, 0, 0, 0, 0), (728, 'section_references', 'References', 206, '80', 13, 1, 0, 0, 0, 0), (729, 'section_sub_reference', 'Reference 1', 207, '81', 13, 1, 0, 0, 0, 0), (730, 'reference_reference', 'Reference', 208, '81', 13, 1, 0, 0, 0, 0), (731, 'reference_name', 'Name', 209, '81', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 735;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 736;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 737;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 738;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 739;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 740;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 741;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 742;
INSERT INTO `#__js_job_fieldsordering` VALUES (735, 'reference_city', 'City', 213, '81', 13, 1, 0, 0, 0, 0), (736, 'reference_zipcode', 'Zip Code', 214, '81', 13, 1, 0, 0, 0, 0), (737, 'reference_phone', 'Phone', 215, '81', 13, 1, 0, 0, 0, 0), (738, 'reference_relation', 'Relation', 216, '81', 13, 1, 0, 0, 0, 0), (739, 'reference_years', 'Years', 217, '81', 13, 1, 0, 0, 0, 0), (740, 'section_sub_reference1', 'Reference 2', 221, '82', 13, 1, 0, 0, 0, 0), (741, 'reference1_reference', 'Reference', 222, '82', 13, 1, 0, 0, 0, 0), (742, 'reference1_name', 'Name', 223, '82', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 746;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 747;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 748;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 749;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 750;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 751;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 752;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 753;
INSERT INTO `#__js_job_fieldsordering` VALUES (746, 'reference1_city', 'City', 227, '82', 13, 1, 0, 0, 0, 0), (747, 'reference1_zipcode', 'Zip Code', 228, '82', 13, 1, 0, 0, 0, 0), (748, 'reference1_phone', 'Phone', 229, '82', 13, 1, 0, 0, 0, 0), (749, 'reference1_relation', 'Relation', 230, '82', 13, 1, 0, 0, 0, 0), (750, 'reference1_years', 'Years', 231, '82', 13, 1, 0, 0, 0, 0), (751, 'section_sub_reference2', 'Reference 3', 232, '83', 13, 1, 0, 0, 0, 0), (752, 'reference2_reference', 'Reference', 233, '83', 13, 1, 0, 0, 0, 0), (753, 'reference2_name', 'Name', 234, '83', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 757;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 758;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 759;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 760;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 761;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 762;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 763;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 764;
INSERT INTO `#__js_job_fieldsordering` VALUES (757, 'reference2_city', 'City', 238, '83', 13, 1, 0, 0, 0, 0), (758, 'reference2_zipcode', 'Zip Code', 239, '83', 13, 1, 0, 0, 0, 0), (759, 'reference2_phone', 'Phone', 240, '83', 13, 1, 0, 0, 0, 0), (760, 'reference2_relation', 'Relation', 241, '83', 13, 1, 0, 0, 0, 0), (761, 'reference2_years', 'Years', 242, '83', 13, 1, 0, 0, 0, 0), (762, 'section_sub_reference3', 'Reference 4', 243, '84', 13, 1, 0, 0, 0, 0), (763, 'reference3_reference', 'Reference', 244, '84', 13, 1, 0, 0, 0, 0), (764, 'reference3_name', 'Name', 245, '84', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 768;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 769;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 770;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 771;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 772;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 773;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 774;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 775;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 776;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 777;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 778;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 779;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 780;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 781;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 782;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 783;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 784;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 785;
INSERT INTO `#__js_job_fieldsordering` VALUES (768, 'reference3_city', 'City', 249, '84', 13, 1, 0, 0, 0, 0), (769, 'reference3_zipcode', 'Zip Code', 250, '84', 13, 1, 0, 0, 0, 0), (770, 'reference3_phone', 'Phone', 251, '84', 13, 1, 0, 0, 0, 0), (771, 'reference3_relation', 'Relation', 252, '84', 13, 1, 0, 0, 0, 0), (772, 'reference3_years', 'Years', 253, '84', 13, 1, 0, 0, 0, 0), (773, 'Iamavailable', 'I am Available', 11, '10', 13, 1, 0, 0, 0, 0), (774, 'searchable', 'Searchable', 12, '10', 13, 1, 0, 0, 0, 0), (775, 'section_userfields', 'Visitor User Fields', 21, '1000', 13, 0, 0, 0, 0, 1), (776, 'userfield1', 'User Field 1', 22, '1000', 13, 0, 0, 0, 0, 0), (777, 'userfield2', 'User Field 2', 23, '1000', 13, 0, 0, 0, 0, 0), (778, 'userfield3', 'User Field 3', 24, '1000', 13, 0, 0, 0, 0, 0), (779, 'userfield4', 'User Field 4', 25, '1000', 13, 0, 0, 0, 0, 0), (780, 'userfield5', 'User Field 5', 26, '1000', 13, 0, 0, 0, 0, 0), (781, 'userfield6', 'User Field 6', 27, '1000', 13, 0, 0, 0, 0, 0), (782, 'userfield7', 'User Field 7', 28, '1000', 13, 0, 0, 0, 0, 0), (783, 'userfield8', 'User Field 8', 29, '1000', 13, 0, 0, 0, 0, 0), (784, 'userfield9', 'User Field 9', 30, '1000', 13, 0, 0, 0, 0, 0), (785, 'userfield10', 'User Field 10', 32, '1000', 13, 0, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 786;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 787;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 788;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 789;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 790;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 791;
INSERT INTO `#__js_job_fieldsordering` VALUES (787, 'date_of_birth', 'Date of Birth', 14, '10', 13, 1, 0, 0, 0, 0), (789, 'video', 'Youtube Video Id', 22, '20', 13, 1, 0, 0, 0, 0), (791, 'address_location', 'Longitude And Latitude', 50, '31', 13, 1, 0, 0, 0, 0);
DELETE FROM `#__js_job_fieldsordering` WHERE id = 833;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 834;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 835;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 836;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 837;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 838;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 839;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 840;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 841;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 842;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 843;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 844;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 845;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 846;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 847;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 848;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 849;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 850;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 851;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 852;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 853;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 854;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 855;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 856;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 857;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 858;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 859;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 862;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 863;
DELETE FROM `#__js_job_fieldsordering` WHERE id = 864;
DELETE FROM `#__js_job_fieldsordering` WHERE fieldfor = 13;
CREATE TABLE IF NOT EXISTS `#__js_job_jobshortlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `jobid` int(11) NOT NULL,
  `comments` text NOT NULL,
  `rate` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sharing` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
RENAME TABLE `#__js_job_resume` TO `#__js_job_resume_old`;
CREATE TABLE IF NOT EXISTS `#__js_job_resume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `application_title` varchar(150) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `middle_name` varchar(150) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email_address` varchar(200) DEFAULT NULL,
  `home_phone` varchar(60) NOT NULL,
  `work_phone` varchar(60) DEFAULT NULL,
  `cell` varchar(60) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `iamavailable` tinyint(1) DEFAULT NULL,
  `searchable` tinyint(1) DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `job_category` int(11) DEFAULT NULL,
  `jobsalaryrange` int(11) DEFAULT NULL,
  `jobsalaryrangetype` int(11) DEFAULT NULL,
  `jobtype` int(11) DEFAULT NULL,
  `heighestfinisheducation` varchar(60) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `resume` text,
  `date_start` datetime DEFAULT NULL,
  `desired_salary` int(11) DEFAULT NULL,
  `djobsalaryrangetype` int(11) DEFAULT NULL,
  `dcurrencyid` int(11) DEFAULT NULL,
  `can_work` varchar(250) DEFAULT NULL,
  `available` varchar(250) DEFAULT NULL,
  `unavailable` varchar(250) DEFAULT NULL,
  `total_experience` varchar(50) DEFAULT NULL,
  `skills` text,
  `driving_license` tinyint(1) DEFAULT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `license_country` varchar(50) DEFAULT NULL,
  `packageid` int(11) DEFAULT NULL,
  `paymenthistoryid` int(11) DEFAULT NULL,
  `currencyid` int(11) DEFAULT NULL,
  `job_subcategory` int(11) DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `video` varchar(50) DEFAULT NULL,
  `isgoldresume` tinyint(1) DEFAULT NULL,
  `isfeaturedresume` tinyint(1) DEFAULT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resume` (`id`, `uid`, `created`, `last_modified`, `published`, `hits`, `application_title`, `keywords`, `alias`, `first_name`, `last_name`, `middle_name`, `gender`, `email_address`, `home_phone`, `work_phone`, `cell`, `nationality`, `iamavailable`, `searchable`, `photo`, `job_category`, `jobsalaryrange`, `jobsalaryrangetype`, `jobtype`, `heighestfinisheducation`, `status`, `resume`, `date_start`, `desired_salary`, `djobsalaryrangetype`, `dcurrencyid`, `can_work`, `available`, `unavailable`, `total_experience`, `skills`, `driving_license`, `license_no`, `license_country`, `packageid`, `paymenthistoryid`, `currencyid`, `job_subcategory`, `date_of_birth`, `video`, `isgoldresume`, `isfeaturedresume`, `serverstatus`, `serverid`)
SELECT id, uid, create_date, modified_date, published, hits, application_title, keywords, alias, first_name, last_name, middle_name, gender, email_address, home_phone, work_phone, cell, nationality, iamavailable, searchable, photo, job_category, jobsalaryrange, jobsalaryrangetype, jobtype, heighestfinisheducation, status, resume, date_start, desired_salary, djobsalaryrangetype, dcurrencyid, can_work, available, unalailable, total_experience, skills, driving_license, license_no, license_country, packageid, paymenthistoryid, currencyid, job_subcategory, date_of_birth, video, isgoldresume, isfeaturedresume, serverstatus, serverid FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumeaddresses`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumeaddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `address` text,
  `address_country` varchar(100) DEFAULT NULL,
  `address_state` varchar(60) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_zipcode` varchar(60) DEFAULT NULL,
  `longitude` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumeaddresses` (`resumeid`, `address`, `address_country`, `address_state`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, address, address_country, address_state, address_city, address_zipcode, longitude, latitude, create_date, modified_date, NULL,NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeaddresses` (`resumeid`, `address`, `address_country`, `address_state`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, address1, address1_country, address1_state, address1_city, address1_zipcode, longitude, latitude, create_date, modified_date, NULL,NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeaddresses` (`resumeid`, `address`, `address_country`, `address_state`, `address_city`, `address_zipcode`, `longitude`, `latitude`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, address2, address2_country, address2_state, address2_city, address2_zipcode, longitude, latitude, create_date, modified_date, NULL,NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumeemployers`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumeemployers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `employer` varchar(250) DEFAULT NULL,
  `employer_position` varchar(150) DEFAULT NULL,
  `employer_resp` text,
  `employer_pay_upon_leaving` varchar(250) DEFAULT NULL,
  `employer_supervisor` varchar(100) DEFAULT NULL,
  `employer_from_date` varchar(60) DEFAULT NULL,
  `employer_to_date` varchar(60) DEFAULT NULL,
  `employer_leave_reason` text,
  `employer_country` varchar(100) DEFAULT NULL,
  `employer_state` varchar(100) DEFAULT NULL,
  `employer_city` varchar(100) DEFAULT NULL,
  `employer_zip` varchar(60) DEFAULT NULL,
  `employer_phone` varchar(60) DEFAULT NULL,
  `employer_address` varchar(150) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumeemployers` (`resumeid`, `employer`, `employer_position`, `employer_resp`, `employer_pay_upon_leaving`, `employer_supervisor`, `employer_from_date`, `employer_to_date`, `employer_leave_reason`, `employer_country`, `employer_state`, `employer_city`, `employer_zip`, `employer_phone`, `employer_address`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, employer, employer_position, employer_resp, employer_pay_upon_leaving, employer_supervisor, employer_from_date, employer_to_date, employer_leave_reason, employer_country, employer_state, employer_city, employer_zip, employer_phone, employer_address, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeemployers` (`resumeid`, `employer`, `employer_position`, `employer_resp`, `employer_pay_upon_leaving`, `employer_supervisor`, `employer_from_date`, `employer_to_date`, `employer_leave_reason`, `employer_country`, `employer_state`, `employer_city`, `employer_zip`, `employer_phone`, `employer_address`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, employer1, employer1_position, employer1_resp, employer1_pay_upon_leaving, employer1_supervisor, employer1_from_date, employer1_to_date, employer1_leave_reason, employer1_country, employer1_state, employer1_city, employer1_zip, employer1_phone, employer1_address, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeemployers` (`resumeid`, `employer`, `employer_position`, `employer_resp`, `employer_pay_upon_leaving`, `employer_supervisor`, `employer_from_date`, `employer_to_date`, `employer_leave_reason`, `employer_country`, `employer_state`, `employer_city`, `employer_zip`, `employer_phone`, `employer_address`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, employer2, employer2_position, employer2_resp, employer2_pay_upon_leaving, employer2_supervisor, employer2_from_date, employer2_to_date, employer2_leave_reason, employer2_country, employer2_state, employer2_city, employer2_zip, employer2_phone, employer2_address, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeemployers` (`resumeid`, `employer`, `employer_position`, `employer_resp`, `employer_pay_upon_leaving`, `employer_supervisor`, `employer_from_date`, `employer_to_date`, `employer_leave_reason`, `employer_country`, `employer_state`, `employer_city`, `employer_zip`, `employer_phone`, `employer_address`, `created`, `last_modified`, `serverstatus`, `serverid`)
SELECT id, employer3, employer3_position, employer3_resp, employer3_pay_upon_leaving, employer3_supervisor, employer3_from_date, employer3_to_date, employer3_leave_reason, employer3_country, employer3_state, employer3_city, employer3_zip, employer3_phone, employer3_address, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumefiles`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumefiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `filename` varchar(300) DEFAULT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumefiles` (`resumeid`, `filename`, `filetype`, `filesize`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, filename, filetype, filesize, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumeinstitutes`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumeinstitutes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `institute` varchar(100) DEFAULT NULL,
  `institute_country` varchar(100) DEFAULT NULL,
  `institute_state` varchar(100) DEFAULT NULL,
  `institute_city` varchar(100) DEFAULT NULL,
  `institute_address` varchar(150) DEFAULT NULL,
  `institute_certificate_name` varchar(100) DEFAULT NULL,
  `institute_study_area` text,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumeinstitutes` (`resumeid`, `institute`, `institute_country`, `institute_state`, `institute_city`, `institute_address`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, institute, institute_country, institute_state, institute_city, institute_address, institute_certificate_name, institute_study_area, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeinstitutes` (`resumeid`, `institute`, `institute_country`, `institute_state`, `institute_city`, `institute_address`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, institute1, institute1_country, institute1_state, institute1_city, institute1_address, institute1_certificate_name, institute1_study_area, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeinstitutes` (`resumeid`, `institute`, `institute_country`, `institute_state`, `institute_city`, `institute_address`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, institute2, institute2_country, institute2_state, institute2_city, institute2_address, institute2_certificate_name, institute2_study_area, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumeinstitutes` (`resumeid`, `institute`, `institute_country`, `institute_state`, `institute_city`, `institute_address`, `institute_certificate_name`, `institute_study_area`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, institute3, institute3_country, institute3_state, institute3_city, institute3_address, institute3_certificate_name, institute3_study_area, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumelanguages`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumelanguages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `language_reading` varchar(50) DEFAULT NULL,
  `language_writing` varchar(50) DEFAULT NULL,
  `language_understanding` varchar(50) DEFAULT NULL,
  `language_where_learned` varchar(150) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumelanguages` (`resumeid`, `language`, `language_reading`, `language_writing`, `language_understanding`, `language_where_learned`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, language, language_reading, language_writing, language_understanding, language_where_learned, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumelanguages` (`resumeid`, `language`, `language_reading`, `language_writing`, `language_understanding`, `language_where_learned`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, language1, language1_reading, language1_writing, language1_understanding, language1_where_learned, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumelanguages` (`resumeid`, `language`, `language_reading`, `language_writing`, `language_understanding`, `language_where_learned`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, language2, language2_reading, language2_writing, language2_understanding, language2_where_learned, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumelanguages` (`resumeid`, `language`, `language_reading`, `language_writing`, `language_understanding`, `language_where_learned`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, language3, language3_reading, language3_writing, language3_understanding, language3_where_learned, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resumereferences`;
CREATE TABLE IF NOT EXISTS `#__js_job_resumereferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resumeid` int(11) NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `reference_name` varchar(50) DEFAULT NULL,
  `reference_country` varchar(50) DEFAULT NULL,
  `reference_state` varchar(50) DEFAULT NULL,
  `reference_city` varchar(50) DEFAULT NULL,
  `reference_zipcode` varchar(20) DEFAULT NULL,
  `reference_address` varchar(150) DEFAULT NULL,
  `reference_phone` varchar(50) DEFAULT NULL,
  `reference_relation` varchar(50) DEFAULT NULL,
  `reference_years` varchar(10) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `serverstatus` varchar(255) DEFAULT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci' AUTO_INCREMENT=1 ;
INSERT INTO `#__js_job_resumereferences` (`resumeid`, `reference`, `reference_name`, `reference_country`, `reference_state`, `reference_city`, `reference_zipcode`, `reference_address`, `reference_phone`, `reference_relation`, `reference_years`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, reference, reference_name, reference_country, reference_state, reference_city, reference_zipcode, reference_address, reference_phone, reference_relation, reference_years, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumereferences` (`resumeid`, `reference`, `reference_name`, `reference_country`, `reference_state`, `reference_city`, `reference_zipcode`, `reference_address`, `reference_phone`, `reference_relation`, `reference_years`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, reference1, reference1_name, reference1_country, reference1_state, reference1_city, NULL, reference1_address, reference1_phone, reference1_relation, reference1_years, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumereferences` (`resumeid`, `reference`, `reference_name`, `reference_country`, `reference_state`, `reference_city`, `reference_zipcode`, `reference_address`, `reference_phone`, `reference_relation`, `reference_years`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, reference2, reference2_name, reference2_country, reference2_state, reference2_city, NULL, reference2_address, reference2_phone, reference2_relation, reference2_years, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
INSERT INTO `#__js_job_resumereferences` (`resumeid`, `reference`, `reference_name`, `reference_country`, `reference_state`, `reference_city`, `reference_zipcode`, `reference_address`, `reference_phone`, `reference_relation`, `reference_years`, `created`, `last_modified`, `serverstatus`, `serverid`) 
SELECT id, reference3, reference3_name, reference3_country, reference3_state, reference3_city, NULL, reference3_address, reference3_phone, reference3_relation, reference3_years, create_date, modified_date, NULL, NULL FROM `#__js_job_resume_old`;
DROP TABLE IF EXISTS `#__js_job_resume_old`;
INSERT INTO `#__js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (20, 420, 'jobseeker-packagepurchase', NULL, 'JS Jobs : You have ordered {PACKAGE_TITLE}', '<div style="background: #6DC6DD; height: 20px;"> </div>\r\n<p style="color: #2191ad;">Dear {JOBSEEKER_NAME}</p>\r\n<p style="color: #4f4f4f;">You have ordered  {PACKAGE_TITLE}..</p>\r\n<p style="color: #4f4f4f;">Now you will be awarded all the facilities which we given in the above package, after the payment status verified.</p>\r\n<p style="color: #4f4f4f;">Payment Status {PAYMENT_STATUS}</p>\r\n<div>Click here to view {LINK} .<br />\r\n<p>Thank you.</p>\r\n</div>\r\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\r\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we can''"t receive your reply!</p>\r\n</div>', 1, '2011-03-31 16:46:16'),(21, 420, 'employer-packagepurchase', NULL, 'JS Jobs : You have ordered {PACKAGE_TITLE}', '<div style="background: #6DC6DD; height: 20px;"> </div>\r\n<p style="color: #2191ad;">Dear {EMPLOYER_NAME}</p>\r\n<p style="color: #4f4f4f;">You have ordered  {PACKAGE_TITLE}..</p>\r\n<p style="color: #4f4f4f;">Now you will be awarded all the facilities which we given in the above package, after the payment status verified.</p>\r\n<p style="color: #4f4f4f;">Payment Status {PAYMENT_STATUS}</p>\r\n<div>Click here to view {LINK} .<br />\r\n<p>Thank you.</p>\r\n</div>\r\n<div style="margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;">\r\n<p><span style="color: red;"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we can''"t receive your reply!</p>\r\n</div>', 1, '2011-03-31 16:46:16');
UPDATE `#__js_job_config` SET `configvalue` = '1.1.1' WHERE `configname` = 'version';
UPDATE `#__js_job_config` SET `configvalue` = '111' WHERE `configname` = 'versioncode';
