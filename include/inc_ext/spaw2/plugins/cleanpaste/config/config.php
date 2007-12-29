<?php
// Clean paste config
// When to apply cleaning to the pasted content
// "selective" -  when content matches (javascript) regular expression 
//                pattern specified in the cofiguration
// "always"
// "never"
SpawConfig::setStaticConfigItem("PG_CLEANPASTE_CLEAN", 'selective', SPAW_CFG_TRANSFER_JS);
// pattern to determine that content should be cleaned in "selective" mode
SpawConfig::setStaticConfigItem("PG_CLEANPASTE_PATTERN", 
  '(urn:schemas-microsoft-com:office)'
  .'|(<([^>]*)style([\s]*)=([\s]*)([\"]*)mso)'
  .'|(<([^>]*)class([\s]*)=([\s]*)([\"]*)mso)'
  .'|(<o:)'
  , 
  SPAW_CFG_TRANSFER_JS);
?>