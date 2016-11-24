<?php

/*

   just a sample how to inject body ID - i.e. to allow specific CSS based things (or JavaScript stuff too)....
   if you define $content['body_id'] = FALSE body tag injection will be hopped

   sample will set body tag injection based on "main structure"
   if it is in home it will fall back to

 */

if(isset($LEVEL_ID[1])) { // lets say it is the main structure root

    $content['body_id'] = $LEVEL_ID[1];

} else { // do nothing

    $content['body_id'] = false;

}
