<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Language: Greek, Language Code: gr
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'χρήστες online';

// Login Page
$BL["login_text"]                       = 'Εισάγετε στοιχεία εισόδου';
$BL['login_error']                      = 'Λάθος κατα την εισαγωγή!';
$BL["login_username"]                   = 'όνομα χρήστη';
$BL["login_userpass"]                   = 'κωδικός πρόσβασης';
$BL["login_button"]                     = 'Σύνδεση';
$BL["login_lang"]                       = 'γλώσσα επικοινωνίας';

// phpwcms.php
$BL['be_nav_logout']                    = 'ΑΠΟΣΥΝΔΕΣΗ';
$BL['be_nav_articles']                  = 'ΑΡΘΡΑ';
$BL['be_nav_files']                     = 'ΑΡΧΕΙΟ';
$BL['be_nav_modules']                   = 'MODULES';
$BL['be_nav_messages']                  = 'ΜΗΝΥΜΑΤΑ';
$BL['be_nav_chat']                      = 'ΣΥΝΟΜΙΛΙΑ';
$BL['be_nav_profile']                   = 'ΠΡΟΦΙΛ';
$BL['be_nav_admin']                     = 'ΔΙΑΧΕΙΡΙΣΤΗΣ';
$BL['be_nav_discuss']                   = 'ΣΥΖΗΤΗΣΗ';

$BL['be_page_title']                    = 'phpwcms διαχείριση';

