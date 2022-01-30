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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

/**
 * phpwcmsNews class
 */
class phpwcmsNews {

    public $news                = array();
    public $news_total          = 0;
    public $where               = array('status' => 'cnt_status != 9');
    public $order_by            = array('cnt_startdate DESC');
    public $group_by            = '';
    public $sql                 = '';
    public $limit               = 0;
    public $start_at            = 0;
    public $select              = '*';
    public $base_url            = '';
    public $base_url_decoded    = '';
    public $data                = array();
    public $sort_options        = array(
        'prio_asc'      => 'cnt_prio ASC',
        'prio_desc'     => 'cnt_prio DESC',
        'name_asc'      => 'cnt_name ASC',
        'name_desc'     => 'cnt_name DESC',
        'start_asc'     => 'cnt_startdate ASC',
        'start_desc'    => 'cnt_startdate DESC',
        'sort_asc'      => 'cnt_sortdate ASC',
        'sort_desc'     => 'cnt_sortdate DESC',
        'end_asc'       => 'cnt_enddate ASC',
        'end_desc'      => 'cnt_enddate DESC'
    );
    public $filter_sort     = '';
    public $csrf_token          = '';

    function __construct() {

        global $BL;
        global $phpwcms;

        $this->BL               = &$BL;
        $this->phpwcms          = &$phpwcms;
        $this->csrf_token       = get_token_get_string();
        $this->base_url         = PHPWCMS_URL.'phpwcms.php?'.$this->csrf_token.'&amp;do=articles&amp;p=3';
        $this->base_url_decoded = PHPWCMS_URL.'phpwcms.php?'.$this->csrf_token.'&do=articles&p=3';

    }

    public function formAction() {

        return $this->base_url.'&amp;cntid='.$this->data['cnt_id'].'&amp;action=edit';

    }

    private function _where() {

        $sql = '';

        if(is_array($this->where) && count($this->where)) {

            foreach($this->where as $value) {

                $value = trim($value);

                if(strtoupper(substr($value, 0, 2)) == 'OR') {

                    $sql .= ' '.$value;

                } elseif(strtoupper(substr($value, 0, 3)) == 'AND') {

                    $sql .= ' '.$value;

                } else {

                    $sql .= ' AND '.$value;
                }

            }

        } elseif(is_string($this->where) ) {

            if(strtoupper(substr($this->where, 0, 2)) == 'OR') {

                $sql .= ' '.$this->where;

            } elseif(strtoupper(substr($this->where, 0, 3)) == 'AND') {

                $sql .= ' '.$this->where;

            } else {

                $sql = ' AND ' . $this->where;
            }

        }

        return $sql;

    }

    /**
     * set array with news
     */
    public function getNews() {

        $this->news = array();

        $sql  = 'SELECT '.$this->select.' FROM '.DB_PREPEND.'phpwcms_content WHERE ';
        $sql .= "cnt_module='news'";

        $sql .= $this->_where();

        if(is_array($this->order_by) && count($this->order_by)) {

            $sql .= ' ORDER BY '.implode(',', $this->order_by);

        } elseif(is_string($this->order_by) && !empty($this->order_by)) {

            $sql .= ' ORDER BY '.$this->order_by;

        }

        if($this->limit > 0) {

            $this->start_at = intval($this->start_at);
            $this->limit    = intval($this->limit);

            $sql .= ' LIMIT ' . $this->start_at . ',' . $this->limit;

        }

        $this->sql = $sql;

        $news = _dbQuery($sql);

        $this->news = isset($news[0]) ? $news : array();

        $this->setQueryDefaults();

    }

    public function countAll() {

        $sql  = 'SELECT COUNT(cnt_id) FROM '.DB_PREPEND.'phpwcms_content WHERE ';
        $sql .= "cnt_module = 'news'" . $this->_where();

        $this->news_total = _dbCount($sql);

        return $this->news_total;

    }

