<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 544 Update Check
function cmsgo_revision_r544() {
    
    $status = true;
    
    // do former revision check – fallback to r543
    if(cmsgo_revision_check_temp('543') !== true) {
        $status = cmsgo_revision_check('543');
    }
    
    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_calendar` WHERE Field='calendar_refid'");
    
    if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) === 'int') {
        
        if($result = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_calendar` CHANGE `calendar_refid` `calendar_refid` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER')) {
            
            _dbUpdate('cmsgo_calendar', array('calendar_refid' => ''), "calendar_refid='0'");
            
        };
        
    }
    
    //modifications 
    
    // Add column newsletter_pub for Newsletter modification
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_newsletter LIKE 'newsletter_pub'");
    if(empty($result)) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_newsletter ADD newsletter_pub datetime NOT NULL DEFAULT '0000-00-00 00:00:00'", 'ALTER');
    }
    // Add column newsletter_lang for Newsletter modification
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_newsletter LIKE 'newsletter_lang'");
    if(empty($result)) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_newsletter ADD newsletter_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }
    // Add column queue_opener for opene newsletter counter
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_newsletterqueue LIKE 'queue_opener'");
    if(empty($result)) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_newsletterqueue ADD queue_opener INT(11) NOT NULL DEFAULT '0'", 'ALTER');
    }
    
    // Add column f_alias for filealias modification
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_file LIKE 'f_alias'");
    if(empty($result)) {
        $result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_file ADD f_alias VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }
    
    // Add column group_modkey for usergroup modules
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_usergroup LIKE 'group_modkey'");
    if(empty($result)) {
       $result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_usergroup ADD group_modkey VARCHAR(20) NOT NULL AFTER `group_active`", 'ALTER');
    }
    
    // Add column group_sys for adding Sysrecords to user groups
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_usergroup LIKE 'group_syskey '");
    if(empty($result)) {
        if($result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_usergroup ADD group_syskey VARCHAR(10) NOT NULL AFTER `group_active`", 'ALTER')) {
            //now we add new sys groups to cmsgo_usergroup
            //first we get all admin users and prepare insert value
            $adminusers = _dbQuery('SELECT `usr_id` FROM `'.DB_PREPEND.'cmsgo_user` WHERE `usr_admin` = 1');
            if(count($adminusers)) {
                foreach ($adminusers as $admins) {
                    $adminids[] = $admins['usr_id'];
                }
                $group_member = implode(',',$adminids);
            } else {
                $group_member = '';
            }
            //basic data for sys group
            $data = array(
                    'group_name'         => 'SYSGROUP',
                    'group_member'         => $group_member ,
                    'group_value'      => '',
                    'group_active'       => 1,
                    'group_trash'       => 0,
                    'group_syskey'      => ''
            );
            
            //array with all sys group values
            $newgroupnames = array('artcent','artnew','artnews','module','adm','admlayout','admtempl','admuser','admugroup','admfc','admalias','admimagealias','admctptempl','admlink','profile','file','filecent','fileaction','fileupload','nl','nllist','nlrecip','nlabo');
            
            foreach ($newgroupnames as $groupname) {
                //if sys group is not existing we add this new group
                if (!_dbQuery('SELECT `group_syskey` FROM `'.DB_PREPEND.'cmsgo_usergroup` WHERE `group_syskey` = '._dbEscape($groupname))) {
                    $data['group_syskey'] = $groupname;
                    _dbInsert(DB_PREPEND.'cmsgo_usergroup', $data);
                }
            }
            
        };
    }
    // end
    
    return $status;
}