$BL['be_subnav_article_center']         = 'κέντρο τύπου';
$BL['be_subnav_article_new']            = 'νέο άρθρο';
$BL['be_subnav_file_center']            = 'κέντρο αρχείων';
$BL['be_subnav_file_ftptakeover']       = 'ftp takeover';
$BL['be_subnav_mod_artists']            = 'συντάκτης, κατηγορία, ύφος';
$BL['be_subnav_msg_center']             = 'κέντρο μηνυμάτων';
$BL['be_subnav_msg_new']                = 'νέο μήνυμα';
$BL['be_subnav_msg_newsletter']         = 'συνδρομή';
$BL['be_subnav_chat_main']              = 'κύρια σελίδα συνομιλίας';
$BL['be_subnav_chat_internal']          = 'εσωτερική συνομιλία';
$BL['be_subnav_profile_login']          = 'πληροφορίες σύνδεσης';
$BL['be_subnav_profile_personal']       = 'προσωπικά στοιχεία';
$BL['be_subnav_admin_pagelayout']       = 'σχεδιάγραμμα σελίδων';
$BL['be_subnav_admin_templates']        = 'πρότυπα';
$BL['be_subnav_admin_css']              = 'προεπιλεγμένο css';
$BL['be_subnav_admin_sitestructure']    = 'δομή ιστοσελίδας';
$BL['be_subnav_admin_users']            = 'χρήστες';
$BL['be_subnav_admin_filecat']          = 'κατηγορίες αρχείων';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'αριθμός άρθρου';
$BL['be_func_struct_preview']           = 'προεπισκόπηση';
$BL['be_func_struct_edit']              = 'επεξεργασία άρθρου';
$BL['be_func_struct_sedit']             = 'επεξεργασία κατηγορίας';
$BL['be_func_struct_cut']               = 'αποκοπή άρθρου';
$BL['be_func_struct_nocut']             = 'απενεργοποίηση αποκοπής άρθρου';
$BL['be_func_struct_svisible']          = 'εναλλαγή ορατό/αόρατο';
$BL['be_func_struct_spublic']           = 'εναλλαγή δημόσιο/προσωπικό';
$BL['be_func_struct_sort_up']           = 'ταξινόμηση προς τα πάνω';
$BL['be_func_struct_sort_down']         = 'ταξινόμηση προς τα κάτω';
$BL['be_func_struct_del_article']       = 'διαγραφή άρθρου';
$BL['be_func_struct_del_jsmsg']         = 'Θέλετε πραγματικά \nνα διαγράψετε το άρθρο?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'δημιουργία νέου άρθρου στην υπάρχουσα κατηγορία';
$BL['be_func_struct_paste_article']     = 'επικόλληση άρθρου στην υπάρχουσα κατηγορία';
$BL['be_func_struct_insert_level']      = 'δημιουργία κατηγορίας';
$BL['be_func_struct_paste_level']       = 'επικόλληση στην κατηγορία';
$BL['be_func_struct_cut_level']         = 'αποκοπή κατηγορίας';
$BL['be_func_struct_no_cut']            = "Δεν είναι δυνατό να κοπεί το ριζικό επίπεδο!";
$BL['be_func_struct_no_paste1']         = "Δεν είναι δυνατό να γίνει επικόλληση εδώ!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'that should paste in here';
$BL['be_func_struct_paste_cancel']      = 'ακύρωση αλλαγής δομής επιπέδου';
$BL['be_func_struct_del_struct']        = 'διαγραφή δομής επιπέδου';
$BL['be_func_struct_del_sjsmsg']        = 'Θέλετε πραγματικά να διαγράψετε \nτην δομή επιπέδου?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'άνοιγμα';
$BL['be_func_struct_close']             = 'κλείσιμο';
$BL['be_func_struct_empty']             = 'άδειασμα';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'απλό κείμενο';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'κώδικας';
$BL['be_ctype_textimage']               = 'κείμενο w/με εικόνες';
$BL['be_ctype_images']                  = 'εικόνες';
$BL['be_ctype_bulletlist']              = 'λίστα (table)';
$BL['be_ctype_ullist']                  = 'list';
$BL['be_ctype_link']                    = 'υπερσύνδεση &amp; email';
$BL['be_ctype_linklist']                = 'λίστα υπερσυνδέσεων';
$BL['be_ctype_linkarticle']             = 'υπερσυνδέσεις για το άρθρο';
$BL['be_ctype_multimedia']              = 'πολυμέσα';
$BL['be_ctype_filelist']                = 'λίστα αρχείων';
$BL['be_ctype_emailform']               = 'φόρμα email';
$BL['be_ctype_newsletter']              = 'ενημερωτικό δελτίο';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Το προφίλ δημιουργήθηκε με επιτυχία.';
$BL['be_profile_create_error']          = 'Παρουσιάστηκε σφάλμα κατά τη δημιουργία.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Τα στοιχεία του προφίλ ανανεώθηκαν επιτυχώς.';
$BL['be_profile_update_error']          = 'Παρουσιάστηκε σφάλμα κατά την ανανέωση.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'η ταυτότητα χρήστη {VAL} δεν είναι έγκυρη';
$BL['be_profile_account_err2']          = 'ο κωδικός πρόσβασης είναι πολύ μικρός (μόνο {VAL} χαρακτήρες: χρειάζονται τουλάχιστον 5 χαρακτήρες)';
$BL['be_profile_account_err3']          = 'ο κωδικός πρόσβασης πρέπει να είναι πανομοιότυπος με τον επαναλαμβανόμενο';
$BL['be_profile_account_err4']          = 'η διεύθυνση ηλεκτρονικού ταχυδρομείου {VAL} δεν είναι έγκυρη';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'τα προσωπικά σας στοιχεία';
$BL['be_profile_data_text']             = 'Τα προσωπικά στοιχεία ειναι προαιρετικά. Μπορούν να βοηθήσουν άλλους χρήστες ή επισκέπτες του ιστότοπου να γνωρίσουν περισσότερα για σας, τα ενδιαφέροντα και τις ικανότητές σας. Εάν επιλέξετε το κατάλληλο κουτάκι οι χρήστες μπορούν να δουν τα στοιχεία του προφίλ σας στο δημόσιο χώρο ή σε σελίδες άρθρων. (ή ακόμα και όχι).';
$BL['be_profile_label_title']           = 'τίτλος';
$BL['be_profile_label_firstname']       = 'όνομα';
$BL['be_profile_label_name']            = 'επίθετο';
$BL['be_profile_label_company']         = 'εταιρεία';
$BL['be_profile_label_street']          = 'διεύθυνση';
$BL['be_profile_label_city']            = 'πόλη';
$BL['be_profile_label_state']           = 'περιοχή';
$BL['be_profile_label_zip']             = 'ταχυδρομικός κώδικας';
$BL['be_profile_label_country']         = 'χώρα';
$BL['be_profile_label_phone']           = 'τηλέφωνο';
$BL['be_profile_label_fax']             = 'φαξ';
$BL['be_profile_label_cellphone']       = 'κινητό';
$BL['be_profile_label_signature']       = 'υπογραφή';
$BL['be_profile_label_notes']           = 'σημειώσεις';
$BL['be_profile_label_profession']      = 'επάγγελμα';
$BL['be_profile_label_newsletter']      = 'ενημερωτικό δελτίο';
$BL['be_profile_text_newsletter']       = 'Επιθυμώ να λαμβάνω γενικά ενημερωτικά δελτία του phpwcms.';
$BL['be_profile_label_public']          = 'δημόσιο';
$BL['be_profile_text_public']           = 'Ο καθένας μπορέι να δει το προσωπικό μου προφίλ.';
$BL['be_profile_label_button']          = 'ανανέωση προσωπικών στοιχείων';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'στοιχεία εισόδου';
$BL['be_profile_account_text']          = 'Κανονικά δεν είναι απαραίτητο να αλλαξετε το όνομα χρήστη.<br />Πρέπει να αλλάζετε τον κωδικό πρόσβασής σας ανα διαστήματα για μεγαλύτερη ασφάλεια.';
$BL['be_profile_label_err']             = 'παρακαλώ ελέγξτε';
$BL['be_profile_label_username']        = 'όνομα χρήστη';
$BL['be_profile_label_newpass']         = 'καινούριος κωδικός πρόσβασης';
$BL['be_profile_label_repeatpass']      = 'επαναλάβετε καινούριο κωδικό πρόσβασης';
$BL['be_profile_label_email']           = 'διεύθυνση ηλεκτρονικού ταχυδρομείου';
$BL['be_profile_account_button']        = 'ανανέωση';
$BL['be_profile_label_lang']            = 'γλώσσα';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'επεξεργασία αρχείων που φορτώνονται μέσω FTP';
$BL['be_ftptakeover_mark']              = 'σημάδεμα';
$BL['be_ftptakeover_available']         = 'διαθέσιμο αρχείο';
$BL['be_ftptakeover_size']              = 'μέγεθος';
$BL['be_ftptakeover_nofile']            = 'δέν υπάρχει διαθέσιμο αρχείο &#8211; πρέπει να φορτώσετε ένα μέσω ftp';
$BL['be_ftptakeover_all']               = 'ΌΛΑ';
$BL['be_ftptakeover_directory']         = 'φάκελος';
$BL['be_ftptakeover_rootdir']           = 'ριζικός κατάλογος';
$BL['be_ftptakeover_needed']            = 'απαραίτητο!!! (πρέπει να επιλέξετε ένα)';
$BL['be_ftptakeover_optional']          = 'προαιρετικό';
$BL['be_ftptakeover_keywords']          = 'λέξεις κλειδιά';
$BL['be_ftptakeover_additional']        = 'επιπλέον';
$BL['be_ftptakeover_longinfo']          = 'long info';
$BL['be_ftptakeover_status']            = 'κατάσταση';
$BL['be_ftptakeover_active']            = 'ενεργός';
$BL['be_ftptakeover_public']            = 'δημόσιο';
$BL['be_ftptakeover_createthumb']       = 'δημιουργία μικρογραφίων';
$BL['be_ftptakeover_button']            = 'επεξεργασία επιλεγμένων αρχείων';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'κέντρο αρχείων';
$BL['be_ftab_createnew']                = 'δημιουργία νέου φακέλου στον κυρίο κατάλογο';
$BL['be_ftab_paste']                    = 'paste clipboard file into root directory';
$BL['be_ftab_disablethumb']             = 'απενεργοποίηση λιστας μικρογραφίων';
$BL['be_ftab_enablethumb']              = 'ενεργοποίηση λιστας μικρογραφίων';
$BL['be_ftab_private']                  = 'προσωπικά&nbsp;αρχεία';
$BL['be_ftab_public']                   = 'δημόσια&nbsp;αρχεία';
$BL['be_ftab_search']                   = 'αναζήτηση';
$BL['be_ftab_trash']                    = 'κάδος&nbsp;ανακύκλωσης';
$BL['be_ftab_open']                     = 'άνοιγμα όλων των καταλόγων';
$BL['be_ftab_close']                    = 'κλείσιμο όλων των ανοιχτών καταλόγων';
$BL['be_ftab_upload']                   = 'φόρτωση του αρχείου στον κυρίο κατάλογο';
$BL['be_ftab_filehelp']                 = 'άνοιγμα βοήθειας';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'κύριος φάκελος';
$BL['be_fpriv_title']                   = 'δημιουργήστε νέο κατάλογο';
$BL['be_fpriv_inside']                  = 'μέσα';
$BL['be_fpriv_error']                   = 'λάθος: συμπληρώστε όνομα για τον κατάλογο';
$BL['be_fpriv_name']                    = 'όνομα';
$BL['be_fpriv_status']                  = 'κατάσταση';
$BL['be_fpriv_button']                  = 'δημιουργία νέου καταλόγου';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'επεξεργασία καταλόγου';
$BL['be_fpriv_newname']                 = 'νέο όνομα';
$BL['be_fpriv_updatebutton']            = 'ενημέρωση πληροφοριών καταλόγου';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Επιλέξτε ένα αρχείο που επιθυμείτε να φορτώσετε';
$BL['be_fprivup_err2']                  = 'Το μέγεθος του φορτωμένου αρχείου είναι μεγαλύτερο από';
$BL['be_fprivup_err3']                  = 'Σημειώθηκε λάθος κατα την εγγραφή του αρχείου στον αποθηκευτικό χώρο';
$BL['be_fprivup_err4']                  = 'Σημειώθηκε λάθος κατα την δημιουργία του φακέλου χρήστη.';
$BL['be_fprivup_err5']                  = 'δεν υπάρχουν μικρογραφίες';
$BL['be_fprivup_err6']                  = 'Παρακαλώ μην προσπαθήσετε πάλι - λάθος συστήματος! Επικοινωνήστε με τον <a href="mailto:{VAL}">διαχειριστή</a> του συστήματος το συντομότερο δυνατόν!';
$BL['be_fprivup_title']                 = 'φόρτωση αρχείων';
$BL['be_fprivup_button']                = 'φόρτωση αρχείων';
$BL['be_fprivup_upload']                = 'φόρτωση';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'επεξεργασία πληροφορίων αρχείου';
$BL['be_fprivedit_filename']            = 'όνομα αρχείου';
$BL['be_fprivedit_created']             = 'δημιουργήθηκε';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'έπαναφορά αρχικού ονόματος του αρχείου';
$BL['be_fprivedit_clockwise']           = 'περιστροφή της μικρογραφίας κατα τη φορά του ρολογιού [γνήσιο αρχείο +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'περιστροφή της μικρογραφίας αντίθετα της φοράς του ρολογιού [γνήσιο αρχείο -90&deg;]';
$BL['be_fprivedit_button']              = 'ενημέρωση πληροφοριών αρχείου';
$BL['be_fprivedit_size']                = 'μέγεθος';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'φορτώστε το αρχείο στον φάκελο';
$BL['be_fprivfunc_makenew']             = 'δημιουργία νεου φακέλου μεσα';
$BL['be_fprivfunc_paste']               = 'paste clipboard file into dir';
$BL['be_fprivfunc_edit']                = 'επεξεργασία φακέλου';
$BL['be_fprivfunc_cactive']             = 'εναλλαγή ενεργό/ανενεργό';
$BL['be_fprivfunc_cpublic']             = 'εναλλαγή δημόσιο/προσωπικό';
$BL['be_fprivfunc_deldir']              = 'διαγραφή φακέλου';
$BL['be_fprivfunc_jsdeldir']            = 'Θέλετε όντως \nνα διαγράψετε τόν φάκελο?';
$BL['be_fprivfunc_notempty']            = 'ο φάκελος {VAL} δέν είναι άδειος!';
$BL['be_fprivfunc_opendir']             = 'άνοιγμα φακέλου';
$BL['be_fprivfunc_closedir']            = 'κλείσιμο φακέλου';
$BL['be_fprivfunc_dlfile']              = 'κατεβάστε το αρχείο';
$BL['be_fprivfunc_clipfile']            = 'clipboard file';
$BL['be_fprivfunc_cutfile']             = 'αποκοπή';
$BL['be_fprivfunc_editfile']            = 'επεξεργασία πληροφορίων αρχείου';
$BL['be_fprivfunc_cactivefile']         = 'εναλλαγή ενεργό/ανενεργό';
$BL['be_fprivfunc_cpublicfile']         = 'εναλλαγή δημόσιο/προσωπικό';
$BL['be_fprivfunc_movetrash']           = 'μεταφορά στον κάδο ανακύκλωσης';
$BL['be_fprivfunc_jsmovetrash1']        = 'Θέλετε όντως να μεταφέρετε το';
$BL['be_fprivfunc_jsmovetrash2']        = 'στόν φάκελο κάδου ανακύκλωσης?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'δεν υπαρχουν ιδιωτικά αρχεία ή φάκελοι';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'χρήστης';
$BL['be_fpublic_nofiles']               = 'δεν υπαρχουν δημόσια αρχεία ή φάκελοι';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'ο κάδος ανακύκλωσης είναι άδειος';
$BL['be_ftrash_show']                   = 'εμφάνιση ιδιωτικών αρχείων';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Θέλετε να αποκαταστήσετε το {VAL} \nκαι να το εισάγετε πίσω στον προσωπικό κατάλογο?';
$BL['be_ftrash_delete']                 = 'Θέλετε όντως να διαγράψετε το {VAL}?';
$BL['be_ftrash_undo']                   = 'επαναφορά';
$BL['be_ftrash_delfinal']               = 'οριστική διαγραφή';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'search string is empty.';
$BL['be_fsearch_title']                 = 'αναζήτηση αρχείων';
$BL['be_fsearch_infotext']              = 'Αυτή είναι μια απλή αναζήτηση για πληροφορίες αρχείων. Ψάχνει για τις αντιστοιχίες στις λέξεις κλειδιά,<br />ονόματα αρχείων και περιγραφές αρχείων. Δέν υπάρχει υποστήριξη για wildcards. Ξεχωρίστε πολλαπλές λέξεις κλειδιά<br />με κενό. Επιλέξτε ΚΑΙ/Η και ποιά αρχεία να αναζητηθούν: προσωπικά/δημόσια.';
$BL['be_fsearch_nonfound']              = 'δε βρέθηκαν αρχεία για την αναζήτησή σας. διορθώστε τις τιμές αναζήτησής σας!';
$BL['be_fsearch_fillin']                = 'παρακαλώ συμπληρώστε την αναζητούμενη λέξη στο παραπάνω πεδίο.';
$BL['be_fsearch_searchlabel']           = 'αναζήτηση για';
$BL['be_fsearch_startsearch']           = 'έναρξη αναζήτησης';
$BL['be_fsearch_and']                   = 'ΚΑΙ';
$BL['be_fsearch_or']                    = 'Ή';
$BL['be_fsearch_all']                   = 'όλα τα αρχεία';
$BL['be_fsearch_personal']              = 'ιδιωτικό';
$BL['be_fsearch_public']                = 'δημόσιο';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'εσωτερική συνομιλία';
$BL['be_chat_info']                     = 'Εδώ μπορείτε να συζητήσετε με άλλους χρήστες phpwcms για οτιδήποτε θέλετε. Αυτό το μέσο είναι για συζήτηση σε πραγματικό χρόνο αλλά μπορείτε επίσης να αφήσετε ένα μήνυμα που μπορούν να το διαβάσουν όλοι. Εαν θέλετε να ανταλλάξετε ιδέες με άλλους παρακαλείσθε να χρησιμοποιήστε τη συζήτηση (σε επόμενες εκδόσεις phpwcms).';
$BL['be_chat_start']                    = 'πατήστε εδώ για έναρξη της συζήτησης';
$BL['be_chat_lines']                    = 'γραμμές συζήτησης';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'κέντρο μηνυμάτων';
$BL['be_msg_new']                       = 'καινούρια';
$BL['be_msg_old']                       = 'παλιά';
$BL['be_msg_senttop']                   = 'απεσταλμένα';
$BL['be_msg_del']                       = 'διεγραμμένα';
$BL['be_msg_from']                      = 'από';
$BL['be_msg_subject']                   = 'θέμα';
$BL['be_msg_date']                      = 'ημερομηνία/ώρα';
$BL['be_msg_close']                     = 'κλείσιμο μηνύματος';
$BL['be_msg_create']                    = 'δημιουργία νέου μηνύματος';
$BL['be_msg_reply']                     = 'απαντήστε σε αυτό το μήνυμα';
$BL['be_msg_move']                      = 'μετακίνηση αυτού του μηνύματος στον κάδο';
$BL['be_msg_unread']                    = 'μη ανεγνωσμένα ή καινούρια μηνύματα';
$BL['be_msg_lastread']                  = 'τελευταία {VAL} ανεγνωσμένα μηνύματα';
$BL['be_msg_lastsent']                  = 'τελευταία {VAL} απεσταλμένα μηνύματα';
$BL['be_msg_marked']                    = 'μηνύματα επιλεγμένα για διαγραφή (κάδος)';
$BL['be_msg_nomsg']                     = 'δε βρέθηκε κανένα μήνυμα μέσα σε αυτόν τον φάκελο';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'σταλμένο από';
$BL['be_msg_on']                        = 'στις';
$BL['be_msg_msg']                       = 'μήνυμα';
$BL['be_msg_err1']                      = 'ξεχάσατε να προσθέσετε παραλήπτη...';
$BL['be_msg_err2']                      = 'συπληρώστε το πεδίο του θέματος (ο παραλήπτης μπορέι να χειριστεί το μήνυμά σας καλύτερα)';
$BL['be_msg_err3']                      = 'δεν έχει νόημα να στείλετε ένα κενό μήνυμα ;-)';
$BL['be_msg_sent']                      = 'καινούριο μήνυμα εστάλη!';
$BL['be_msg_fwd']                       = 'θα μεταφερθείτε στο κέντρο μηνυμάτων ή';
$BL['be_msg_newmsgtitle']               = 'σύνθεση νέου μηνύματος';
$BL['be_msg_err']                       = 'σφάλμα στην αποστολή του μηνύματος';
$BL['be_msg_sendto']                    = 'αποστολή μηνύματος προς';
$BL['be_msg_available']                 = 'λίστα διαθέσιμων παραληπτών';
$BL['be_msg_all']                       = 'στείλτε το μήνυμα σε όλους τους παραλήπτες';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'συνδρομές ενημερωτικών δελτίων';
$BL['be_newsletter_titleedit']          = 'επεξεργασία συνδρομών ενημερωτικών δελτίων';
$BL['be_newsletter_new']                = 'δημιουργία νέου';
$BL['be_newsletter_add']                = 'προσθήκη&nbsp;συνδρομής&nbsp;ενημερωτικού δελτίου';
$BL['be_newsletter_name']               = 'όνομα';
$BL['be_newsletter_info']               = 'πληροφορίες';
$BL['be_newsletter_button_save']        = 'Αποθήκευση συνδρομής';
$BL['be_newsletter_button_cancel']      = 'Ακύρωση';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'η ταυτότητα χρήστη δεν είναι έγκυρη, επιλέξτε μία διαφορετική';
$BL['be_admin_usr_err2']                = 'η ταυτότητα χρήστη είναι κενή (απαιτείται)';
$BL['be_admin_usr_err3']                = 'ο κωδικός προσβασης είναι κενός (απαιτείται)';
$BL['be_admin_usr_err4']                = "η διεύθυνση ηλεκτρονικού ταχυδρομείου δεν είναι έγκυρη";
$BL['be_admin_usr_err']                 = 'σφάλμα';
$BL['be_admin_usr_mailsubject']         = 'καλωσήρθατε στο phpwcms';
$BL['be_admin_usr_mailbody']            = "ΚΑΛΩΣΗΡΘΑΤΕ ΣΤΟ PHPWCMS\n\n    ταυτότητα χρήστη: {LOGIN}\n    κωδικός πρόσβασης: {PASSWORD}\n\n\nΜπορείτε να συνδεθείτε εδώ: {LOGIN_PAGE}\n\nδιαχειριστής phpwcms\n ";
$BL['be_admin_usr_title']               = 'προσθήκη νέου χρήστη';
$BL['be_admin_usr_realname']            = 'πραγματικό όνομα';
$BL['be_admin_usr_setactive']           = 'ενεργοποίηση χρήστη';
$BL['be_admin_usr_iflogin']             = 'εαν ενεργοποιηθεί ο χρήστης μπορεί να συνδέεται';
$BL['be_admin_usr_isadmin']             = 'ο χρήστης είναι διαχειριστής';
$BL['be_admin_usr_ifadmin']             = 'εαν ενεργοποιηθεί ο χρήστης έχει δικαιώματα διαχειριστή';
$BL['be_admin_usr_verify']              = 'επαλήθευση';
$BL['be_admin_usr_sendemail']           = 'στείλτε ένα μήνυμα ηλεκτρονικού ταχυδρομείου στο νέο χρήστη με τις πληροφορίες λογαριασμού';
$BL['be_admin_usr_button']              = 'αποστολή στοιχείων χρήστη';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'επεξεργασία λογαριασμού χρήστη';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - στοιχεία λογαριασμού άλλαξαν';
$BL['be_admin_usr_emailbody']           = "PHPWCMS ΠΛΗΡΟΦΟΡΙΕΣ ΛΟΓΑΡΙΑΣΜΟΥ ΧΡΗΣΤΗ ΑΛΛΑΞΑΝ\n\n    όνομα χρήστη: {LOGIN}\n    κωδικός: {PASSWORD}\n\n\nΜπορείτε να συνδεθείτε εδώ: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[ΚΑΜΙΑ ΑΛΛΑΓΗ - ΧΡΗΣΙΜΟΠΟΙΗΣΤΕ ΤΟΝ ΓΝΩΣΤΟ ΚΩΔΙΚΟ ΠΡΟΣΒΑΣΗΣ]';
$BL['be_admin_usr_ebutton']             = 'ανανέωση στοιχείων χρήστη';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'λίστα χρηστών phpwcms';
$BL['be_admin_usr_ldel']                = 'ΠΡΟΣΟΧΗ!&#13;Αυτό θα διαγράψει τον χρήστη';
$BL['be_admin_usr_create']              = 'δημιουργία νέου χρήστη';
$BL['be_admin_usr_editusr']             = 'επεξεργασία χρήστη';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'δομή ιστότοπου';
$BL['be_admin_struct_child']            = '(child of)';
$BL['be_admin_struct_index']            = 'αρχική σελίδα';
$BL['be_admin_struct_cat']              = 'τίτλος κατηγορίας';
$BL['be_admin_struct_hide1']            = 'απόκρυψη';
$BL['be_admin_struct_hide2']            = 'this&nbsp;category&nbsp;in&nbsp;menu';
$BL['be_admin_struct_info']             = 'category infotext';
$BL['be_admin_struct_template']         = 'πατρόν';
$BL['be_admin_struct_alias']            = 'alias this category';
$BL['be_admin_struct_visible']          = 'ορατό';
$BL['be_admin_struct_button']           = 'αποστολή στοιχείων κατηγορίας';
$BL['be_admin_struct_close']            = 'κλείσιμο';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'κατηγορίες αρχείων';
$BL['be_admin_fcat_err']                = 'τίτλος κατηγορίας άδειος!';
$BL['be_admin_fcat_name']               = 'τίτλος κατηγορίας';
$BL['be_admin_fcat_needed']             = 'απαραίτητο';
$BL['be_admin_fcat_button1']            = 'ανανέωση';
$BL['be_admin_fcat_button2']            = 'δημιουργία';
$BL['be_admin_fcat_delmsg']             = 'Do you really want\nto delete file key?';
$BL['be_admin_fcat_fcat']               = 'κατηγορία αρχείου';
$BL['be_admin_fcat_err1']               = 'file key name is empty!';
$BL['be_admin_fcat_fkeyname']           = 'file key name';
$BL['be_admin_fcat_exit']               = 'exit editing';
$BL['be_admin_fcat_addkey']             = 'προσθήκη νέου κλειδιού';
$BL['be_admin_fcat_editcat']            = 'επεξεργασία τίτλου κατηγορίας';
$BL['be_admin_fcat_delcatmsg']          = 'Do you really want\nto delete file category?';
$BL['be_admin_fcat_delcat']             = 'διαγραφή κατηγορίας αρχείου';
$BL['be_admin_fcat_delkey']             = 'delete file key name';
$BL['be_admin_fcat_editkey']            = 'επεξεργασία κλειδιού';
$BL['be_admin_fcat_addcat']             = 'δημιουργία νέας κατηγορίας αρχείου';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend setup: page layout';
$BL['be_admin_page_align']              = 'στοίχιση σελίδας';
$BL['be_admin_page_align_left']         = 'κανονική στοίχιση (αριστερά) απο όλο το περιεχόμενο της σελίδας';
$BL['be_admin_page_align_center']       = 'στοίχιση όλου τού περιεχόμενου στο κέντρο';
$BL['be_admin_page_align_right']        = 'align right of the whole page content';
$BL['be_admin_page_margin']             = 'margin';
$BL['be_admin_page_top']                = 'πάνω';
$BL['be_admin_page_bottom']             = 'κάτω';
$BL['be_admin_page_left']               = 'αριστερά';
$BL['be_admin_page_right']              = 'δεξιά';
$BL['be_admin_page_bg']                 = 'φόντο';
$BL['be_admin_page_color']              = 'χρώμα';
$BL['be_admin_page_height']             = 'ύψος';
$BL['be_admin_page_width']              = 'πλάτος';
$BL['be_admin_page_main']               = 'main';
$BL['be_admin_page_leftspace']          = 'αριστερό κενό';
$BL['be_admin_page_rightspace']         = 'δεξή κενό';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'εικόνα';
$BL['be_admin_page_text']               = 'κείμενο';
$BL['be_admin_page_link']               = 'υπερσύνδεση';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'visited';
$BL['be_admin_page_pagetitle']          = 'page&nbsp;title';
$BL['be_admin_page_addtotitle']         = 'add&nbsp;to&nbsp;title';
$BL['be_admin_page_category']           = 'κατηγορία';
$BL['be_admin_page_articlename']        = 'article&nbsp;name';
$BL['be_admin_page_blocks']             = 'blocks';
$BL['be_admin_page_allblocks']          = 'all blocks';
$BL['be_admin_page_col1']               = '3 column layout';
$BL['be_admin_page_col2']               = '2 column layout (main column right, nav column left)';
$BL['be_admin_page_col3']               = '2 column layout (main column left, nav column right)';
$BL['be_admin_page_col4']               = '1 column layout';
$BL['be_admin_page_header']             = 'επικεφαλίδα';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'top&nbsp;space';
$BL['be_admin_page_bottomspace']        = 'bottom&nbsp;space';
$BL['be_admin_page_button']             = 'save page layout';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'αποθήκευση δεδομένων css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend setup: templates';
$BL['be_admin_tmpl_default']            = 'εξ ορισμού';
$BL['be_admin_tmpl_add']                = 'add&nbsp;template';
$BL['be_admin_tmpl_edit']               = 'edit template';
$BL['be_admin_tmpl_new']                = 'δημιουργία νέου';
$BL['be_admin_tmpl_css']                = 'css file';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'σφάλμα';
$BL['be_admin_tmpl_button']             = 'save template';
$BL['be_admin_tmpl_name']               = 'όνομα';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'site structure and article list';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'ο τίτλος γι\'αυτό το άρθρο είναι κενός';
$BL['be_article_err2']                  = 'η δοθείσα ημερομηνία έναρξης ήταν λάθος - ρυθμίστε τη τώρα';
$BL['be_article_err3']                  = 'η δοθείσα ημερομηνία λήξης ήταν λάθος - ρυθμίστε τη τώρα';
$BL['be_article_title1']                = 'article basis information';
$BL['be_article_cat']                   = 'κατηγορία';
$BL['be_article_atitle']                = 'article title';
$BL['be_article_asubtitle']             = 'υπότιτλος';
$BL['be_article_abegin']                = 'ξεκινάει';
$BL['be_article_aend']                  = 'τελειώνει';
$BL['be_article_aredirect']             = 'redirect to';
$BL['be_article_akeywords']             = 'λέξεις κλειδιά';
$BL['be_article_asummary']              = 'σύνοψη';
$BL['be_article_abutton']               = 'δημιουργία νέου άρθρου';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'end date given was wrong - set to now + 1 week';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'edit article basis information';
$BL['be_article_eslastedit']            = 'τελευταία επεξεργασία';
$BL['be_article_esnoupdate']            = 'φόρμα δέν ανανεώθηκε';
$BL['be_article_esbutton']              = 'update article data';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'περιεχόμενο άρθρου';
$BL['be_article_cnt_type']              = 'content type';
$BL['be_article_cnt_space']             = 'κενό';
$BL['be_article_cnt_before']            = 'πριν';
$BL['be_article_cnt_after']             = 'μετά';
$BL['be_article_cnt_top']               = 'πάνω';
$BL['be_article_cnt_toplink']           = 'display top link';
$BL['be_article_cnt_ctitle']            = 'τίτλος περιεχομένου';
$BL['be_article_cnt_back']              = 'complete article info';
$BL['be_article_cnt_button1']           = 'Ανανέωση';
$BL['be_article_cnt_button2']           = 'Δημιουργία';
$BL['be_article_cnt_button3']           = 'Αποθήκευση &amp; κλείσιμο';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'πληροφορίες άρθρου';
$BL['be_article_cnt_ledit']             = 'επεξεργασία άρθρου';
$BL['be_article_cnt_lvisible']          = 'εναλλαγή ορατό/αόρατο';
$BL['be_article_cnt_ldel']              = 'να διαγραφεί αυτο το άρθρο';
$BL['be_article_cnt_ldeljs']            = 'Διαγραφή άρθρου?';
$BL['be_article_cnt_redirect']          = 'προώθηση';
$BL['be_article_cnt_edited']            = 'επεξεργασμένο απο';
$BL['be_article_cnt_start']             = 'start date';
$BL['be_article_cnt_end']               = 'end date';
$BL['be_article_cnt_add']               = 'προσθήκη';
$BL['be_article_cnt_addtitle']          = 'add new content part';
$BL['be_article_cnt_up']                = 'move content up';
$BL['be_article_cnt_down']              = 'move content down';
$BL['be_article_cnt_edit']              = 'edit content part';
$BL['be_article_cnt_delpart']           = 'delete this article content part';
$BL['be_article_cnt_delpartjs']         = 'Delete content part?';
$BL['be_article_cnt_center']            = 'article center';

