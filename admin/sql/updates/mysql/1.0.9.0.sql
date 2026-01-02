ALTER TABLE  `#__js_job_employerpackages` ADD  `currencyid` INT( 11 ) NOT NULL AFTER  `id`;
ALTER TABLE  `#__js_job_jobseekerpackages` ADD  `currencyid` INT( 11 ) NOT NULL AFTER  `id`;

ALTER TABLE `#__js_job_categories` ADD `alias` VARCHAR( 225 ) NOT NULL AFTER `cat_title`;
UPDATE `#__js_job_categories` SET alias = LOWER( REPLACE( cat_title, ' ', '-' ) );
UPDATE `#__js_job_categories` SET alias = LOWER( REPLACE( alias, '/', '-' ) ); 
UPDATE `#__js_job_categories` SET alias = LOWER( REPLACE( alias, '&', '-' ) ); 
ALTER TABLE `#__js_job_categories` ADD `serverid` int( 11 ) AFTER `isactive`;


ALTER TABLE `#__js_job_subcategories` ADD `alias` VARCHAR( 225 ) NOT NULL AFTER `title`;
UPDATE `#__js_job_subcategories` SET alias = LOWER( REPLACE( title, ' ', '-' ) ); 
UPDATE `#__js_job_subcategories` SET alias = LOWER( REPLACE( alias, '/', '-' ) ); 
UPDATE `#__js_job_subcategories` SET alias = LOWER( REPLACE( alias, '&', '-' ) ); 
ALTER TABLE `#__js_job_subcategories` ADD `serverid` int( 11 ) AFTER `status`;


ALTER TABLE `#__js_job_companies` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `name`;
ALTER TABLE `#__js_job_companies` ADD `isgoldcompany` tinyint( 1 ) NULL AFTER `paymenthistoryid`;
ALTER TABLE `#__js_job_companies` ADD `startgolddate` datetime NULL AFTER `isgoldcompany`;
ALTER TABLE `#__js_job_companies` ADD `isfeaturedcompany` tinyint( 1 ) NULL AFTER `isgoldcompany`;
ALTER TABLE `#__js_job_companies` ADD `startfeatureddate` datetime NULL AFTER `isfeaturedcompany`;
ALTER TABLE `#__js_job_companies` ADD `serverstatus` varchar( 255 ) NULL AFTER `isfeaturedcompany`;
ALTER TABLE `#__js_job_companies` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;
UPDATE `#__js_job_companies` SET alias = LOWER( REPLACE( name, ' ', '-' ) );


ALTER TABLE `#__js_job_coverletters` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `title`;
ALTER TABLE `#__js_job_coverletters` ADD `serverstatus` varchar( 255 ) NULL AFTER `paymenthistoryid`;
ALTER TABLE `#__js_job_coverletters` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;
UPDATE `#__js_job_coverletters` SET alias = LOWER( REPLACE( title, ' ', '-' ) );

ALTER TABLE `#__js_job_currencies` ADD `serverid` int( 11 ) NULL AFTER `default`;


ALTER TABLE `#__js_job_departments` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `name`;
ALTER TABLE `#__js_job_departments` ADD `serverstatus` varchar( 255 ) NULL AFTER `created`;
ALTER TABLE `#__js_job_departments` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;
UPDATE `#__js_job_departments` SET alias = LOWER( REPLACE( name, ' ', '-' ) );

ALTER TABLE `#__js_job_experiences` ADD `serverid` int( 11 ) NULL AFTER `status`;


ALTER TABLE `#__js_job_heighesteducation` ADD `serverid` int( 11 ) NULL AFTER `isactive`;


ALTER TABLE `#__js_job_jobapply` ADD `action_status` int( 11 ) DEFAULT '1' AFTER `coverletterid`;
ALTER TABLE `#__js_job_jobapply` ADD `serverstatus` varchar( 255 ) NULL AFTER `action_status`;
ALTER TABLE `#__js_job_jobapply` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;

ALTER TABLE `#__js_job_jobs` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `title`;
ALTER TABLE `#__js_job_jobs` ADD `isgoldjob` tinyint( 1 ) NULL AFTER `latitude`;
ALTER TABLE `#__js_job_jobs` ADD `isfeaturedjob` tinyint( 1 ) NULL AFTER `isgoldjob`;
ALTER TABLE `#__js_job_jobs` ADD `raf_gender` tinyint( 1 ) NULL AFTER `isfeaturedjob`;
ALTER TABLE `#__js_job_jobs` ADD `raf_degreelevel` tinyint( 1 ) NULL AFTER `raf_gender`;
ALTER TABLE `#__js_job_jobs` ADD `raf_experience` tinyint( 1 ) NULL AFTER `raf_degreelevel`;
ALTER TABLE `#__js_job_jobs` ADD `raf_age` tinyint( 1 ) NULL AFTER `raf_experience`;
ALTER TABLE `#__js_job_jobs` ADD `raf_education` tinyint( 1 ) NULL AFTER `raf_age`;
ALTER TABLE `#__js_job_jobs` ADD `raf_category` tinyint( 1 ) NULL AFTER `raf_education`;
ALTER TABLE `#__js_job_jobs` ADD `raf_subcategory` tinyint( 1 ) NULL AFTER `raf_category`;
ALTER TABLE `#__js_job_jobs` ADD `raf_location` tinyint( 1 ) NULL AFTER `raf_subcategory`;
ALTER TABLE `#__js_job_jobs` ADD `serverstatus` varchar( 255 ) NULL AFTER `raf_location`;
ALTER TABLE `#__js_job_jobs` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;
UPDATE `#__js_job_jobs` SET alias = LOWER( REPLACE( title, ' ', '-' ));