    public function getPagination() {

        initMootools();

        $paginate = '<input type="hidden" name="page" id="filterPage" value="' . $this->filter_page . '" />';

        if( $this->limit > 0 && $this->news_total > $this->limit ) {

            $current_page   = $this->filter_page + 1;
            $max_page       = ceil($this->news_total / $this->limit);

            if($current_page > $max_page) {
                $current_page = $max_page;
                $this->filter_page  = $max_page - 1;
                $this->start_at     = $this->filter_page * $this->limit;
            }

            $prev_page      = $this->filter_page - 1;
            if($prev_page < 0) {
                $prev_page = 0;
            }
            $next_page      = $current_page;
            if($next_page >= $max_page) {
                $next_page = $max_page - 1;
            }

            $paginate .= '<img src="img/famfamfam/action_back.gif" alt="" border="0"';
            if($current_page == 1) {
                $paginate .= ' class="inactive"';
            } else {
                $paginate .= ' onclick="$(\'filterPage\').value='.$prev_page.';$(\'paginate\').submit();"';
            }
            $paginate .= ' /></td><td class="chatlist nowrap">';

            $paginate .= '&nbsp;<b>' . $current_page . '</b>/' . $max_page . '&nbsp;';

            $paginate .= '</td><td class="nowrap"><img src="img/famfamfam/action_forward.gif" alt="" border="0"';
            if($current_page == $max_page) {
                $paginate .= ' class="inactive"';
            } else {
                $paginate .= ' onclick="$(\'filterPage\').value='.$next_page.';$(\'paginate\').submit();"';
            }
            $paginate .= ' />';

        } else {

            $this->start_at     = 0;
            $this->filter_page  = 0;

        }

        return $paginate;
    }

    public function setQueryDefaults() {

        $this->select   = '*';
        $this->order_by = array('cnt_livedate');
        $this->sql      = '';
        $this->limit    = 0;
        $this->start_at = 0;
        $this->group_by = '';
        $this->where    = array('status' => 'cnt_status != 9');

    }

    public function filter() {

        // filter defaults
        // 0 = all with deleted
        // 1 = all active
        // 2 = all inactive

        $status     = isset($_SESSION['PAGE_FILTER']['news']['status']) ? intval($_SESSION['PAGE_FILTER']['news']['status']) : 0;
        $filter     = isset($_SESSION['PAGE_FILTER']['news']['filter']) ? $_SESSION['PAGE_FILTER']['news']['filter'] : '';
        $page       = isset($_SESSION['PAGE_FILTER']['news']['page']) ? intval($_SESSION['PAGE_FILTER']['news']['page']) : 0;
        $sort       = isset($_SESSION['PAGE_FILTER']['news']['sort']) ? $_SESSION['PAGE_FILTER']['news']['sort'] : 'start_desc';
        $lang       = isset($_SESSION['PAGE_FILTER']['news']['lang']) ? $_SESSION['PAGE_FILTER']['news']['lang'] : '';
        $keyword    = isset($_SESSION['PAGE_FILTER']['news']['keyword']) ? $_SESSION['PAGE_FILTER']['news']['keyword'] : '';

        if(isset($_POST['filter'])) {

            $active     = empty($_POST['showactive']) ? false : true;
            $inactive   = empty($_POST['showinactive']) ? false : true;
            $filter     = clean_slweg($_POST['filter']);
            $page       = empty($_POST['page']) ? 0 : intval($_POST['page']);
            $sort       = empty($_POST['sort']) || !isset($this->sort_options[$_POST['sort']]) ? 'start_desc' : $_POST['sort'];
            $lang       = empty($_POST['lang']) ? '' : $_POST['lang'];
            $keyword    = clean_slweg($_POST['keyword']);

            if($active && $inactive) {
                $status = 0;
            } elseif($active) {
                $status = 1;
            } elseif($inactive) {
                $status = 2;
            }

            if(!isset($_SESSION['PAGE_FILTER'])) {
                $_SESSION['PAGE_FILTER'] = array();
            }

            $_SESSION['PAGE_FILTER']['news'] = array(
                'status'    => $status,
                'filter'    => $filter,
                'page'      => $page,
                'sort'      => $sort,
                'lang'      => $lang,
                'keyword'   => $keyword
            );

        }

        switch($status) {

            case 0:
                $this->where['status'] = 'cnt_status IN (1,0)';
                break;

            case 1:
                $this->where['status'] = 'cnt_status = 1';
                break;

            case 2:
                $this->where['status'] = 'cnt_status = 0';
                break;
        }

        if($filter !== '') {
            $this->where['filter'] = "CONCAT(cnt_name,cnt_title,cnt_subtitle,cnt_teasertext,cnt_text) LIKE "._dbEscape($filter, true, '%', '%');
        }

        if($keyword) {

            $this->where['cat']  = '(';
            $this->where['cat'] .=  'SELECT COUNT(pcat.cat_pid) ';
            $this->where['cat'] .=  'FROM '.DB_PREPEND.'phpwcms_categories pcat WHERE ';
            $this->where['cat'] .=  "pcat.cat_type='news' AND pcat.cat_pid=cnt_id AND ";
            $this->where['cat'] .=  'pcat.cat_name=' . _dbEscape($keyword) . ' ';
            $this->where['cat'] .=  'GROUP BY pcat.cat_pid';
            $this->where['cat'] .= ')';

        }

        $this->order_by = array($this->sort_options[$sort]);

        $this->filter_status    = $status;
        $this->filter           = $filter;
        $this->filter_page      = $page;
        $this->filter_sort      = $sort;
        $this->filter_lang      = $lang;
        $this->filter_keyword   = $keyword;

        $this->limit            = setItemsPerPage();
        $this->start_at         = $page * $this->limit;

    }

