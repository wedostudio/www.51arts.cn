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

/* 获得指定的分类ID */
if (!empty($_GET['id']))
{
    $cat_id =$_GET['id'];
}
elseif (!empty($_GET['category']))
{
    $cat_id = intval($_GET['category']);
}
// else
// {
//     ecs_header("Location: ./\n");

//     exit;
// }

/* 获得当前页码 */
$page   = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/* 获得页面的缓存ID */
$cache_id = sprintf('%X', crc32($cat_id . '-' . $page . '-' . $_CFG['lang']));

if (!$smarty->is_cached('article_cat.dwt', $cache_id))
{
    /* 如果页面没有被缓存则重新获得页面的内容 */

    assign_template('a', array($cat_id));
    $position = assign_ur_here($cat_id);
    $smarty->assign('page_title',           $position['title']);     // 页面标题
    $smarty->assign('ur_here',              $position['ur_here']);   // 当前位置

    $smarty->assign('categories',           get_categories_tree(0)); // 分类树
    $smarty->assign('article_categories',   article_categories_tree($cat_id)); //文章分类树
    $smarty->assign('helps',                get_shop_help());        // 网店帮助
    $smarty->assign('top_goods',            get_top10());            // 销售排行

    $smarty->assign('best_goods',           get_recommend_goods('best'));
    $smarty->assign('new_goods',            get_recommend_goods('new'));
    $smarty->assign('hot_goods',            get_recommend_goods('hot'));
    $smarty->assign('promotion_goods',      get_promote_goods());
    $smarty->assign('promotion_info', get_promotion_info());
	

    /* Meta */
    $meta = $db->getRow("SELECT keywords, cat_desc FROM " . $ecs->table('article_cat') . " WHERE cat_id = '$cat_id'");

//     if ($meta === false || empty($meta))
//     {
//         /* 如果没有找到任何记录则返回首页 */
//         ecs_header("Location: ./\n");
//         exit;
//     }

    $smarty->assign('keywords',    htmlspecialchars($meta['keywords']));
    $smarty->assign('description', htmlspecialchars($meta['cat_desc']));

    /* 获得文章总数 */
    $size   = 8;
    $count  = get_article_count($cat_id);
    $pages  = ($count > 0) ? ceil($count / $size) : 1;

    if ($page > $pages)
    {
        $page = $pages;
    }
    $pager['search']['id'] = $cat_id;
    $keywords = '';
    $goon_keywords = ''; //继续传递的搜索关键词

    /* 获得文章列表 */
    if (isset($_REQUEST['keywords']))
    {
        $keywords = addslashes(htmlspecialchars(urldecode(trim($_REQUEST['keywords']))));
        $pager['search']['keywords'] = $keywords;
        $search_url = substr(strrchr($_POST['cur_url'], '/'), 1);

        $smarty->assign('search_value',    stripslashes(stripslashes($keywords)));
        $smarty->assign('search_url',       $search_url);
        $count  = get_article_count($cat_id, $keywords);
        $pages  = ($count > 0) ? ceil($count / $size) : 1;
        if ($page > $pages)
        {
            $page = $pages;
        }

        $goon_keywords = urlencode($_REQUEST['keywords']);
    }
//左边文章列表
	$articles_list=wedo_get_cat_articles($cat_id, $page, $size ,$keywords);
    $smarty->assign('articles_list',    $articles_list);;
    $smarty->assign('cat_id',    $cat_id);
//热闹资讯
	$hot_news = cat_articles('14,15,16', 20, 1, 5);
    $hot_news_top = $hot_news ? array_slice($hot_news, 0, 1) : array();
    $smarty->assign('hot_news_top',  $hot_news_top);
    $hot_news = $hot_news ? array_slice($hot_news, 1,4) : array();
    $smarty->assign('hot_news',  $hot_news);
//热卖商品 array_slice($hot_news, 0, 1)
	$top_goods=array_slice(get_top10(), 0, 4);
	$smarty->assign('top_goods',      $top_goods ); 
//wedo 新分页
	$wedopage=getPageHtml($cat_id,$_REQUEST['page'],$pages);
 	$smarty->assign('wedopage',$wedopage);
	switch($cat_id){
	case 14:
	$smarty->assign('type',      'superinfo_title' ); break;
	case 15:
	$smarty->assign('type',      'arteye_title' ); break;
	case 16:
	$smarty->assign('type',      'artdecode_title' ); break;
	
	
}

    /* 分页 */           
    assign_pager('article_cat', $cat_id, $count, $size, '', '', $page, $goon_keywords);
    assign_dynamic('article_cat');
}

//$smarty->assign('feed_url',         ($_CFG['rewrite'] == 1) ? "feed-typearticle_cat" . $cat_id . ".xml" : 'feed.php?type=article_cat' . $cat_id); // RSS URL

$smarty->display('article_cat.dwt', $cache_id);
exit;