ALTER TABLE `#__js_job_jobstatus` ADD `serverid` int(11) NULL AFTER `isactive`;
ALTER TABLE `#__js_job_jobtypes` ADD `serverid` int(11) NULL AFTER `status`;

ALTER TABLE `#__js_job_resume` ADD `alias` VARCHAR( 255 ) NOT NULL AFTER `application_title`;
ALTER TABLE `#__js_job_resume` ADD `keywords` VARCHAR( 255 )  NULL AFTER `application_title`;
ALTER TABLE `#__js_job_resume` change `langugage_reading` `language_reading` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage_writing` `language_writing` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage_undarstanding` `language_understanding` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage_where_learned` `language_where_learned` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage1_reading` `language1_reading` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage1_writing` `language1_writing` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage1_undarstanding` `language1_understanding` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage1_where_learned` `language1_where_learned` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage2_reading` `language2_reading` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage2_writing` `language2_writing` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage2_undarstanding` `language2_understanding` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage2_where_learned` `language2_where_learned` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage3_reading` `language3_reading` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage3_writing` `language3_writing` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage3_undarstanding` `language3_understanding` varchar(20);
ALTER TABLE `#__js_job_resume` change `langugage3_where_learned` `language3_where_learned` varchar(20);
ALTER TABLE `#__js_job_resume` ADD `dcurrencyid` int( 11 ) NULL AFTER `desired_salary`;
ALTER TABLE `#__js_job_resume` ADD `isgoldresume` tinyint( 1 ) NULL AFTER `video`;
ALTER TABLE `#__js_job_resume` ADD `isfeaturedresume` tinyint( 1 ) NULL AFTER `isgoldresume`;
ALTER TABLE `#__js_job_resume` ADD `serverstatus` varchar( 255 ) NULL AFTER `isfeaturedresume`;
ALTER TABLE `#__js_job_resume` ADD `serverid` int( 11 ) NULL AFTER `serverstatus`;
UPDATE `#__js_job_resume` SET alias = LOWER( REPLACE( application_title, ' ', '-' ) );

ALTER TABLE `#__js_job_salaryrange` ADD `serverid` int( 11 ) NULL AFTER `rangeend`;

ALTER TABLE `#__js_job_salaryrangetypes` ADD `serverid` int( 11 ) NULL AFTER `status`;

ALTER TABLE `#__js_job_shifts` ADD `serverid` int( 11 ) NULL AFTER `status`;

