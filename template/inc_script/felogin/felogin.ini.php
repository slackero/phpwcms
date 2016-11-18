; <?php exit('Sorry this is no public file. Good bye!'); ?>

; 1) Do not edit first line of this file!
;    It's important regarding security.
; 2) Do not use Windows Notepad to edit this file!
;    Recommendation: www.pspad.com
; 3) Format this file like Windows INI files
;    http://en.wikipedia.org/wiki/INI_file


; A) General settings
; ===================

; Put in the level depth, root level = 0
FELOGIN_LEVEL_DEPTH			= 0

; This is the ID of parent's level, root would be 0
FELOGIN_LEVEL_ID			= 0

; If user is logged in, a new level will be inserted which
; holds the logout link - and this is the link text
; set FELOGIN_LOGOUT_LINK = 0 to avoid that
; {FELOGOUT_LINK_PREFIX}	replacement tag for FELOGIN_LOGOUT_LINK_PREFIX
; {FELOGOUT_SUFFIX}			replacement tag for FELOGIN_LOGOUT_LINK_SUFFIX
; {FELOGIN_USER}			replacement tag for the username (logged in)
FELOGIN_LOGOUT_LINK			= "Logout {FELOGOUT_PREFIX}{FELOGIN_USER}{FELOGOUT_SUFFIX}"

; please use double singe quotes "''" as quote in HTML text
; otherwise parsing the INI file will fail

; HTML prefix mainly used for logout link
FELOGIN_LOGOUT_LINK_PREFIX	= "<span style='color:#FF0000;'>"

; HTML suffix mainly used for logout link
FELOGIN_LOGOUT_LINK_SUFFIX	= "</span>"

; Logout GET value
FELOGIN_LOGOUT_GET_VALUE	= "yes"

; Error messages
FELOGIN_ERROR_EMPTY_USER	= "Insert your username"
FELOGIN_ERROR_UNKNOWN_USER	= "Please proof, the user is unknow"
FELOGIN_ERROR_EMPTY_PASS	= "Insert your password"
FELOGIN_ERROR_WRONG_PASS	= "Wrong password"

; Wrap error messages by HTML
FELOGIN_ERROR_PREFIX		= "<p class='error'>"
FELOGIN_ERROR_SUFFIX		= "</p>"


; B) Setting user/password and permitted levels
; =============================================

; Set ID of level for which the user will get permissions
; Sample: [10]

; list allowed login/password combination for section
; define 1 .. n entries per section
; login = password

; Sample level 1
[1]
hansi		= hansi123
klausi		= klausi123

; Sample level 2
[2]
wusi		= wusi123
