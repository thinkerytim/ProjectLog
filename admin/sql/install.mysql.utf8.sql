CREATE TABLE `#__projectlog_projects` (
  `id` integer NOT NULL auto_increment,
  `asset_id` int(10) unsigned NOT NULL default '0',
  `catid` integer NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `job_id` varchar(100) default NULL,
  `release_id` varchar(100) default NULL,
  `task_id` varchar(100) default NULL,
  `workorder_id` varchar(100) default NULL,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `misc` mediumtext,
  `release_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `contract_from` datetime NOT NULL default '0000-00-00 00:00:00',
  `contract_to` datetime NOT NULL default '0000-00-00 00:00:00',
  `specific_loc` text,
  `general_loc` text,
  `manager` int(11) default NULL default '0',
  `chief` varchar(200) NOT NULL,
  `technicians` varchar(200) NOT NULL,
  `deployment_from` datetime NOT NULL default '0000-00-00 00:00:00',
  `deployment_to` datetime NOT NULL default '0000-00-00 00:00:00',
  `onsite` tinyint(1) NOT NULL default '0',
  `project_type` varchar(100) default NULL,
  `client` varchar(100) default NULL,
  `status` varchar(100) NOT NULL,
  `approved` tinyint(1) NOT NULL default '1', 
  `image` varchar(255) default NULL,
  `imagepos` varchar(20) default NULL,
  `email_to` varchar(255) default NULL,
  `default_con` tinyint(1) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` integer unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` integer NOT NULL default '0',
  `params` text NOT NULL,
  `mobile` varchar(255) NOT NULL default '',
  `webpage` varchar(255) NOT NULL default '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL default '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL default '0' COMMENT 'Set if article is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `version` int(10) unsigned NOT NULL default '1',
  `hits` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_client` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
)  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__projectlog_logs` (
  `id` int(11) NOT NULL auto_increment,
  `asset_id` int(10) unsigned NOT NULL default '0',
  `project_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',  
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `checked_out` integer unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` integer NOT NULL default '0',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `language` char(7) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_client` (`published`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__projectlog_docs` (
  `id` integer NOT NULL auto_increment,
  `asset_id` int(10) unsigned NOT NULL default '0',
  `project_id` integer NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `checked_out` integer unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` integer NOT NULL default '0',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `language` char(7) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;