// content forms
$BL['be_cnt_plaintext']                 = 'απλό κείμενο';
$BL['be_cnt_htmltext']                  = 'κείμενο html';
$BL['be_cnt_image']                     = 'εικόνα';
$BL['be_cnt_position']                  = 'τοποθεσία';
$BL['be_cnt_pos0']                      = 'Above, αριστερά';
$BL['be_cnt_pos1']                      = 'Above, κέντρο';
$BL['be_cnt_pos2']                      = 'Above, δεξιά';
$BL['be_cnt_pos3']                      = 'Κάτω, αριστερά';
$BL['be_cnt_pos4']                      = 'Κάτω, κέντρο';
$BL['be_cnt_pos5']                      = 'Κάτω, δεξιά';
$BL['be_cnt_pos6']                      = 'Στο κείμενο, αριστερά';
$BL['be_cnt_pos7']                      = 'Στο κείμενο, δεξιά';
$BL['be_cnt_pos0i']                     = 'align the image above and left of the text block';
$BL['be_cnt_pos1i']                     = 'align the image above and centered of the text block';
$BL['be_cnt_pos2i']                     = 'align the image above and right of the text block';
$BL['be_cnt_pos3i']                     = 'align the image below and left of the text block';
$BL['be_cnt_pos4i']                     = 'align the image below and centered of the text block';
$BL['be_cnt_pos5i']                     = 'align the image above and right of the text block';
$BL['be_cnt_pos6i']                     = 'align the image left within the text block';
$BL['be_cnt_pos7i']                     = 'align the image right within the text block';
$BL['be_cnt_maxw']                      = 'μέγιστο.&nbsp;πλάτος';
$BL['be_cnt_maxh']                      = 'μέγιστο.&nbsp;ύψος';
$BL['be_cnt_enlarge']                   = 'πατήστε&nbsp;μεγέθυνση';
$BL['be_cnt_caption']                   = 'τίτλος';
$BL['be_cnt_subject']                   = 'θέμα';
$BL['be_cnt_recipient']                 = 'παραλήπτης';
$BL['be_cnt_buttontext']                = 'κείμενο κουμπιού';
$BL['be_cnt_sendas']                    = 'αποστολή σαν';
$BL['be_cnt_text']                      = 'κείμενο';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'πεδία φόρμας';
$BL['be_cnt_code']                      = 'code';
$BL['be_cnt_infotext']                  = 'info&nbsp;text';
$BL['be_cnt_subscription']              = 'συνδρομή';
$BL['be_cnt_labelemail']                = 'label&nbsp;email';
$BL['be_cnt_tablealign']                = 'table&nbsp;align';
$BL['be_cnt_labelname']                 = 'label&nbsp;name';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'all&nbsp;subscr.';
$BL['be_cnt_default']                   = 'προεπιλεγμένο';
$BL['be_cnt_left']                      = 'αριστερά';
$BL['be_cnt_center']                    = 'κέντρο';
$BL['be_cnt_right']                     = 'δεξιά';
$BL['be_cnt_buttontext']                = 'button&nbsp;text';
$BL['be_cnt_successtext']               = 'success&nbsp;text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'change.email';
$BL['be_cnt_openimagebrowser']          = 'open image browser';
$BL['be_cnt_openfilebrowser']           = 'open file browser';
$BL['be_cnt_sortup']                    = 'μετακίνηση πάνω';
$BL['be_cnt_sortdown']                  = 'μετακίνηση κάτω';
$BL['be_cnt_delimage']                  = 'διαγραφή επιλεγμένης εικόνας';
$BL['be_cnt_delfile']                   = 'διαγραφή επιλεγμένου αρχείου';
$BL['be_cnt_delmedia']                  = 'διαγραφή επιλεγμένου πολυμέσου';
$BL['be_cnt_column']                    = 'στήλη';
$BL['be_cnt_imagespace']                = 'image&nbsp;space';
$BL['be_cnt_directlink']                = 'direct link';
$BL['be_cnt_target']                    = 'στόχος';
$BL['be_cnt_target1']                   = 'σε νέο παράθυρο';
$BL['be_cnt_target2']                   = 'in parent frame of the window';
$BL['be_cnt_target3']                   = 'in same window without frames';
$BL['be_cnt_target4']                   = 'in the same frame or window';
$BL['be_cnt_bullet']                    = 'list (table)';
$BL['be_cnt_ullist']                    = 'list';
$BL['be_cnt_ullist_desc']               = '~ = 1st Level, &nbsp; ~~ = 2nd level, &nbsp; etc.';
$BL['be_cnt_linklist']                  = 'λίστα υπερσυνδέσεων';
$BL['be_cnt_plainhtml']                 = 'απλή html';
$BL['be_cnt_files']                     = 'αρχεία';
$BL['be_cnt_description']               = 'περιγραφή';
$BL['be_cnt_linkarticle']               = 'link article';
$BL['be_cnt_articles']                  = 'άρθρα';
$BL['be_cnt_movearticleto']             = 'move selected article to link article list';
$BL['be_cnt_removearticleto']           = 'remove selected article from link article list';
$BL['be_cnt_mediatype']                 = 'τύπος πολυμέσων';
$BL['be_cnt_control']                   = 'control';
$BL['be_cnt_showcontrol']               = 'show control bar';
$BL['be_cnt_autoplay']                  = 'αυτόματη αναπαραγωγή';
$BL['be_cnt_source']                    = 'πηγή';
$BL['be_cnt_internal']                  = 'εσωτερικά';
$BL['be_cnt_openmediabrowser']          = 'open media browser';
$BL['be_cnt_external']                  = 'εξωτερικά';
$BL['be_cnt_mediapos0']                 = 'αριστερά (προεπιλογή)';
$BL['be_cnt_mediapos1']                 = 'κέντρο';
$BL['be_cnt_mediapos2']                 = 'δεξιά';
$BL['be_cnt_mediapos3']                 = 'block, αριστερά';
$BL['be_cnt_mediapos4']                 = 'block, δεξιά';
$BL['be_cnt_mediapos0i']                = 'align media above and left of the text block';
$BL['be_cnt_mediapos1i']                = 'align media above and centered of the text block';
$BL['be_cnt_mediapos2i']                = 'align media above and right of the text block';
$BL['be_cnt_mediapos3i']                = 'align media left within the text block';
$BL['be_cnt_mediapos4i']                = 'align media right within the text block';
$BL['be_cnt_setsize']                   = 'καθορίστε το μέγεθος';
$BL['be_cnt_set1']                      = 'set media size to 160x120px';
$BL['be_cnt_set2']                      = 'set media size to 240x180px';
$BL['be_cnt_set3']                      = 'set media size to 320x240px';
$BL['be_cnt_set4']                      = 'set media size to 480x360px';
$BL['be_cnt_set5']                      = 'clear media width and height';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'create new pagelayout';
$BL['be_admin_page_name']               = 'layout name';
$BL['be_admin_page_edit']               = 'edit pagelayout';
$BL['be_admin_page_render']             = 'rendering';
$BL['be_admin_page_table']              = 'table';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'custom';
$BL['be_admin_page_custominfo']         = 'from template main block';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'No page layout available!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'αναζήτηση';
$BL['be_cnt_results']                   = 'αποτελέσματα';
$BL['be_cnt_results_per_page']          = 'per&nbsp;page (if empty show max. 25)';
$BL['be_cnt_opennewwin']                = 'άνοιγμα νέου παραθύρου';
$BL['be_cnt_searchlabeltext']           = 'these are predefined texts and values for the search form and search result page and texts are shown when more than the given count of results per page should be shown.';
$BL['be_cnt_input']                     = 'input';
$BL['be_cnt_style']                     = 'στυλ';
$BL['be_cnt_result']                    = 'αποτέλεσμα';
$BL['be_cnt_next']                      = 'επόμενο';
$BL['be_cnt_previous']                  = 'προηγούμενο';
$BL['be_cnt_align']                     = 'ευθυγράμμιση';
$BL['be_cnt_searchformtext']            = 'τα παρακάτω κείμενα παρουσιάζονται όταν η φόρμα αναζήτησης είναι ανοιχτή ή τα αποτελέσματα για μία δοσμένη αναζήτηση (δεν) είναι διαθέσιμα.';
$BL['be_cnt_intro']                     = 'εισαγωγή';
$BL['be_cnt_noresult']                  = 'κανένα αποτέλεσμα';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'απενεργοποίηση';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'ιδιοκτήτης άρθρου';
$BL['be_article_adminuser']             = 'admin user';
$BL['be_article_username']              = 'συγγραφέας';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'ορατό μόνο στους συνδεδεμένους χρήστες';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'backend default text';
$BL['be_ctype_ecard']                   = 'ηλεκτρονική κάρτα';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'title/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'εικόνα ηλεκτρονικής κάρτας';
$BL['be_cnt_ecard_title']               = 'τίτλος ηλεκτρονικής κάρτας';
$BL['be_cnt_alignment']                 = 'ευθυγράμμιση';
$BL['be_cnt_ecardform']                 = 'form tmpl';
$BL['be_cnt_ecardform_err']             = 'Όλα τα πεδία που επισημαίνονται με * είναι υποχρεωτικά';
$BL['be_cnt_ecardform_sender']          = 'Αποστολέας';
$BL['be_cnt_ecardform_recipient']       = 'Παραλήπτης';
$BL['be_cnt_ecardform_name']            = 'Όνομα';
$BL['be_cnt_ecardform_msgtext']         = 'Το μήνυμά σας στον παραλήπτη';
$BL['be_cnt_ecardform_button']          = 'στείλτε την ηλεκτρονική κάρτα';
$BL['be_cnt_ecardsend']                 = 'κάρτα εστάλη';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend default startup text';
$BL['be_admin_startup_text']            = 'startup text';
$BL['be_admin_startup_button']          = 'save startup text';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'guestbook/comment';
$BL['be_cnt_guestbook_listing']         = 'listing';
$BL['be_cnt_guestbook_listing_all']     = 'list&nbsp;all&nbsp;entries';
$BL['be_cnt_guestbook_list']            = 'list';
$BL['be_cnt_guestbook_perpage']         = 'per&nbsp;page';
$BL['be_cnt_guestbook_form']            = 'form';
$BL['be_cnt_guestbook_signed']          = 'signed';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'πριν';
$BL['be_cnt_guestbook_after']           = 'μετά';
$BL['be_cnt_guestbook_entry']           = 'εγγραφή';
$BL['be_cnt_guestbook_edit']            = 'επεξεργασία';
$BL['be_cnt_ecardform_selector']        = 'selector';
$BL['be_cnt_ecardform_radiobutton']     = 'radio button';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript functionality';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'top article count';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'ενημερωτικό δελτίο';
$BL['be_newsletter_addnl']              = 'πρόσθεση ενημερωτικού δελτίου';
$BL['be_newsletter_titleeditnl']        = 'επεξεργασία ενημερωτικού δελτίου';
$BL['be_newsletter_newnl']              = 'δημιουργία νέου';
$BL['be_newsletter_button_savenl']      = 'αποθήκευση ενημερωτικού δελτίου';
$BL['be_newsletter_fromname']           = 'από όνομα';
$BL['be_newsletter_fromemail']          = 'από διεύθυνση ηλεκτρονικού ταχυδρομείου';
$BL['be_newsletter_replyto']            = 'απάντηση σε διεύθυνση ηλεκτρονικού ταχυδρομείου';
$BL['be_newsletter_changed']            = 'τελευταία αλλαγή';
$BL['be_newsletter_placeholder']        = 'placeholder';
$BL['be_newsletter_htmlpart']           = 'HTML περιεχόμενο ενημερωτικού δελτίου';
$BL['be_newsletter_textpart']           = 'TEXT περιεχόμενο ενημερωτικού δελτίου';
$BL['be_newsletter_allsubscriptions']   = 'όλες οι συνδρομές';
$BL['be_newsletter_verifypage']         = 'επιβεβαίωση συνδέσμου';
$BL['be_newsletter_open']               = 'HTML and TEXT input';
$BL['be_newsletter_open1']              = '(Πατήστε στην εικόνα για να ανοίξει)';
$BL['be_newsletter_sendnow']            = 'Αποστολή ενημερωτικού δελτίου';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Attention!</strong> Sending a newsletter to multiple recipients is very hazardous. Recipients should have been verified otherwise you will send potential spam. Think twice before you send the newsletter. Check your newsletter by sending a test.';
$BL['be_newsletter_attention1']         = 'Εάν έχετε κάνει αλλαγές στο παραπάνω ενημερωτικό δελτίο παρακαλείσθε να το σώσετε πρώτα αλλιώς αυτές οι αλλαγές δε θα χρησιμοποιηθούν.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'Αποστολή ενημερωτικού δελτίου';
$BL['be_newsletter_sendprocess']        = 'Αποστολή διαδικασίας';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Attention!</strong> Please do not stop the send process. Otherwise it is possible that you will send the newsletter more than twice to a recipient. When sending fails all non achieved recipient are stored in a session array and will be used if you send again immediately.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">the test email address <strong>###TEST###</strong> is NOT valid!<br />&nbsp;<br />Try again please!';
$BL['be_newsletter_to']                 = 'Παραλήπτες';
$BL['be_newsletter_ready']              = 'Αποστολή ενημερωτικού δελτίου: ΕΠΙΤΥΧΗΣ';
$BL['be_newsletter_readyfailed']        = 'Απέτυχε η αποστολή ενημερωτικού δελτίου προς ';
$BL['be_subnav_msg_subscribers']        = 'συνδρομητές ενημερωτικού δελτίου';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'sitemap';
$BL['be_cnt_sitemap_catimage']          = 'level icon';
$BL['be_cnt_sitemap_articleimage']      = 'article icon';
$BL['be_cnt_sitemap_display']           = 'display';
$BL['be_cnt_sitemap_structuronly']      = 'structure levels only';
$BL['be_cnt_sitemap_structurarticle']   = 'structure levels + articles';
$BL['be_cnt_sitemap_catclass']          = 'level class';
$BL['be_cnt_sitemap_articleclass']      = 'article class';
$BL['be_cnt_sitemap_count']             = 'counter';
$BL['be_cnt_sitemap_classcount']        = 'add to class name';
$BL['be_cnt_sitemap_noclasscount']      = 'don\'t add to class name';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'bid';
$BL['be_cnt_bid_bidtext']               = 'bid text';
$BL['be_cnt_bid_sendtext']              = 'sent text';
$BL['be_cnt_bid_verifiedtext']          = 'verified text';
$BL['be_cnt_bid_errortext']             = 'bid deleted';
$BL['be_cnt_bid_verifyemail']           = 'verify email';
$BL['be_cnt_bid_startbid']              = 'start bid';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'increase&nbsp;by';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'ext. content';
$BL['be_cnt_pages_select']              = 'select file';
$BL['be_cnt_pages_fromfile']            = 'file from structure';
$BL['be_cnt_pages_manually']            = 'custom path/file or URL';
$BL['be_cnt_pages_cust']                = 'file/URL';
$BL['be_cnt_pages_from']                = 'source';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover images';
$BL['be_cnt_reference_basis']           = 'ευθυγράμμιση';
$BL['be_cnt_reference_horizontal']      = 'οριζόντια';
$BL['be_cnt_reference_vertical']        = 'κάθετα';
$BL['be_cnt_reference_aligntext']       = 'small reference images';
$BL['be_cnt_reference_largetext']       = 'large reference image';
$BL['be_cnt_reference_zoom']            = 'εστίαση';
$BL['be_cnt_reference_middle']          = 'μέση';
$BL['be_cnt_reference_border']          = 'όριο';
$BL['be_cnt_reference_block']           = 'block w x h';

