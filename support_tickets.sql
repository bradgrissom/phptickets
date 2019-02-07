-- 
-- Table structure for table `tickets_categories`
-- 

CREATE TABLE `tickets_categories` (
  `tickets_categories_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_categories_name` varchar(20) NOT NULL default '',
  `tickets_categories_order` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tickets_categories_id`),
  UNIQUE KEY `tickets_categories_name` (`tickets_categories_name`),
  KEY `tickets_categories_order` (`tickets_categories_order`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `tickets_categories`
-- 

INSERT INTO `tickets_categories` VALUES (1, 'Department 1', 1);
INSERT INTO `tickets_categories` VALUES (2, 'Department 2', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `tickets_status`
-- 

CREATE TABLE `tickets_status` (
  `tickets_status_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_status_name` varchar(20) NOT NULL default '',
  `tickets_status_order` tinyint(3) unsigned NOT NULL default '1',
  `tickets_status_color` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`tickets_status_id`),
  KEY `tickets_status_name` (`tickets_status_name`),
  KEY `tickets_status_order` (`tickets_status_order`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `tickets_status`
-- 

INSERT INTO `tickets_status` VALUES (1, 'Low', 1, 'FFCC99');
INSERT INTO `tickets_status` VALUES (2, 'Medium', 2, 'FF9966');
INSERT INTO `tickets_status` VALUES (3, 'High', 3, 'FF6633');
INSERT INTO `tickets_status` VALUES (4, 'Urgent', 4, 'FF3300');

-- --------------------------------------------------------

-- 
-- Table structure for table `tickets_tickets`
-- 

CREATE TABLE `tickets_tickets` (
  `tickets_id` smallint(5) unsigned NOT NULL auto_increment,
  `tickets_username` varchar(16) NOT NULL default '',
  `tickets_subject` varchar(50) NOT NULL default '',
  `tickets_timestamp` bigint(10) unsigned NOT NULL default '0',
  `tickets_status` varchar(10) NOT NULL default 'Open',
  `tickets_name` varchar(50) NOT NULL default '',
  `tickets_email` varchar(50) NOT NULL default '',
  `tickets_urgency` tinyint(3) unsigned NOT NULL default '1',
  `tickets_category` tinyint(3) unsigned NOT NULL default '1',
  `tickets_admin` varchar(20) NOT NULL default 'Client',
  `tickets_child` smallint(5) unsigned NOT NULL default '0',
  `tickets_question` text NOT NULL,
  PRIMARY KEY  (`tickets_id`),
  KEY `tickets_username` (`tickets_username`),
  KEY `tickets_urgency` (`tickets_urgency`),
  KEY `tickets_category` (`tickets_category`),
  KEY `tickets_child` (`tickets_child`),
  KEY `tickets_status` (`tickets_status`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tickets_tickets`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tickets_users`
-- 

CREATE TABLE `tickets_users` (
  `tickets_users_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_users_name` varchar(50) NOT NULL default '',
  `tickets_users_username` varchar(16) NOT NULL default '',
  `tickets_users_password` varchar(16) NOT NULL default '',
  `tickets_users_email` varchar(100) NOT NULL default '',
  `tickets_users_lastlogin` bigint(10) unsigned NOT NULL default '0',
  `tickets_users_newlogin` bigint(10) unsigned NOT NULL default '0',
  `tickets_users_admin` varchar(5) NOT NULL default 'User',
  `tickets_users_status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tickets_users_id`),
  KEY `tickets_users_username` (`tickets_users_username`),
  KEY `tickets_users_email` (`tickets_users_email`),
  KEY `tickets_users_admin` (`tickets_users_admin`),
  KEY `tickets_users_status` (`tickets_users_status`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tickets_users`
-- 

INSERT INTO `tickets_users` VALUES (1, 'Administrator', 'administrator', 'password', 'email@yourdomain.com', 0, 0, 'Admin', 1);