//不改变原先函数，增加一个函数用于增加对点赞人数的的获取，以及评论数的获取
function wedo_get_cat_articles($cat_id, $page = 1, $size = 8 ,$requirement='')
{
    //取出所有非0的文章
//  if ($cat_id == '-1')
//  {
//      $cat_str = 'cat_id > 0';
//  }
//  else
//  {
//      $cat_str = get_article_children($cat_id);
//  }
    //增加搜索条件，如果有搜索内容就进行搜索  
   global $db, $ecs;
if ($requirement != '')
    {
        $sql = 'SELECT article_id, title, author, add_time, file_url, open_type, support_count, content' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND title like \'%' . $requirement . '%\' AND cat_id= '.$cat_id.
               ' ORDER BY article_type DESC, article_id DESC';
    }
    else 
    {
        
        $sql = 'SELECT article_id, title, author, add_time, file_url, open_type, support_count, content ' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND cat_id='.$cat_id.
               ' ORDER BY article_type DESC, article_id DESC';
 
	}
//	echo $sql;exit;
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page-1) * $size);

    $arr = array();
    if ($res)
    {
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $article_id = $row['article_id'];
            $arr[$article_id]['id']          = $article_id;
            $arr[$article_id]['title']       = $row['title'];
            $arr[$article_id]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ? sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = date($GLOBALS['_CFG']['date_format'], $row['add_time']);
			$comment_count = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table('comment') . " WHERE id_value=".$article_id);
            $arr[$article_id]['comment_count']    = $comment_count;
			$arr[$article_id]['support_count']          = $support_count;
			$arr[$article_id]['content']       = $row['content'];
			$arr[$article_id]['file_url']       = $row['file_url'];
			
//			$arr[$article_id]['cat_id']       = "=======================".$row['cat_id']."=======================";
        }
    }
// 	var_dump($arr);exit;
    return $arr;
}

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
//echo $sql;exit;
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
            $arr[$article_id]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ? sub_str($row['title'], $length) : $row['title'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = date($GLOBALS['_CFG']['date_format'], $row['add_time']);
//          echo $cat_id.'   '.$article_id.'    '.$GLOBALS['_CFG']['article_title_length'].'<br />';
        }
    }

    return $arr;
}
function getPageHtml($cat_id,$page, $pages=15){
	
	//$pages=;//sql获取总页数
  //最多显示多少个页码
  $_pageNum = 5;
  //当前页面小于1 则为1
  $page = $page<1?1:$page;
  //当前页大于总页数 则为总页数
  $page = $page > $pages ? $pages : $page;
  //页数小当前页 则为当前页
  $pages = $pages < $page ? $page : $pages;

  //计算开始页
  $_start = $page - floor($_pageNum/2);
  $_start = $_start<1 ? 1 : $_start;
  //计算结束页
  $_end = $page + floor($_pageNum/2);
  $_end = $_end>$pages? $pages : $_end;

  //当前显示的页码个数不够最大页码数，在进行左右调整
  $_curPageNum = $_end-$_start+1;
  //左调整
  if($_curPageNum<$_pageNum && $_start>1){ 
   $_start = $_start - ($_pageNum-$_curPageNum);
   $_start = $_start<1 ? 1 : $_start;
   $_curPageNum = $_end-$_start+1;
  }
  //右边调整
  if($_curPageNum<$_pageNum && $_end<$pages){
   $_end = $_end + ($_pageNum-$_curPageNum);
   $_end = $_end>$pages? $pages : $_end;
  }
//echo $_start."/////".$_end."/////".$_curPageNum;exit;
  $_pageHtml = '<ul>';

if($page>1){
   $_pageHtml .= '<li><a href="article_cat.php?id='.$cat_id.'&page='.($page-1).'">上一页</a></li>
							<li id="page_select">
								<form>
									<div id="pagectl_divselect">
										<cite>第'.$page.'页</cite>
										<ul>';
}else{
	$_pageHtml .= '<li><a></a></li>
							<li id="page_select">
								<form>
									<div id="pagectl_divselect">
										<cite>第'.$page.'页</cite>
										<ul>';
	
	
	
    }
  for ($i = $_start; $i <= $_end; $i++) {
   if($i == $page){
    $_pageHtml .= '<li><a selectid="2">第'.$i.'页</a></li>';
   }else{
    $_pageHtml .= '<li><a href="article_cat.php?id='.$cat_id.'&page='.$i.'" selectid="'.$i.'">第'.$i.'页</a></li>';
   }
  }
  /*if($_end == $pages){
   $_pageHtml .= '<li><a title="最后一页">»</a></li>';
  }else{
   $_pageHtml .= '<li><a  title="最后一页" href="'.$url.'&page='.$pages.'">»</a></li>';
  }*/
if($page<$_end){//</ul>
									
						
   $_pageHtml .= '</ul></div><input name="" type="hidden" value="" id="pagectl_inputselect" /></form></li><li><a href="article_cat.php?id='.$cat_id.'&page='.($page+1).'">下一页</a></li>';
  
}else{
	
	   $_pageHtml .= '</ul></div><input name="" type="hidden" value="" id="pagectl_inputselect" /></form></li><li><a></a></li>';
}
  

  
  $_pageHtml .= '</ul>';
  return  $_pageHtml;
  
 }

?>