// added: 31-05-2004
$BL['be_article_rendering']             = 'rendring';
$BL['be_article_nosummary']             = 'don\'t display summary in full article';
$BL['be_article_forlist']               = 'article listing';
$BL['be_article_forfull']               = 'display full article';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ΠΡΟΣΟΧΗ!</strong> Ο &quot;SETUP&quot; κατάλογος υπάρχει ακόμα! Διαγράψτε αυτό το φάκελο - πιθανά προβλήματα ασφαλείας.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'απαγορευμένες λέξεις';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'set cookie';
$BL['be_cnt_guestbook_allowed']         = 'επιτρέπεται ξανά μετά από';
$BL['be_cnt_guestbook_seconds']         = 'δευτερόλεπτα';
$BL['be_alias_ID']                      = 'κωδικός ψευδωνύμου';
$BL['be_ftrash_delall']                 = "Είστε σίγουροι οτι θέλετε να διαγράψετε \nΟΛΑ ΤΑ ΑΡΧΕΙΑ στον κάδο ανακύκλωσης?";
$BL['be_ftrash_delallfiles']            = 'διαγραφή όλων των αρχείων στον κάδο ανακύκλωσης';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'CSV subscribers import';
$BL['be_newsletter_importtitle']        = 'Εισαγωγή Συνδρομητών Ενημερωτικού Δελτίου';
$BL['be_newsletter_entriesfound']       = 'εγγραφές&nbsp;βρέθηκαν';
$BL['be_newsletter_foundinfile']        = 'in file';
$BL['be_newsletter_addresses']          = 'διευθύνσεις';
$BL['be_newsletter_csverror']           = 'Imported CSV file seems to be incorrect! Check delimeter!';
$BL['be_newsletter_importall']          = 'import all entries';
$BL['be_newsletter_addressesadded']     = 'διευθύνσεις προστέθηκαν.';
$BL['be_newsletter_newimport']          = 'νέα εισαγωγή';
$BL['be_newsletter_importerror']        = 'Παρακαλώ ελέγξτε το CSV αρχείο σας - δεν μπορεί να προστεθεί καμία διεύθυνση!';
$BL['be_newsletter_shouldbe1']          = 'Το CSV αρχείο σας πρέπει να είναι διαμορφωμένο έτσι';
$BL['be_newsletter_shouldbe2']          = 'όμως μπορείτε να διαλέξετε μία εξατομικευμένη delimeter';
$BL['be_newsletter_sample']             = 'δείγμα';
$BL['be_newsletter_selectCSV']          = 'δείγμα αρχείου CSV';
$BL['be_newsletter_delimeter']          = 'delimeter';
$BL['be_newsletter_importCSV']          = 'εισαγωγή αρχείου CSV';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'ordering of assigned articles';
$BL['be_admin_struct_orderdate']        = 'ημερομηνία δημιουργίας';
$BL['be_admin_struct_orderchangedate']  = 'αλλαγή ημερομηνίας';
$BL['be_admin_struct_orderstartdate']   = 'ημερομηνία έναρξης';
$BL['be_admin_struct_orderdesc']        = 'φθίνουσα';
$BL['be_admin_struct_orderasc']         = 'αύξουσα';
$BL['be_admin_struct_ordermanual']      = 'χειροκίνητο (βελάκι πάνω/κάτω)';
$BL['be_cnt_sitemap_startid']           = 'έναρξη σε';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'map';
$BL['be_save_btn']                      = 'Αποθήκευση';
$BL['be_cmap_location_error_notitle']   = 'συμπληρώστε έναν τίτλο για αυτή την τοποθεσία.';
$BL['be_cnt_map_add']                   = 'πρόσθεση τοποθεσίας';
$BL['be_cnt_map_edit']                  = 'επεξεργασία τοποθεσίας';
$BL['be_cnt_map_title']                 = 'τίτλος τοποθεσίας';
$BL['be_cnt_map_info']                  = 'εγγραφή/πληροφορίες';
$BL['be_cnt_map_list']                  = 'λίστα τοποθεσιών';
$BL['be_btn_delete']                    = 'Είστε σίγουροι οτι θέλετε να \nδιαγράψετε αυτή την τοποθεσία?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP μεταβλητές';
$BL['be_cnt_vars']                      = 'μεταβλητές';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'αντιγραφή άρθρου';
$BL['be_func_struct_nocopy']            = 'απενεργοποίηση αντιγραφής άρθρου';
$BL['be_func_struct_copy_level']        = 'copy structure level';
$BL['be_func_struct_no_copy']           = "It's not possible to copy the root level!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'λεπτό';
$BL['be_date_minutes']                  = 'λεπτά';
$BL['be_date_hour']                     = 'ώρα';
$BL['be_date_hours']                    = 'ώρες';
$BL['be_date_day']                      = 'μέρα';
$BL['be_date_days']                     = 'μέρες';
$BL['be_date_week']                     = 'εβδομάδα';
$BL['be_date_weeks']                    = 'εβδομάδες';
$BL['be_date_month']                    = 'μήνας';
$BL['be_date_months']                   = 'μήνες';
$BL['be_off']                           = 'Κλείσιμο';
$BL['be_on']                            = 'Άνοιγμα';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timeout';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'users &amp; groups';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'forums list';
$BL['be_forum_title']                   = 'forum title';
$BL['be_forum_permission']              = 'permissions';
$BL['be_forum_add']                     = 'add forum';
$BL['be_forum_titleedit']               = 'edit forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'custom';
$BL['be_show_content']                  = 'display';
$BL['be_main_content']                  = 'κύρια στήλη';
$BL['be_admin_template_jswarning']      = 'Προειδοποίηση!!! \nCustom blocks may change! \n\nIf you cancel \nreset your pagelayout setting! \n\nChange template?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS url';
$BL['be_cnt_rssfeed_item']              = 'items';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'hide 1st item';