DROP TABLE IF EXISTS `#__js_job_companycities`;
CREATE TABLE `#__js_job_companycities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL,
  `cityid` int(11) NOT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companyid` (`companyid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__js_job_jobcities`;
CREATE TABLE `#__js_job_jobcities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` int(11) NOT NULL,
  `cityid` int(11) NOT NULL,
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobid` (`jobid`),
  KEY `cityid` (`cityid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__js_job_jobs_temp`;
CREATE TABLE `#__js_job_jobs_temp` (
  `localid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `aliasid` varchar(255) DEFAULT NULL,
  `companyaliasid` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `jobdays` int(11) DEFAULT NULL,
  `companyid` int(11) DEFAULT NULL,
  `companyname` varchar(255) DEFAULT NULL,
  `jobcategory` varchar(255) DEFAULT NULL,
  `cat_title` varchar(255) DEFAULT NULL,
  `symbol` varchar(60) DEFAULT NULL,
  `salaryfrom` varchar(255) DEFAULT NULL,
  `salaryto` varchar(255) DEFAULT NULL,
  `salaytype` varchar(255) DEFAULT NULL,
  `jobtype` varchar(100) DEFAULT NULL,
  `jobstatus` varchar(100) DEFAULT NULL,
  `cityname` varchar(100) DEFAULT NULL,
  `statename` varchar(100) DEFAULT NULL,
  `countryname` varchar(100) DEFAULT NULL,
  `noofjobs` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`localid`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

DROP TABLE IF EXISTS `#__js_job_jobs_temp_time`;
CREATE TABLE `#__js_job_jobs_temp_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastcalltime` datetime DEFAULT NULL,
  `expiretime` datetime DEFAULT NULL,
  `is_request` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

DROP TABLE IF EXISTS `#__js_job_sharing_service_log`;
CREATE TABLE `#__js_job_sharing_service_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `referenceid` int(11) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `eventtype` varchar(255) DEFAULT NULL,
  `message` text,
  `messagetype` varchar(255) DEFAULT NULL,
  `datetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

DROP TABLE IF EXISTS `#__js_job_paymenthistory`;
CREATE TABLE `#__js_job_paymenthistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `packageid` int(11) NOT NULL,
  `packagetitle` varchar(255) NOT NULL,
  `packageprice` float DEFAULT NULL,
  `discountamount` int(11) DEFAULT NULL,
  `paidamount` int(11) DEFAULT NULL,
  `discountmessage` varchar(500) DEFAULT NULL,
  `packagediscountstartdate` datetime DEFAULT NULL,
  `packagediscountenddate` datetime DEFAULT NULL,
  `packageexpireindays` int(11) NOT NULL,
  `packageshortdetails` varchar(255) DEFAULT NULL,
  `packagedescription` text,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `transactionverified` tinyint(4) DEFAULT NULL,
  `transactionautoverified` tinyint(4) DEFAULT NULL,
  `verifieddate` datetime DEFAULT NULL,
  `referenceid` varchar(150) DEFAULT NULL,
  `payer_firstname` varchar(150) DEFAULT NULL,
  `payer_lastname` varchar(150) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `payer_amount` float DEFAULT NULL,
  `payer_itemname` varchar(255) DEFAULT NULL,
  `payer_itemname2` varchar(255) DEFAULT NULL,
  `payer_status` varchar(255) DEFAULT NULL,
  `payer_tx_token` varchar(255) DEFAULT NULL,
  `packagefor` tinyint(1) DEFAULT NULL,
  `currencyid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_uid` (`uid`),
  KEY `payment_packageid` (`packageid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__js_job_paymenthistory` (uid, packageid, packagetitle, packageprice,discountamount,paidamount,discountmessage,packagediscountstartdate,packagediscountenddate,packageexpireindays,packageshortdetails,packagedescription,status,created,transactionverified,transactionautoverified,verifieddate,referenceid,payer_firstname,payer_lastname,payer_email,payer_amount,payer_itemname,payer_itemname2,payer_status,payer_tx_token,packagefor,currencyid) 
SELECT uid, packageid,packagetitle,packageprice,discountamount,paidamount,discountmessage,packagestartdate,packageenddate,packageexpireindays,packageshortdetails,packagedescription,status,created,transactionverified,transactionautoverified,verifieddate,referenceid,payer_firstname,payer_lastname,payer_email,payer_amount,payer_itemname,payer_itemname1,payer_status,payer_tx_token,2,(select id from `#__js_job_currencies` AS cur where cur.default=1) AS currencyid from `#__js_job_jobseekerpaymenthistory`;

INSERT INTO `#__js_job_paymenthistory` (uid, packageid, packagetitle, packageprice,discountamount,paidamount,discountmessage,packagediscountstartdate,packagediscountenddate,packageexpireindays,packageshortdetails,packagedescription,status,created,transactionverified,transactionautoverified,verifieddate,referenceid,payer_firstname,payer_lastname,payer_email,payer_amount,payer_itemname,payer_itemname2,payer_status,payer_tx_token,packagefor,currencyid) 
SELECT uid, packageid,packagetitle,packageprice,discountamount,paidamount,discountmessage,packagediscountstartdate,packagediscountenddate,packageexpireindays,packageshortdetails,packagedescription,status,created,transactionverified,transactionautoverified,verifieddate,referenceid,payer_firstname,payer_lastname,payer_email,payer_amount,payer_itemname,payer_itemname2,payer_status,payer_tx_token,1,(select id from `#__js_job_currencies` AS cur where cur.default=1) AS currencyid from `#__js_job_employerpaymenthistory`;


RENAME TABLE `#__js_job_countries` TO `#__js_job_countries_old`;

UPDATE `#__js_job_countries_old` SET enabled=1 WHERE enabled='Y';

UPDATE `#__js_job_countries_old` SET enabled=0 WHERE enabled='N';

DROP TABLE IF EXISTS `#__js_job_countries`;

CREATE TABLE `#__js_job_countries` (
  `id` smallint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `shortCountry` varchar(30) DEFAULT NULL,
  `continentID` tinyint(11) DEFAULT NULL,
  `dialCode` smallint(8) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

INSERT INTO `#__js_job_countries`(id,name,shortCountry,continentID,dialCode,enabled,serverid) VALUES (1,'United States','US',1,1,1,0),(2,'Canada','Canada',1,1,1,0),(3,'Bahamas','Bahamas',1,242,1,0),(4,'Barbados','Barbados',1,246,1,0),(5,'Belize','Belize',1,501,1,0),(6,'Bermuda','Bermuda',1,441,1,0),(7,'British Virgin Islands','BVI',1,284,1,0),(8,'Cayman Islands','CaymanIsl',1,345,1,0),(9,'Costa Rica','CostaRica',1,506,1,0),(10,'Cuba','Cuba',1,53,1,0),(11,'Dominica','Dominica',1,767,1,0),(12,'Dominican Republic','DominicanRep',1,809,1,0),(13,'El Salvador','ElSalvador',1,503,1,0),(14,'Greenland','Greenland',1,299,1,0),(15,'Grenada','Grenada',1,473,1,0),(16,'Guadeloupe','Guadeloupe',1,590,1,0),(17,'Guatemala','Guatemala',1,502,1,0),(18,'Haiti','Haiti',1,509,1,0),(19,'Honduras','Honduras',1,503,1,0),(20,'Jamaica','Jamaica',1,876,1,0),(21,'Martinique','Martinique',1,596,1,0),(22,'Mexico','Mexico',1,52,1,0),(23,'Montserrat','Montserrat',1,664,1,0),(24,'Nicaragua','Nicaragua',1,505,1,0),(25,'Panama','Panama',1,507,1,0),(26,'Puerto Rico','PuertoRico',1,787,1,0),(27,'Trinidad and Tobago','Trinidad-Tobago',1,868,1,0),(28,'United States Virgin Islands','USVI',1,340,1,0),(29,'Argentina','Argentina',2,54,1,0),(30,'Bolivia','Bolivia',2,591,1,0),(31,'Brazil','Brazil',2,55,1,0),(32,'Chile','Chile',2,56,1,0),(33,'Colombia','Colombia',2,57,1,0),(34,'Ecuador','Ecuador',2,593,1,0),(35,'Falkland Islands','FalklandIsl',2,500,1,0),(36,'French Guiana','FrenchGuiana',2,594,1,0),(37,'Guyana','Guyana',2,592,1,0),(38,'Paraguay','Paraguay',2,595,1,0),(39,'Peru','Peru',2,51,1,0),(40,'Suriname','Suriname',2,597,1,0),(41,'Uruguay','Uruguay',2,598,1,0),(42,'Venezuela','Venezuela',2,58,1,0),(43,'Albania','Albania',3,355,1,0),(44,'Andorra','Andorra',3,376,1,0),(45,'Armenia','Armenia',3,374,1,0),(46,'Austria','Austria',3,43,1,0),(47,'Azerbaijan','Azerbaijan',3,994,1,0),(48,'Belarus','Belarus',3,375,1,0),(49,'Belgium','Belgium',3,32,1,0),(50,'Bosnia and Herzegovina','Bosnia-Herzegovina',3,387,1,0),(51,'Bulgaria','Bulgaria',3,359,1,0),(52,'Croatia','Croatia',3,385,1,0),(53,'Cyprus','Cyprus',3,357,1,0),(54,'Czech Republic','CzechRep',3,420,1,0),(55,'Denmark','Denmark',3,45,1,0),(56,'Estonia','Estonia',3,372,1,0),(57,'Finland','Finland',3,358,1,0),(58,'France','France',3,33,1,0),(59,'Georgia','Georgia',3,995,1,0),(60,'Germany','Germany',3,49,1,0),(61,'Gibraltar','Gibraltar',3,350,1,0),(62,'Greece','Greece',3,30,1,0),(63,'Guernsey','Guernsey',3,44,1,0),(64,'Hungary','Hungary',3,36,1,0),(65,'Iceland','Iceland',3,354,1,0),(66,'Ireland','Ireland',3,353,1,0),(67,'Isle of Man','IsleofMan',3,44,1,0),(68,'Italy','Italy',3,39,1,0),(69,'Jersey','Jersey',3,44,1,0),(70,'Kosovo','Kosovo',3,381,1,0),(71,'Latvia','Latvia',3,371,1,0),(72,'Liechtenstein','Liechtenstein',3,423,1,0),(73,'Lithuania','Lithuania',3,370,1,0),(74,'Luxembourg','Luxembourg',3,352,1,0),(75,'Macedonia','Macedonia',3,389,1,0),(76,'Malta','Malta',3,356,1,0),(77,'Moldova','Moldova',3,373,1,0),(78,'Monaco','Monaco',3,377,1,0),(79,'Montenegro','Montenegro',3,381,1,0),(80,'Netherlands','Netherlands',3,31,1,0),(81,'Norway','Norway',3,47,1,0),(82,'Poland','Poland',3,48,1,0),(83,'Portugal','Portugal',3,351,1,0),(84,'Romania','Romania',3,40,1,0),(85,'Russia','Russia',3,7,1,0),(86,'San Marino','SanMarino',3,378,1,0),(87,'Serbia','Serbia',3,381,1,0),(88,'Slovakia','Slovakia',3,421,1,0),(89,'Slovenia','Slovenia',3,386,1,0),(90,'Spain','Spain',3,34,1,0),(91,'Sweden','Sweden',3,46,1,0),(92,'Switzerland','Switzerland',3,41,1,0),(93,'Turkey','Turkey',3,90,1,0),(94,'Ukraine','Ukraine',3,380,1,0),(95,'United Kingdom','UK',3,44,1,0),(96,'Vatican City','Vatican',3,39,1,0),(97,'Afghanistan','Afghanistan',4,93,1,0),(98,'Bahrain','Bahrain',4,973,1,0),(99,'Bangladesh','Bangladesh',4,880,1,0),(100,'Bhutan','Bhutan',4,975,1,0),(101,'Brunei','Brunei',4,673,1,0),(102,'Cambodia','Cambodia',4,855,1,0),(103,'China','China',4,86,1,0),(104,'East Timor','EastTimor',4,670,1,0),(105,'Hong Kong','HongKong',4,852,1,0),(106,'India','India',4,91,1,0),(107,'Indonesia','Indonesia',4,62,1,0),(108,'Iran','Iran',4,98,1,0),(109,'Iraq','Iraq',4,964,1,0),(110,'Israel','Israel',4,972,1,0),(111,'Japan','Japan',4,81,1,0),(112,'Jordan','Jordan',4,962,1,0),(113,'Kazakhstan','Kazakhstan',4,7,1,0),(114,'Kuwait','Kuwait',4,965,1,0),(115,'Kyrgyzstan','Kyrgyzstan',4,996,1,0),(116,'Laos','Laos',4,856,1,0),(117,'Lebanon','Lebanon',4,961,1,0),(118,'Macau','Macau',4,853,1,0),(119,'Malaysia','Malaysia',4,60,1,0),(120,'Maldives','Maldives',4,960,1,0),(121,'Mongolia','Mongolia',4,976,1,0),(122,'Myanmar (Burma)','Myanmar(Burma)',4,95,1,0),(123,'Nepal','Nepal',4,977,1,0),(124,'North Korea','NorthKorea',4,850,1,0),(125,'Oman','Oman',4,968,1,0),(126,'Pakistan','Pakistan',4,92,1,0),(127,'Philippines','Philippines',4,63,1,0),(128,'Qatar','Qatar',4,974,1,0),(129,'Saudi Arabia','SaudiArabia',4,966,1,0),(130,'Singapore','Singapore',4,65,1,0),(131,'South Korea','SouthKorea',4,82,1,0),(132,'Sri Lanka','SriLanka',4,94,1,0),(133,'Syria','Syria',4,963,1,0),(134,'Taiwan','Taiwan',4,886,1,0),(135,'Tajikistan','Tajikistan',4,992,1,0),(136,'Thailand','Thailand',4,66,1,0),(137,'Turkmenistan','Turkmenistan',4,993,1,0),(138,'United Arab Emirates','UAE',4,971,1,0),(139,'Uzbekistan','Uzbekistan',4,998,1,0),(140,'Vietnam','Vietnam',4,84,1,0),(141,'Yemen','Yemen',4,967,1,0),(142,'Algeria','Algeria',5,213,1,0),(143,'Angola','Angola',5,244,1,0),(144,'Benin','Benin',5,229,1,0),(145,'Botswana','Botswana',5,267,1,0),(146,'Burkina Faso','BurkinaFaso',5,226,1,0),(147,'Burundi','Burundi',5,257,1,0),(148,'Cameroon','Cameroon',5,237,1,0),(149,'Cape Verde','CapeVerde',5,238,1,0),(150,'Central African Republic','CentralAfricanRep',5,236,1,0),(151,'Chad','Chad',5,235,1,0),(152,'Congo','Congo',5,242,1,0),(153,'Democoratic Republic of Congo','D.R Congo',5,242,1,0),(154,'Djibouti','Djibouti',5,253,1,0),(155,'Egypt','Egypt',5,20,1,0),(156,'Equatorial Guinea','EquatorialGuinea',5,240,1,0),(157,'Eritrea','Eritrea',5,291,1,0),(158,'Ethiopia','Ethiopia',5,251,1,0),(159,'Gabon','Gabon',5,241,1,0),(160,'Gambia','Gambia',5,220,1,0),(161,'Ghana','Ghana',5,233,1,0),(162,'Guinea','Guinea',5,224,1,0),(163,'Guinea-Bissau','Guinea-Bissau',5,245,1,0),(164,'Cote DIvory','IvoryCoast',5,225,1,0),(165,'Kenya','Kenya',5,254,1,0),(166,'Lesotho','Lesotho',5,266,1,0),(167,'Liberia','Liberia',5,231,1,0),(168,'Libya','Libya',5,218,1,0),(169,'Madagascar','Madagascar',5,261,1,0),(170,'Malawi','Malawi',5,265,1,0),(171,'Mali','Mali',5,223,1,0),(172,'Mauritania','Mauritania',5,222,1,0),(173,'Mauritius','Mauritius',5,230,1,0),(174,'Morocco','Morocco',5,212,1,0),(175,'Mozambique','Mozambique',5,258,1,0),(176,'Namibia','Namibia',5,264,1,0),(177,'Niger','Niger',5,227,1,0),(178,'Nigeria','Nigeria',5,234,1,0),(179,'Reunion','Reunion',5,262,1,0),(180,'Rwanda','Rwanda',5,250,1,0),(181,'Sao Tome and Principe','SaoTome-Principe',5,239,1,0),(182,'Senegal','Senegal',5,221,1,0),(183,'Seychelles','Seychelles',5,248,1,0),(184,'Sierra Leone','SierraLeone',5,232,1,0),(185,'Somalia','Somalia',5,252,1,0),(186,'South Africa','SouthAfrica',5,27,1,0),(187,'Sudan','Sudan',5,249,1,0),(188,'Swaziland','Swaziland',5,268,1,0),(189,'Tanzania','Tanzania',5,255,1,0),(190,'Togo','Togo',5,228,1,0),(191,'Tunisia','Tunisia',5,216,1,0),(192,'Uganda','Uganda',5,256,1,0),(193,'Western Sahara','WesternSahara',5,212,1,0),(194,'Zambia','Zambia',5,260,1,0),(195,'Zimbabwe','Zimbabwe',5,263,1,0),(196,'Australia','Australia',6,61,1,0),(197,'New Zealand','NewZealand',6,64,1,0),(198,'Fiji','Fiji',6,679,1,0),(199,'French Polynesia','FrenchPolynesia',6,689,1,0),(200,'Guam','Guam',6,671,1,0),(201,'Kiribati','Kiribati',6,686,1,0),(202,'Marshall Islands','MarshallIsl',6,692,1,0),(203,'Micronesia','Micronesia',6,691,1,0),(204,'Nauru','Nauru',6,674,1,0),(205,'New Caledonia','NewCaledonia',6,687,1,0),(206,'Papua New Guinea','PapuaNewGuinea',6,675,1,0),(207,'Samoa','Samoa',6,684,1,0),(208,'Solomon Islands','SolomonIsl',6,677,1,0),(209,'Tonga','Tonga',6,676,1,0),(210,'Tuvalu','Tuvalu',6,688,1,0),(211,'Vanuatu','Vanuatu',6,678,1,0),(212,'Wallis and Futuna','Wallis-Futuna',6,681,1,0),(213,'Comoros','Comoros',0,0,1,0),(214,'Cote DIvorie','Cote-DIvorie',NULL,NULL,1,0);

INSERT INTO `#__js_job_countries`(name,shortCountry,enabled)
SELECT c_d.name AS name,REPLACE(c_d.name,' ','-') AS shortCountry,c_d.enabled AS enabled
		FROM `#__js_job_countries_old` AS c_d 
		where  NOT EXISTS (SELECT name from #__js_job_countries WHERE REPLACE(LOWER(name),' ','') = REPLACE(LOWER(c_d.name),' ',''));

RENAME TABLE `#__js_job_states` TO `#__js_job_states_old`;

update `#__js_job_states_old` set enabled=1 where enabled='Y';

update `#__js_job_states_old` set enabled=0 where enabled='N';

ALTER TABLE `#__js_job_states_old` ADD COLUMN countryid int(11) null AFTER countrycode;
ALTER TABLE `#__js_job_states_old` ADD COLUMN shortRegion VARCHAR(100) null AFTER countrycode;
ALTER TABLE `#__js_job_states_old` ADD COLUMN serverid VARCHAR(100) null AFTER countrycode;

update `#__js_job_states_old`  AS state
join `#__js_job_countries_old` AS oc ON state.countrycode=oc.code 
join `#__js_job_countries` AS country ON oc.name=country.name
set state.countryid=country.id;

DROP TABLE IF EXISTS `#__js_job_states`;

CREATE TABLE `#__js_job_states` (
    `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) DEFAULT NULL,
  `shortRegion` varchar(25) DEFAULT NULL,
  `countryid` smallint(9) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `serverid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countryid` (`countryid`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

INSERT INTO `#__js_job_states` VALUES (1,'Alabama','AL',1,1,0),(2,'Alaska','AK',1,1,0),(3,'Arizona','AZ',1,1,0),(4,'Arkansas','AR',1,1,0),(5,'California','CA',1,1,0),(6,'Colorado','CO',1,1,0),(7,'Connecticut','CT',1,1,0),(8,'Delaware','DE',1,1,0),(9,'District of Columbia','DC',1,1,0),(10,'Florida','FL',1,1,0),(11,'Georgia','GA',1,1,0),(12,'Hawaii','HI',1,1,0),(13,'Idaho','ID',1,1,0),(14,'Illinois','IL',1,1,0),(15,'Indiana','IN',1,1,0),(16,'Iowa','IA',1,1,0),(17,'Kansas','KS',1,1,0),(18,'Kentucky','KY',1,1,0),(19,'Louisiana','LA',1,1,0),(20,'Maine','ME',1,1,0),(21,'Maryland','MD',1,1,0),(22,'Massachusetts','MA',1,1,0),(23,'Michigan','MI',1,1,0),(24,'Minnesota','MN',1,1,0),(25,'Mississippi','MS',1,1,0),(26,'Missouri','MO',1,1,0),(27,'Montana','MT',1,1,0),(28,'Nebraska','NE',1,1,0),(29,'Nevada','NV',1,1,0),(30,'New Hampshire','NH',1,1,0),(31,'New Jersey','NJ',1,1,0),(32,'New Mexico','NM',1,1,0),(33,'New York','NY',1,1,0),(34,'North Carolina','NC',1,1,0),(35,'North Dakota','ND',1,1,0),(36,'Ohio','OH',1,1,0),(37,'Oklahoma','OK',1,1,0),(38,'Oregon','OR',1,1,0),(39,'Pennsylvania','PA',1,1,0),(40,'Rhode Island','RI',1,1,0),(41,'South Carolina','SC',1,1,0),(42,'South Dakota','SD',1,1,0),(43,'Tennessee','TN',1,1,0),(44,'Texas','TX',1,1,0),(45,'Utah','UT',1,1,0),(46,'Vermont','VT',1,1,0),(47,'Virginia','VA',1,1,0),(48,'Washington','WA',1,1,0),(49,'West Virginia','WV',1,1,0),(50,'Wisconsin','WI',1,1,0),(51,'Wyoming','WY',1,1,0),(52,'Alberta','AB',2,1,0),(53,'British Columbia','BC',2,1,0),(54,'Manitoba','MB',2,1,0),(55,'New Brunswick','NB',2,1,0),(56,'Newfoundland and Labrador','NL',2,1,0),(57,'Northwest Territories','NT',2,1,0),(58,'Nova Scotia','NS',2,1,0),(59,'Nunavut','NU',2,1,0),(60,'Ontario','ON',2,1,0),(61,'Prince Edward Island','PE',2,1,0),(62,'Quebec','QC',2,1,0),(63,'Saskatchewan','SK',2,1,0),(64,'Yukon','YT',2,1,0),(65,'England','England',95,1,0),(66,'Northern Ireland','NorthernIreland',95,1,0),(67,'Scotland','Scottland',95,1,0),(68,'Wales','Wales',95,1,0),(86,'NWFP','NWFP',126,1,0),(87,'FATA','FATA',126,1,0),(88,'Balochistan','Balochistan',126,1,0),(89,'Punjab','Punjab',126,1,0),(90,'Capital','Capital',126,1,0);

INSERT INTO `#__js_job_states`(name,shortRegion,countryid,enabled,serverid) 
		SELECT s_d.name AS name,s_d.shortRegion AS shortRegion,s_d.countryid AS countryid,s_d.enabled AS enabled,s_d.serverid AS serverid
		FROM `#__js_job_states_old` AS s_d 
		JOIN `#__js_job_countries_old` AS c_d ON s_d.countryid = c_d.id
		where  NOT EXISTS (
		SELECT s_o.name 
		from #__js_job_states AS s_o
		JOIN #__js_job_countries AS c ON s_o.countryid = c.id
		WHERE REPLACE(LOWER(s_o.name),' ','') = REPLACE(LOWER(s_d.name),' ','')
		AND REPLACE(LOWER(c.name),' ','') = REPLACE(LOWER(c_d.name),' ', '')
		
		);

update `#__js_job_cities` set enabled=1 where enabled='Y';

update `#__js_job_cities` set enabled=0 where enabled='N';

ALTER TABLE `#__js_job_cities` ADD COLUMN countryid int(11) null AFTER countrycode;

ALTER TABLE `#__js_job_cities` ADD COLUMN stateid int(11) null AFTER statecode;

update `#__js_job_cities`  AS city
join `#__js_job_countries_old` AS oc ON city.countrycode=oc.code 
join `#__js_job_countries` AS country ON oc.name=country.name
set city.countryid=country.id;

update `#__js_job_cities`  AS city
join `#__js_job_states_old` AS so ON city.statecode=so.code 
join `#__js_job_states` AS state ON so.name=state.name
set city.stateid=state.id;

ALTER TABLE `#__js_job_cities` ADD COLUMN cityName varchar(255) null AFTER id;

ALTER TABLE `#__js_job_cities` ADD COLUMN isedit tinyint(1) default 0 AFTER countryid;

ALTER TABLE `#__js_job_cities` ADD COLUMN serverid int(11) null AFTER isedit;

DELETE FROM `#__js_job_fieldsordering` WHERE fieldtitle='Country';

DELETE FROM `#__js_job_fieldsordering` WHERE fieldtitle='State';

DELETE FROM `#__js_job_fieldsordering` WHERE fieldtitle='County';
ALTER TABLE  `#__js_job_fieldsordering` ADD  `isvisitorpublished` TINYINT( 1 ) NOT NULL AFTER  `published`;
INSERT INTO `#__js_job_fieldsordering` (`field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`) VALUES
('section_languages', 'Languages', 254, '85', 3, 1, 1, 1, 0, 0),
('section_sub_language', 'Language 1', 255, '85', 3, 1, 1, 1, 0, 0),
('language_name', 'Language Name', 256, '85', 3, 1, 1, 1, 0, 0),
('language_reading', 'Language Reading', 257, '85', 3, 1, 1, 1, 0, 0),
('language_writing', 'Language Writing', 258, '85', 3, 1, 1, 1, 0, 0),
('language_understading', 'Language Understanding', 259, '85', 3, 1, 1, 1, 0, 0),
('language_where_learned', 'Language Learn', 260, '85', 3, 1, 1, 1, 0, 0),
('section_sub_language1', 'Language 2', 261, '85', 3, 1, 1, 1, 0, 0),
('language1_name', 'Language Name', 262, '85', 3, 1, 1, 1, 0, 0),
('language1_reading', 'Language Reading', 263, '85', 3, 1, 1, 1, 0, 0),
('language1_writing', 'Language Writing', 264, '85', 3, 1, 1, 1, 0, 0),
('language1_understading', 'Language Understanding', 265, '85', 3, 1, 1, 1, 0, 0),
('language1_where_learned', 'Language Learn', 266, '85', 3, 1, 1, 1, 0, 0),
('section_sub_language2', 'Language 3', 267, '85', 3, 1, 1, 1, 0, 0),
('language2_name', 'Language Name', 268, '85', 3, 1, 1, 1, 0, 0),
('language2_reading', 'Language Reading', 269, '85', 3, 1, 1, 1, 0, 0),
('language2_writing', 'Language Writing', 270, '85', 3, 1, 1, 1, 0, 0),
('language2_understading', 'Language Understanding', 271, '85', 3, 1, 1, 1, 0, 0),
('language2_where_learned', 'Language Learn', 272, '85', 3, 1, 1, 1, 0, 0),
('section_sub_language3', 'Language 4', 273, '85', 3, 1, 1, 1, 0, 0),
('language3_name', 'Language Name', 274, '85', 3, 1, 1, 1, 0, 0),
('language3_reading', 'Language Reading', 275, '85', 3, 1, 1, 1, 0, 0),
('language3_writing', 'Language Writing', 276, '85', 3, 1, 1, 1, 0, 0),
('language3_understading', 'Language Understanding', 277, '85', 3, 1, 1, 1, 0, 0),
('keywords','Key Words',21,20,3,1,1,1,1,0),
('language3_where_learned', 'Language Learn', 278, '85', 3, 1, 1, 1, 0, 0);
UPDATE  `#__js_job_fieldsordering` SET isvisitorpublished = published;

INSERT INTO `#__js_job_config` (`configname`, `configvalue`, `configfor`) VALUES
('show_applied_resume_status', '1', 'default'),
('authentication_client_key', '', 'jobsharing'),
('search_resume_zipcode', '1', 'searchresume'),
('newlisting_requiredpackage', '1', 'default'),
('user_registration_captcha', '1', 'default'),
('js_newlisting_requiredpackage', '1', 'default'),
('employer_share_fb_like', '1', 'social'),
('jobseeker_share_fb_like', '1', 'social'),
('employer_share_fb_share', '1', 'social'),
('jobseeker_share_fb_share', '1', 'social'),
('employer_share_fb_comments', '1', 'social'),
('jobseeker_share_fb_comments', '1', 'social'),
('employer_share_google_like', '1', 'social'),
('jobseeker_share_google_like', '1', 'social'),
('employer_share_google_share', '1', 'social'),
('jobseeker_share_google_share', '1', 'social'),
('employer_share_blog_share', '1', 'social'),
('jobseeker_share_blog_share', '1', 'social'),
('employer_share_friendfeed_share', '1', 'social'),
('jobseeker_share_friendfeed_share', '1', 'social'),
('employer_share_linkedin_share', '1', 'social'),
('jobseeker_share_linkedin_share', '1', 'social'),
('employer_share_digg_share', '1', 'social'),
('employer_share_twitter_share', '1', 'social'),
('jobseeker_share_twiiter_share', '1', 'social'),
('employer_share_myspace_share', '1', 'social'),
('jobseeker_share_myspace_share', '1', 'social'),
('employer_share_yahoo_share', '1', 'social'),
('jobseeker_share_yahoo_share', '1', 'social'),
('jobseeker_share_digg_share', '1', 'social'),
('newfolders', '1', 'emcontrolpanel'),
('jsjobupdatecount', '0', 'default'),
('vis_emnewfolders', '0', 'default'),
('resume_subcategories', '1', 'searchresume'),
('resume_subcategories_all', '1', 'searchresume'),
('resume_subcategories_colsperrow', '3', 'searchresume'),
('resume_subcategoeis_max_hight', '250', 'searchresume'),
('employer_resume_alert_fields', '1', 'email'),
('defaultaddressdisplaytype', 'csc', 'default'),
('jobseeker_defaultgroup', '1', 'default'),
('employer_defaultgroup', '6', 'default'),
('default_sharing_city', '', 'jobsharing'),
('default_sharing_state', '', 'jobsharing'),
('default_sharing_country', '', 'jobsharing'),
('job_alert_captcha', '1', 'default'),
('jobseeker_resume_applied_status', '1', 'email'),
('search_resume_keywords','1','searchresume');
DELETE config FROM `#__js_job_config` AS config 
		WHERE config.configname='currency' 
		OR config.configname='defaultcountry' 
		OR config.configname='login_redirect' 
		OR config.configname='hidecountry';

alter table #__js_job_currencies add column code varchar(10) not null default 0 after symbol;

update #__js_job_currencies set code='USD' where symbol='$';

update #__js_job_currencies set code='PKR' where symbol='Rs.';

update #__js_job_currencies set code='GBP' where symbol='£';


UPDATE `#__js_job_config` AS config  SET configvalue='1.0.9.0'
		WHERE config.configname='version';

UPDATE `#__js_job_config` AS config  SET configvalue='1090'
		WHERE config.configname='versioncode';