    public function getNewsCategories() {

        $where = '(SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_content WHERE  cnt_id=cat_pid AND cnt_status != 9 AND cnt_module='news')";
        $result = _dbGet('phpwcms_categories', 'cat_name', "cat_type='news' AND ".$where, 'cat_name', 'cat_name');
        $categories = array();

        if(isset($result[0]['cat_name'])) {
            foreach($result as $item) {
                $categories[] = $item['cat_name'];
            }
        }

        return $categories;
    }

    public function getNewsLanguages() {

        $this->select           = 'DISTINCT cnt_lang';
        $this->order_by         = 'cnt_lang';
        $this->where['lang']    = "cnt_lang != ''";
        $this->getNews();

        return $this->news;

    }

    public function listBackend() {

        $this->select   = '*, ';
        $this->select  .= 'IF(UNIX_TIMESTAMP(cnt_livedate)<=0, cnt_created, UNIX_TIMESTAMP(cnt_livedate)) AS cnt_startdate, ';
        $this->select  .= 'UNIX_TIMESTAMP(cnt_killdate) AS cnt_enddate, ';
        $this->select  .= 'IF(cnt_sort=0, IF(UNIX_TIMESTAMP(cnt_livedate)<=0, cnt_created, UNIX_TIMESTAMP(cnt_livedate)), cnt_sort) AS cnt_sortdate';

        $this->getNews();

        $list = array();

        $x = 0;

        if(count($this->news)) {

            $list[] = '<table cellpadding="0" cellspacing="0" border="0" summary="" class="listing" style="width:100%; min-width:750px">';
            $list[] = '<tr class="header">';

            $sort_class = array(
                'prio'  => 'sort-off',
                'name'  => 'sort-off',
                'start' => 'sort-off',
                'sort'  => 'sort-off',
                'end'   => 'sort-off'
            );

            switch($this->filter_sort) {
                case 'prio_asc':    $sort_class['prio'] = 'sort-asc'; break;
                case 'prio_desc':   $sort_class['prio'] = 'sort-desc'; break;
                case 'name_asc':    $sort_class['name'] = 'sort-asc'; break;
                case 'name_desc':   $sort_class['name'] = 'sort-desc'; break;
                case 'start_asc':   $sort_class['start'] = 'sort-asc'; break;
                case 'start_desc':  $sort_class['start'] = 'sort-desc'; break;
                case 'sort_asc':    $sort_class['sort'] = 'sort-asc'; break;
                case 'sort_desc':   $sort_class['sort'] = 'sort-desc'; break;
                case 'end_asc':     $sort_class['end'] = 'sort-asc'; break;
                case 'end_desc':    $sort_class['end'] = 'sort-desc'; break;
            }

            $list[] = '<th class="column colfirst news"><span class="'.$sort_class['name'].'">'.$this->BL['be_title'].'</span></th>';
            $list[] = '<th class="column"><span class="'.$sort_class['start'].'">'.$this->BL['be_article_cnt_start'].'</span></th>';
            $list[] = '<th class="column"><span class="'.$sort_class['end'].'">'.$this->BL['be_article_cnt_end'].'</span></th>';
            $list[] = '<th class="column"><span class="'.$sort_class['sort'].'">'.$this->BL['be_sort_date'].'</span></th>';
            $list[] = '<th class="column"><span class="'.$sort_class['prio'].'">Prio</span></th>';
            $list[] = '<th class="column collast">&nbsp;</th>';

            $list[] = '</tr>';

            foreach($this->news as $news) {

                $list[] = '<tr class="row'.($x%2?' alt':'').'">';

                $news['live']       = $news['cnt_startdate'];
                $news['live']       = $news['live'] == false || $news['live'] <= 0 ? $this->BL['be_func_struct_empty'] : date($this->BL['be_shortdatetime'], $news['live']);
                $news['kill']       = phpwcms_strtotime($news['cnt_killdate'], $this->BL['be_shortdatetime'], $this->BL['be_func_struct_empty']);
                $news['sort']       = $news['cnt_sortdate'] == false || $news['cnt_sortdate'] <= 0 ? $this->BL['be_func_struct_empty'] : date($this->BL['be_shortdatetime'], $news['cnt_sortdate']);

                $list[] = '<td class="column colfirst news" style="background-image:url(img/famfamfam/lang/'.(!$news['cnt_lang'] ? 'all' : $news['cnt_lang']).'.png)">';
                $list[] = html($news['cnt_name']);
                $list[] = '</td>';
                $list[] = '<td class="column nowrap">'.$news['live'].'</td>';
                $list[] = '<td class="column nowrap">'.$news['kill'].'</td>';
                $list[] = '<td class="column nowrap">'.$news['sort'].'</td>';
                $list[] = '<td class="column">'.$news['cnt_prio'].'</td>';
                $list[] = '<td class="column collast nowrap">

                    <a href="'.$this->base_url.'&amp;cntid='.$news['cnt_id'].'&amp;action=edit">'.
                    '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>'.

                    '<a href="'.$this->base_url.'&amp;cntid='.$news['cnt_id'].'&amp;status='.
                    ($news['cnt_status'] ? '0' : '1').'">'.
                    '<img src="img/button/aktiv_12x13_'.$news['cnt_status'].'.gif" border="0" alt="" /></a>'.

                    '<a href="'.$this->base_url.'&amp;cntid='.$news['cnt_id'].'&amp;status=9'.
                    '" title="'.$this->BL['be_delete_dataset'].' '.html($news['cnt_name']).'" onclick="return confirm(\''.
                    $this->BL['be_delete_dataset'].' \n'.js_singlequote($news['cnt_name']).'\');">'.
                    '<img src="img/button/trash_13x13_1.gif" border="0" alt=""></a>

                </td>';

                $list[] = '</tr>';

                $x++;
            }


            $list[] = '</table>';
        }

        return implode(LF, $list);
    }

