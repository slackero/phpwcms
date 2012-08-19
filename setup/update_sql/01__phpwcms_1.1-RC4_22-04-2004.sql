#
# PHPWCMS database update script
# ==============================
# 22-04-2003

# extending structure category for direct redirect link
ALTER TABLE `phpwcms_articlecat` ADD `acat_redirect` VARCHAR( 255 ) NOT NULL ;

# table for new content part BID
CREATE TABLE `phpwcms_bid` (
  `bid_id` int(11) NOT NULL auto_increment,
  `bid_cid` int(11) NOT NULL default '0',
  `bid_email` text NOT NULL,
  `bid_hash` varchar(255) NOT NULL default '',
  `bid_amount` float NOT NULL default '0',
  `bid_created` timestamp NOT NULL,
  `bid_verified` int(1) NOT NULL default '0',
  `bid_trashed` int(1) NOT NULL default '0',
  `bid_vars` mediumblob NOT NULL,
  PRIMARY KEY  (`bid_id`)
);