$BL['be_ctype_simpleform']              = 'email contact form';

$BL['be_cnt_onsuccess']                 = 'on success';
$BL['be_cnt_onerror']                   = 'on error';
$BL['be_cnt_onsuccess_redirect']        = 'redirect on success';
$BL['be_cnt_onerror_redirect']          = 'redirect on error';

$BL['be_cnt_form_class']                = 'form class';
$BL['be_cnt_label_wrap']                = 'label wrap';
$BL['be_cnt_error_class']               = 'error class';
$BL['be_cnt_req_mark']                  = 'required mark';
$BL['be_cnt_mark_as_req']               = 'mark as required';
$BL['be_cnt_mark_as_del']               = 'mark item for deletion';


$BL['be_cnt_type']                      = 'τύπος';
$BL['be_cnt_label']                     = 'ταμπέλα';
$BL['be_cnt_needed']                    = 'απαιτούνται';
$BL['be_cnt_delete']                    = 'διαγραφή';
$BL['be_cnt_value']                 = 'τιμή';
$BL['be_cnt_error_text']                = 'σφάλμα κείμενο';
$BL['be_cnt_css_style']                 = 'CSS στυλ';
$BL['be_cnt_send_copy_to']              = 'CC σε';

$BL['be_cnt_field']                     = array("text"=>'text (single-line)', "email"=>'email', "textarea"=>'text (multi-line)',
                                                "hidden"=>'hidden', "password"=>'password', "select"=>'select menu',
                                                "list"=>'list menu', "checkbox"=>'checkbox', "radio"=>'radio button',
                                                "upload"=>'file', "submit"=>'send button', "reset"=>'reset button',
                                                "break"=>'break', "breaktext"=>'break text', "special"=>'text (spezial)');