    public function getFiles($mode='backend') {

        $data = array();

        if( is_array($this->data['cnt_files']['id']) && count($this->data['cnt_files']['id'])) {

            $where  = 'f_id IN (' . implode(',', $this->data['cnt_files']['id']) . ') AND ';
            $where .= 'f_kid=1 AND f_trash=0';
            if($mode !== 'backend') {
                $where .= ' AND f_aktiv=1 AND f_public=1';
            }

            $result = _dbGet('phpwcms_file', '*', $where);

            // Link results and keep sorting
            if(isset($result[0])) {
                foreach($this->data['cnt_files']['id'] as $key => $file_id) {
                    foreach($result as $file_data) {
                        if(intval($file_data['f_id']) === intval($file_id)) {
                            $data[$key] = $file_data;
                            continue;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function edit() {

        $this->newsId   = intval($_GET['cntid']);
        $this->data     = array();

        if(isset($_GET['status'])) {

            $status = intval($_GET['status']);

            switch($status) {

                case 0:
                case 1:
                case 9:
                    _dbUpdate('phpwcms_content', array('cnt_status'=>$status), 'cnt_id='.$this->newsId);
                    set_status_message(
                            $status == 9 ? $this->BL['be_action_deleted'] : $this->BL['be_action_status'],
                            'success',
                            array('ID'=>$this->newsId)
                        );
                    break;

                default:
                    set_status_message($this->BL['be_action_notvalid'], 'warning');

            }

            headerRedirect( $this->base_url_decoded );

        }

        $start_date = 0;
        $end_date   = 0;

        $this->data = array(
            'cnt_id'                => 0,
            'cnt_pid'               => 0,
            'cnt_status'            => intval($this->phpwcms['set_news_active']),
            'cnt_livedate'          => '0000-00-00 00:00:00',
            'cnt_killdate'          => '0000-00-00 00:00:00',
            'cnt_archive_status'    => 1,
            'cnt_alias'             => '',
            'cnt_name'              => '',
            'cnt_title'             => '',
            'cnt_subtitle'          => '',
            'cnt_editor'            => '',
            'cnt_place'             => '',
            'cnt_teasertext'        => '',
            'cnt_text'              => '',
            'cnt_duplicate'         => 0,
            'cnt_lang'              => '',
            'cnt_prio'              => 0,
            'cnt_readmore'          => 1,
            'cnt_image'             => array(
                'id'        => 0,
                'name'      => '',
                'zoom'      => 0,
                'lightbox'  => 0,
                'caption'   => '',
                'link'      => ''
             ),
            'cnt_files'             => array(
                'id'                => array(),
                'caption'           => '',
                'gallery'           => 0,
                'gallery_download'  => 0
             ),
            'cnt_link'              => '',
            'cnt_linktext'          => '',
            'cnt_category'          => '',
            'cnt_sort'              => 0,
            'cnt_opengraph'         => empty($this->phpwcms['set_sociallink']['news']) ? 0 : 1,
            'cnt_textformat'        => 'plain',
            'cnt_searchoff'         => 0
        );

        // check form post
        if( isset($_POST['cnt_name']) ) {

            $post       = $this->getPostData();
            $post_error = false;

            if(!empty($_POST['cnt_duplicate'])) {
                $this->newsId           = 0;
                $duplicate              = 1;
                $post['cnt_created']    = now();
            } else {
                $duplicate              = 0;
            }

            // 1st check if we have a name because it's mandatory
            if($post['cnt_name'] == '') {

                $post_error = true;

                set_status_message($this->BL['be_news_name_mandatory'], 'warning');

                $post['cnt_duplicate'] = $duplicate;

            }

            // do db work
            if($post_error === false) {

                $values = $post;
                $values['cnt_object'] = serialize($values['cnt_object']);

                $success = false;

                // store new dataset
                if($this->newsId == 0) {

                    $result = _dbInsert('phpwcms_content', $values);
                    if(isset($result['INSERT_ID'])) {
                        $this->newsId   = $result['INSERT_ID'];
                        $success        = true;

                        set_status_message($this->BL['be_successfully_saved'] . LF . $post['cnt_name'], 'success');
                    }

                // update existing dataset
                } else {

                    $result = _dbUpdate('phpwcms_content', $values, 'cnt_id='.$this->newsId);
                    if($result != false) {
                        $success = true;

                        set_status_message($this->BL['be_successfully_updated'] . LF . $post['cnt_name'], 'success');
                    }

                }

                // if success
                if($success) {

                    // save categories
                    if($this->newsId) {

                        _dbSaveCategories($post['cnt_object']['cnt_category'], 'news', $this->newsId, ',');

                    }

                    // redirect to form again
                    if($this->newsId && isset($_POST['submit'])) {
                        headerRedirect( $this->base_url_decoded . '&cntid='.$this->newsId.'&action=edit' );

                    // back to listing
                    } else {
                        headerRedirect( $this->base_url_decoded );
                    }

                // error while storing data
                } else {

                    set_status_message($BL['be_error_while_save'].trim( html( _dbErrorNum().': '._dbError() ) ), 'warning');

                }

            }

            $this->data = array_merge($this->data, $post);

        } elseif($this->newsId > 0) {

            $result = _dbGet('phpwcms_content', '*', 'cnt_status!=9 AND cnt_id='.$this->newsId, '', '', '1');
            if(isset($result[0])) {

                $result[0]['cnt_object'] = @unserialize($result[0]['cnt_object']);
                if(is_array($result[0]['cnt_object']['cnt_image'])) {
                    $result[0]['cnt_image'] = array_merge($this->data['cnt_image'], $result[0]['cnt_object']['cnt_image']);
                }
                if(is_array($result[0]['cnt_object']['cnt_files'])) {
                    $result[0]['cnt_files'] = array_merge($this->data['cnt_files'], $result[0]['cnt_object']['cnt_files']);
                }
                if(isset($result[0]['cnt_object']['cnt_link'])) {
                    $result[0]['cnt_link'] = $result[0]['cnt_object']['cnt_link'];
                }
                if(isset($result[0]['cnt_object']['cnt_linktext'])) {
                    $result[0]['cnt_linktext'] = $result[0]['cnt_object']['cnt_linktext'];
                }
                if(isset($result[0]['cnt_object']['cnt_category'])) {
                    $result[0]['cnt_category'] = $result[0]['cnt_object']['cnt_category'];
                }
                if(isset($result[0]['cnt_object']['cnt_readmore'])) {
                    $result[0]['cnt_readmore'] = $result[0]['cnt_object']['cnt_readmore'];
                }
                if(isset($result[0]['cnt_object']['cnt_textformat'])) {
                    $result[0]['cnt_textformat'] = $result[0]['cnt_object']['cnt_textformat'];
                }
                if(isset($result[0]['cnt_object']['cnt_searchoff'])) {
                    $result[0]['cnt_searchoff'] = $result[0]['cnt_object']['cnt_searchoff'];
                }

                $this->data = array_merge($this->data, $result[0]);

            } else {

                set_status_message($this->BL['be_data_select_failed'], 'warning');
                headerRedirect( $this->base_url_decoded );
            }

        }

        $start_date = strtotime( $this->data['cnt_livedate'] );
        $end_date   = strtotime( $this->data['cnt_killdate'] );
        $sort_date  = intval($this->data['cnt_sort']);

        if($start_date <= 0) {
            $this->data['cnt_livedate']     = '0000-00-00 00:00:00';
            $this->data['cnt_date_start']   = '';
            $this->data['cnt_time_start']   = '';
        } else {
            $this->data['cnt_date_start']   = date($this->BL['default_date'], $start_date);
            $this->data['cnt_time_start']   = date($this->BL['default_time'], $start_date);
        }

        if($end_date <= 0) {
            $this->data['cnt_killdate']     = '0000-00-00 00:00:00';
            $this->data['cnt_date_end']     = '';
            $this->data['cnt_time_end']     = '';
        } else {
            $this->data['cnt_date_end']     = date($this->BL['default_date'], $end_date);
            $this->data['cnt_time_end']     = date($this->BL['default_time'], $end_date);
        }

        // sort date
        if($sort_date <= 0) {
            $this->data['cnt_sort']         = 0;
            $this->data['cnt_sort_date']    = '';
            $this->data['cnt_sort_time']    = '';
        } else {
            $this->data['cnt_sort_date']    = date($this->BL['default_date'], $sort_date);
            $this->data['cnt_sort_time']    = date($this->BL['default_time'], $sort_date);
        }

    }

    public function getPostData() {

        $post = array();

        // do only when news ID is known
        if( $this->newsId == 0 ) {

            $post['cnt_created']    = now();

        }

        $post['cnt_pid']            = 0;
        $post['cnt_type']           = '';
        $post['cnt_module']         = 'news';
        $post['cnt_changed']        = time();

        $post['cnt_status']         = empty($_POST['cnt_status']) ? 0 : 1;
        $post['cnt_archive_status'] = empty($_POST['cnt_archive_status']) ? 0 : 1;
        $post['cnt_prio']           = empty($_POST['cnt_prio']) ? 0 : intval($_POST['cnt_prio']);

        $temp_time                  = isset($_POST['calendar_start_time']) ? _getTime($_POST['calendar_start_time']) : '';
        $temp_date                  = isset($_POST['calendar_start_date']) ? _getDate($_POST['calendar_start_date']) : '';
        $post['cnt_livedate']       = $temp_date.' '.$temp_time;

        $temp_time                  = isset($_POST['calendar_end_time']) ? _getTime($_POST['calendar_end_time']) : '';
        $temp_date                  = isset($_POST['calendar_end_date']) ? _getDate($_POST['calendar_end_date']) : '';
        $post['cnt_killdate']       = $temp_date.' '.$temp_time;

        $temp_time                  = isset($_POST['sort_time']) ? _getTime($_POST['sort_time']) : '';
        $temp_date                  = isset($_POST['sort_date']) ? _getDate($_POST['sort_date']) : '';
        $post['cnt_sort']           = intval( strtotime($temp_date.' '.$temp_time) );

        $post['cnt_name']           = isset($_POST['cnt_name']) ? clean_slweg($_POST['cnt_name']) : '';
        $post['cnt_title']          = isset($_POST['cnt_title']) ? clean_slweg($_POST['cnt_title']) : '';

        if($post['cnt_name'] == '' && $post['cnt_title'] != '') {
            $post['cnt_name'] = $post['cnt_title'];
        } elseif($post['cnt_name'] != '' && $post['cnt_title'] == '') {
            $post['cnt_title'] = $post['cnt_name'];
        }

        $post['cnt_alias']          = isset($_POST['cnt_alias']) ? clean_slweg($_POST['cnt_alias']) : '';
        if(empty($this->phpwcms['allow_empty_alias']) && $post['cnt_alias'] == '') {
            $post['cnt_alias'] = empty($post['cnt_title']) ? $post['cnt_name'] : $post['cnt_title'];
        }
        $post['cnt_alias']          = proof_alias($this->newsId, $post['cnt_alias'], 'CONTENT');

        $post['cnt_subtitle']       = isset($_POST['cnt_subtitle']) ? clean_slweg($_POST['cnt_subtitle']) : '';
        $post['cnt_editor']         = isset($_POST['cnt_editor']) ? clean_slweg($_POST['cnt_editor']) : '';
        $post['cnt_place']          = isset($_POST['cnt_place']) ? clean_slweg($_POST['cnt_place']) : '';
        $post['cnt_teasertext']     = isset($_POST['cnt_teasertext']) ? clean_slweg($_POST['cnt_teasertext']) : '';
        $post['cnt_text']           = isset($_POST['cnt_text']) ? slweg($_POST['cnt_text']) : '';

        $category                   = isset($_POST['cnt_category']) ? decode_entities(clean_slweg($_POST['cnt_category'])) : '';
        $category                   = trim( trim( preg_replace('/\s+/', ' ', $category), ',' ) );

        $post['cnt_lang']           = isset($_POST['cnt_lang']) ? preg_replace('/[^a-z\-]/', '', strtolower($_POST['cnt_lang'])) : '';
        $post['cnt_opengraph']      = empty($_POST['cnt_opengraph']) ? 0 : 1;

        $post['cnt_object']         = array(

            'cnt_image' => array(
                'id'        => isset($_POST['cnt_image_id']) ? intval($_POST['cnt_image_id']) : '',
                'name'      => isset($_POST['cnt_image_name']) ? clean_slweg($_POST['cnt_image_name']) : '',
                'zoom'      => empty($_POST['cnt_image_zoom']) ? 0 : 1,
                'lightbox'  => empty($_POST['cnt_image_lightbox']) ? 0 : 1,
                'caption'   => isset($_POST['cnt_image_caption']) ? clean_slweg($_POST['cnt_image_caption']) : '',
                'link'      => isset($_POST['cnt_image_link']) ? clean_slweg($_POST['cnt_image_link']) : ''
            ),

            'cnt_files' => array(
                'id'                => isset($_POST['cnt_files']) && is_array($_POST['cnt_files']) && count($_POST['cnt_files']) ? $_POST['cnt_files'] : array(),
                'caption'           => isset($_POST['cnt_file_caption']) ? clean_slweg($_POST['cnt_file_caption'], 0, false) : '',
                'gallery'           => empty($_POST['cnt_file_gallery']) ? 0 : 1,
                'gallery_download'  => empty($_POST['cnt_file_gallery_download']) ? 0 : 1
            ),

            'cnt_link'          => isset($_POST['cnt_link']) ? clean_slweg($_POST['cnt_link']) : '',
            'cnt_linktext'      => isset($_POST['cnt_linktext']) ? clean_slweg($_POST['cnt_linktext']) : '',
            'cnt_category'      => $category,
            'cnt_readmore'      => empty($_POST['cnt_readmore']) ? 0 : 1,
            'cnt_textformat'    => isset($_POST['cnt_textformat']) ? clean_slweg($_POST['cnt_textformat']) : 'plain',
            'cnt_searchoff'     => empty($_POST['cnt_searchoff']) ? 0 : 1,

        );

        return $post;

    }

}
