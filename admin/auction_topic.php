<?php

/**
 * ECSHOP 管理中心拍卖活动管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: auction.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_goods.php');

$exc = new exchange($ecs->table('goods_activity_topic'), $db, 't_id', 'topic_name');


/*------------------------------------------------------ */
//-- 活动商品列表页
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
    admin_priv('auction_topic');

    /* 模板赋值 */
    $smarty->assign('full_page',   1);
    $smarty->assign('ur_here',     $_LANG['auction_list']);
    $smarty->assign('action_link', array('href' => 'auction_topic.php?act=add&act_id='.$act_id, 'text' => $_LANG['add_auction']));

    $list = auction_topic_list();

    $smarty->assign('auction_topic_list', $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 显示商品列表页面 */
    assign_query_info();
    $smarty->display('auction_topic_list.htm');
}

/*------------------------------------------------------ */
//-- 分页、排序、查询
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    $list = auction_topic_list();

    $smarty->assign('auction_topic_list', $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('auction_topic_list.htm'), '',
        array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 删除
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('auction');

    $id = intval($_GET['id']);
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('goods_activity_topic') . " WHERE t_id = '$id'";
    $auction = $GLOBALS['db']->getRow($sql);
    if (empty($auction))
    {
        make_json_error($_LANG['auction_not_exist']);
    }
    $now = gmtime();
    $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('goods_activity') . 
        " WHERE t_id = '$id' AND is_finished = 0 AND start_time <= '$now' AND end_time >= '$now' ";
    $count = $GLOBALS['db']->getOne($sql);
    if ($count > 0)
    {
        make_json_error($_LANG['auction_cannot_remove']);
    }
    $name = $auction['topic_name'];
    $exc->drop($id);

    /* 记日志 */
    admin_log($name, 'remove', 'auction');

    /* 清除缓存 */
    clear_cache_files();

    $url = 'auction_topic.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_record_selected']);
    }
    else
    {
        /* 检查权限 */
        admin_priv('auction');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['drop']))
        {
            /* 查询哪些拍卖活动已经有人出价 */
            $sql = "SELECT DISTINCT act_id FROM " . $ecs->table('auction_log') .
                    " WHERE act_id " . db_create_in($ids);
            $ids = array_diff($ids, $db->getCol($sql));
            if (!empty($ids))
            {
                /* 删除记录 */
                $sql = "DELETE FROM " . $ecs->table('goods_activity') .
                        " WHERE act_id " . db_create_in($ids) .
                        " AND act_type = '" . GAT_AUCTION . "'";
                $db->query($sql);

                /* 记日志 */
                admin_log('', 'batch_remove', 'auction');

                /* 清除缓存 */
                clear_cache_files();
            }
            $links[] = array('text' => $_LANG['back_auction_list'], 'href' => 'auction.php?act=list&' . list_link_postfix());
            sys_msg($_LANG['batch_drop_ok'], 0, $links);
        }
    }
}

/*------------------------------------------------------ */
//-- 添加、编辑
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('auction_topic');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('form_action', $is_add ? 'insert' : 'update');

    /* 初始化、取得拍卖活动信息 */
    if ($is_add)
    {
        $auction = array(
            't_id'        => 0,
            'topic_name'      => '',
            'topic_desc'      => '',
            'start_time'    => date('Y-m-d', time() + 86400),
            'end_time'      => date('Y-m-d', time() + 4 * 86400),
        );
    }
    else
    {
        if (empty($_GET['id']))
        {
            sys_msg('invalid param');
        }
        $id = intval($_GET['id']);
        $auction = auction_topic_info($id, true);
        if (empty($auction))
        {
            sys_msg($_LANG['auction_not_exist']);
        }
        $auction['status'] = $_LANG['auction_status'][$auction['status_no']];
    }
    $smarty->assign('auction', $auction);

    /* 赋值时间控件的语言 */
    $smarty->assign('cfg_lang', $_CFG['lang']);

    /* 显示模板 */
    if ($is_add)
    {
        $smarty->assign('ur_here', $_LANG['add_auction']);
    }
    else
    {
        $smarty->assign('ur_here', $_LANG['edit_auction']);
    }
    $smarty->assign('action_link', list_link($is_add));

    assign_query_info();
    $smarty->display('auction_topic_info.htm');
}
/*------------------------------------------------------ */
//-- 添加、编辑后提交
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('auction_topic');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'insert';

    /* 提交值 */
    $auction = array(
        't_id'        => intval($_POST['t_id']),
        'topic_name'      => sub_str($_POST['topic_name'], 255, false),
        'topic_desc'      => $_POST['topic_desc'],
        'start_time'    => local_strtotime($_POST['start_time']),
        'end_time'      => local_strtotime($_POST['end_time']),
    );
    
    /* 保存数据 */
    if ($is_add)
    {
        $db->autoExecute($ecs->table('goods_activity_topic'), $auction, 'INSERT');
        $auction['t_id'] = $db->insert_id();
    }
    else
    {
        $db->autoExecute($ecs->table('goods_activity_topic'), $auction, 'UPDATE', "t_id = '$auction[t_id]'");
        
        //更新活动下商品的拍卖时间
        $data = array(
            'act_name'      => $auction['topic_name'],
            'act_desc'      => $auction['topic_desc'],
            'start_time'    => $auction['start_time'],
            'end_time'      => $auction['end_time'],
        );
        $db->autoExecute($ecs->table('goods_activity'), $data, 'UPDATE', "t_id = '$auction[t_id]'");
    }

    /* 记日志 */
    if ($is_add)
    {
        admin_log($auction['topic_name'], 'add', 'auction');
    }
    else
    {
        admin_log($auction['topic_name'], 'edit', 'auction');
    }

    /* 清除缓存 */
    clear_cache_files();

    /* 提示信息 */
    if ($is_add)
    {
        $links = array(
            array('href' => 'auction_topic.php?act=add', 'text' => $_LANG['continue_add_auction']),
            array('href' => 'auction_topic.php?act=list', 'text' => $_LANG['back_auction_list'])
        );
        sys_msg($_LANG['add_auction_ok'], 0, $links);
    }
    else
    {
        $links = array(
            array('href' => 'auction_topic.php?act=list&' . list_link_postfix(), 'text' => $_LANG['back_auction_list'])
        );
        sys_msg($_LANG['edit_auction_ok'], 0, $links);
    }
}


