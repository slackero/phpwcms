#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.4 to 1.3.5
#
#####################################################


ALTER TABLE `phpwcms_articlecat` ADD `acat_overwrite` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_paginate_page` INT( 5 ) UNSIGNED NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_paginate_title` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD INDEX ( `acontent_paginate_page` ) ;