$BL['be_cnt_access']                    = 'πρόσβαση';
$BL['be_cnt_activated']                 = 'ενεργοποιημένο';
$BL['be_cnt_available']                 = 'διαθέσιμο';
$BL['be_cnt_guests']                    = 'επισκέπτες';
$BL['be_cnt_admin']                     = 'διαχεριστής';
$BL['be_cnt_write']                     = 'γράψε';
$BL['be_cnt_read']                      = 'διάβασε';

$BL['be_cnt_no_wysiwyg_editor']         = 'απενεργοποίηση WYSIWYG editor';
$BL['be_cnt_cache_update']              = 'μηδενισμός cache';
$BL['be_cnt_cache_delete']              = 'διαγραφή cache';
$BL['be_cnt_cache_delete_msg']          = 'Είστε σίγουροι οτι θέλετε να διαγράψετε το cache?  \nΑυτό μπορεί να επηρρεάσει και την εύρεση.  \n';

$BL['be_admin_usr_issection']           = 'login section';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend';
$BL['be_admin_usr_ifsection2']          = 'frontend and backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'edit this article content part';
$BL['be_func_content_paste0']            = 'paste in article';
$BL['be_func_content_paste']             = 'paste later article content part';
$BL['be_func_content_cut']               = 'cut this article content part';
$BL['be_func_content_no_cut']            = "It's not possible to cut the article content part!";
$BL['be_func_content_copy']              = 'copy this article content part part';
$BL['be_func_content_no_copy']           = "It's not possible to copy the article content part!";
$BL['be_func_content_paste_cancel']      = 'cancel article content part change';

$BL['be_cnt_move_deleted'] = 'αφαίρεση διεγραμμένων αρχείων';
$BL['be_cnt_move_deleted_msg'] = 'Είστε σίγουροι οτι θέλετε να μετακινήσετε όλα τα αρχεία  \nμαρκαρισμένα ως διεγραμμένα στον ειδικό φάκελο διαγραφής; \n';

$BL['be_admin_struct_permit'] = 'authorized to access (let empty for everybody)';
$BL['be_admin_struct_adduser_all']   = 'take over all users';
$BL['be_admin_struct_adduser_this']  = 'take over selected user';
$BL['be_admin_struct_remove_all']    = 'αφαίρεση όλων των χρηστών';
$BL['be_admin_struct_remove_this']   = 'αφαίρεση επιλεγμένου χρήστη';


$BL['be_ctype_alias'] = 'contentpart alias';
$BL['be_cnt_setting'] = 'take over';
$BL['be_cnt_spaces'] = 'spaces of contentpart alias';
$BL['be_cnt_toplink'] = 'top link setting of contentpart alias';
$BL['be_cnt_block'] = 'display (block) setting of contentpart alias';
$BL['be_cnt_title'] = 'titles of contentpart alias';