/*
 * 取得拍卖活动列表
 * @return   array
 */
function auction_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['is_going']   = empty($_REQUEST['is_going']) ? 0 : 1;
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = "";
        if (!empty($filter['keyword']))
        {
            $where .= " AND goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['is_going'])
        {
            $now = gmtime();
            $where .= " AND is_finished = 0 AND start_time <= '$now' AND end_time >= '$now' ";
        }

        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('goods_activity') .
                " WHERE act_type = '" . GAT_AUCTION . "' $where";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT * ".
                "FROM " . $GLOBALS['ecs']->table('goods_activity') .
                " WHERE act_type = '" . GAT_AUCTION . "' $where ".
                " ORDER BY $filter[sort_by] $filter[sort_order] ".
                " LIMIT ". $filter['start'] .", $filter[page_size]";

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->query($sql);

    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ext_info = unserialize($row['ext_info']);
        $arr = array_merge($row, $ext_info);

        $arr['start_time']  = local_date('Y-m-d H:i', $arr['start_time']);
        $arr['end_time']    = local_date('Y-m-d H:i', $arr['end_time']);

        $list[] = $arr;
    }
    $arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/*
 * 取得拍卖列表
 * @return   array
 */
function auction_topic_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['is_going']   = empty($_REQUEST['is_going']) ? 0 : 1;
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 't_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = "1=1";
        if (!empty($filter['keyword']))
        {
            $where .= " AND topic_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['is_going'])
        {
            $now = gmtime();
            $where .= " AND start_time <= '$now' AND end_time >= '$now' ";
        }

        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('goods_activity_topic') .
        " WHERE $where";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT * ".
            "FROM " . $GLOBALS['ecs']->table('goods_activity_topic') .
            " WHERE $where ".
            " ORDER BY $filter[sort_by] $filter[sort_order] ".
            " LIMIT ". $filter['start'] .", $filter[page_size]";

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->query($sql);
    
    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr = $row;

        $arr['start_time']  = local_date('Y-m-d H:i', $row['start_time']);
        $arr['end_time']    = local_date('Y-m-d H:i', $row['end_time']);

        $list[] = $arr;
    }
    $arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 * 列表链接
 * @param   bool    $is_add     是否添加（插入）
 * @param   string  $text       文字
 * @return  array('href' => $href, 'text' => $text)
 */
function list_link($is_add = true, $text = '')
{
    $href = 'auction_topic.php?act=list';
    if (!$is_add)
    {
        $href .= '&' . list_link_postfix();
    }
    if ($text == '')
    {
        $text = $GLOBALS['_LANG']['auction_list'];
    }

    return array('href' => $href, 'text' => $text);
}

/**
 * 取得拍卖活动信息
 * @param   int     $id     活动id
 * @return  array
 */
function auction_topic_info($id, $config = false)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('goods_activity_topic') . " WHERE t_id = '$id'";
    $auction = $GLOBALS['db']->getRow($sql);
    $now = gmtime();
    if ($now < $auction['start_time'])
    {
        $auction['status_no'] = PRE_START; // 未开始
    }
    elseif ($now > $auction['end_time'])
    {
        $auction['status_no'] = FINISHED; // 已结束，未处理
    }
    else
    {
        $auction['status_no'] = UNDER_WAY; // 进行中
    }
    if ($config == true)
    {

        $auction['start_time'] = local_date('Y-m-d H:i', $auction['start_time']);
        $auction['end_time'] = local_date('Y-m-d H:i', $auction['end_time']);
    }
    else
    {
        $auction['start_time'] = local_date($GLOBALS['_CFG']['time_format'], $auction['start_time']);
        $auction['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $auction['end_time']);
    }

    return $auction;
}

?>