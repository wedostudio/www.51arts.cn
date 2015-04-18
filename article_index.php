<?php

/**
 * ECSHOP 文章分类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: article_cat.php 17217 2011-01-19 06:29:08Z liubo $
*/


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

/* 清除缓存 */
clear_cache_files();

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */


/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/* 获得页面的缓存ID */
$cache_id = sprintf('%X', crc32($_CFG['lang']));

if (!$smarty->is_cached('article_index.dwt', $cache_id))
{
    /* 如果页面没有被缓存则重新获得页面的内容 */

    $smarty->assign('page_title',           '艺术情报');     // 页面标题
    $smarty->assign('keywords',    htmlspecialchars('艺术情报'));
    $smarty->assign('description', htmlspecialchars('艺术情报'));
    
    $smarty->assign('categories',           get_categories_tree(0)); // 分类树
    $smarty->assign('article_categories',   article_categories_tree($cat_id)); //文章分类树
    $smarty->assign('helps',                get_shop_help());        // 网店帮助
    $smarty->assign('top_goods',            get_top10());            // 销售排行

    $smarty->assign('hot_goods',            get_recommend_goods('hot'));

    //艺术资讯
    $arts_news = cat_articles(12, 20, 1, 4);
    $arts_news_top = $arts_news ? array_slice($arts_news, 0, 1) : array();
    $smarty->assign('arts_news_top',  $arts_news_top);
    $arts_news = $arts_news ? array_slice($arts_news, 1) : array();
    $smarty->assign('arts_news',  $arts_news);
    
    //重磅快讯
    $heavy_news = cat_articles(13, 100, 1, 3, 1);
    $smarty->assign('arts_news',  $arts_news);
    //艺术眼
    $arts_eye = cat_articles(15, 50, 1, 5, 1);
    $arts_eye_top = $arts_eye ? array_slice($arts_eye, 0, 1) : array();
    $smarty->assign('arts_eye_top',  $arts_eye_top);
    $arts_eye1 = $arts_eye ? array_slice($arts_eye, 1, 2) : array();
    $smarty->assign('arts_eye1',  $arts_eye1);
    $arts_eye = $arts_eye ? array_slice($arts_eye, 3) : array();
    $smarty->assign('arts_eye',  $arts_eye);
    //艺术解码
    $arts_code = cat_articles(14, 80, 1, 4, 1);
    $smarty->assign('arts_code',  $arts_code);
    //点击排行
    $hits_news = cat_articles('12,13,14,15', 30, 1, 8);
    $smarty->assign('hits_news',  $hits_news);
    
    assign_dynamic('article_cat');
}

$smarty->display('article_index.dwt', $cache_id);

/**
 * 获得文章分类下的文章列表
 *
 * @access  public
 * @param   integer     $cat_id
 * @param   integer     $page
 * @param   integer     $size
 *
 * @return  array
 */
function cat_articles($cat_id, $length=20, $page = 1, $size = 20, $article_type=FALSE, $order='article_id')
{
    $sql = 'SELECT article_id, title, author, add_time, file_url, open_type, content' .
        ' FROM ' .$GLOBALS['ecs']->table('article') .
        ' WHERE is_open = 1 AND cat_id IN (' . $cat_id. ') AND file_url<>""';
    $sql .= $article_type!==false ? " AND article_type = $article_type" : '';
    $sql .= ' ORDER BY article_type DESC, '.$order.' DESC';

    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page-1) * $size);

    $arr = array();
    if ($res)
    {
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $article_id = $row['article_id'];

            $arr[$article_id]['id']          = $article_id;
            $arr[$article_id]['title']       = $row['title'];
            $arr[$article_id]['content']     = sub_str(strip_tags($row['content']), 20, '...');
            $arr[$article_id]['file_url']    = $row['file_url'];
            $arr[$article_id]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ? sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = date($GLOBALS['_CFG']['date_format'], $row['add_time']);
            
        }
    }

    return $arr;
}

?>