<?php

/*

CREATE TABLE `phpwcms_shop_orders` (
  `order_id` int(10) unsigned NOT NULL auto_increment,
  `order_number` varchar(20) NOT NULL default '',
  `order_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `order_name` varchar(255) NOT NULL default '',
  `order_firstname` varchar(255) NOT NULL default '',
  `order_email` varchar(255) NOT NULL default '',
  `order_net` float NOT NULL default '0',
  `order_gross` float NOT NULL default '0',
  `order_payment` varchar(255) NOT NULL default '',
  `order_data` mediumtext NOT NULL,
  `order_status` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`order_id`),
  KEY `order_number` (`order_number`,`order_status`)
) TYPE = MYISAM ;

*/


?>