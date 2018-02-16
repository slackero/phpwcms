#####################################################
#
#  CMSGO SQL Update
#  
#  Upgrade release 1.3.2 to 1.3.3
#
#####################################################


ALTER TABLE `cmsgo_articlecat` ADD `acat_overwrite` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `cmsgo_articlecontent` ADD `acontent_paginate_page` INT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `cmsgo_articlecontent` ADD INDEX ( `acontent_paginate_page` ) ;
ALTER TABLE `cmsgo_articlecontent` ADD `acontent_paginate_title` VARCHAR( 255 ) NOT NULL ;
