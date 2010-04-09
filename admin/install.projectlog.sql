CREATE TABLE IF NOT EXISTS `#__projectlog_categories` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#__projectlog_docs` (
  `id` int(11) NOT NULL auto_increment,
  `project_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `submittedby` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

CREATE TABLE IF NOT EXISTS `#__projectlog_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#__projectlog_groups_mid` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

CREATE TABLE IF NOT EXISTS `#__projectlog_logs` (
  `id` int(11) NOT NULL auto_increment,
  `project_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `loggedby` int(11) NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

CREATE TABLE IF NOT EXISTS `#__projectlog_projects` (
  `id` int(11) NOT NULL auto_increment,
  `category` int(11) NOT NULL default '0',
  `group_access` int(11) NOT NULL default '0',
  `release_id` varchar(20) NOT NULL,
  `job_id` varchar(20) NOT NULL,
  `task_id` varchar(20) NOT NULL,
  `workorder_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `release_date` date NOT NULL default '0000-00-00',
  `contract_from` date NOT NULL default '0000-00-00',
  `contract_to` date NOT NULL default '0000-00-00',
  `location_gen` text NOT NULL,
  `location_spec` text NOT NULL,
  `manager` int(11) NOT NULL default '0',
  `chief` int(11) NOT NULL default '0',
  `technicians` varchar(200) NOT NULL,
  `deployment_from` date NOT NULL default '0000-00-00',
  `deployment_to` date NOT NULL default '0000-00-00',
  `onsite` tinyint(1) NOT NULL default '0',
  `projecttype` varchar(100) NOT NULL,
  `client` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `approved` tinyint(1) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;