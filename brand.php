<?php

/**
 * ECSHOP 品牌列表
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: brand.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

/* 获得请求的分类 ID */
if (!empty($_REQUEST['id']))
{
    $brand_id = intval($_REQUEST['id']);
}
if (!empty($_REQUEST['brand']))
{
    $brand_id = intval($_REQUEST['brand']);
}
if (empty($brand_id))
{
    /* 缓存编号 */
//  if(empty($_REQUEST['artist_type'])){
	    $cache_id = sprintf('%X', crc32($_CFG['lang']));
			    if (!$smarty->is_cached('brand_list.dwt', $cache_id))
			    {
			        assign_template();
			        $position = assign_ur_here('', $_LANG['all_brand']);
			        $smarty->assign('page_title',      $position['title']);    // 页面标题
			        $smarty->assign('ur_here',         $position['ur_here']);  // 当前位置
			
			        $smarty->assign('categories',      get_categories_tree()); // 分类树
			        $smarty->assign('helps',           get_shop_help());       // 网店帮助
			        $smarty->assign('top_goods',       get_top10());           // 销售排行
			        
			        define(ARTIST, 1);define(JIGOU,2);define(AIHAOZHE, 3);
					
					$smarty->assign('top10artist',       get_top(ARTIST,10));//wedo 获取前十的艺术家
					$smarty->assign('top2artist',       get_top(JIGOU,2));//wedo 获取前2的艺术机构
					$smarty->assign('top4artist',       get_top(AIHAOZHE,4));//wedo 获取前4的收藏爱好者
			        $smarty->assign('brand_list', get_brands());
//					echo "no artist_type";exit;
				}
//			}else{
//				
//		/* 排序、显示方式以及类型 */
//
// var_dump(get_top_cat_list()) ;exit;
//$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
//$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
//$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');
//
//$sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
//$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
//$order = (isset($_REQUEST['catsort']) && in_array(trim(strtoupper($_REQUEST['catsort'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
//$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
//$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';
//setcookie('ECS[display]', $display, gmtime() + 86400 * 7);
//
//
//	    $cache_id = sprintf('%X', crc32($_CFG['lang'].$_REQUEST['artist_type']));
//	    if ($smarty->is_cached('artist_list.dwt', $cache_id))
//	    {
//	    	//echo $_REQUEST['artist_type'];exit;
//	        assign_template();
//	        $position = assign_ur_here('', $_LANG['all_brand']);
//	        $smarty->assign('page_title',      $position['title']);    // 页面标题
//	        $smarty->assign('ur_here',         $position['ur_here']);  // 当前位置
//	
//	        $smarty->assign('categories',      get_categories_tree()); // 分类树
//	        $smarty->assign('helps',           get_shop_help());       // 网店帮助
//	        $smarty->assign('top_goods',       get_top10());           // 销售排行
//	        
//	        define(ARTIST, 1);define(JIGOU,2);define(AIHAOZHE, 3);
//			
//			$smarty->assign('top10artist',       get_top(ARTIST,10));//wedo 获取前十的艺术家
//			$smarty->assign('top2artist',       get_top(JIGOU,2));//wedo 获取前2的艺术机构
//			$smarty->assign('top4artist',       get_top(AIHAOZHE,4));//wedo 获取前4的收藏爱好者
//	        $smarty->assign('brand_list', get_brands());
////				$smarty->assign('top_cat_list', get_top_cat_list());
////				var_dump(get_top_cat_list());exit;
////			$size = 6;
////			if(!in_array($_REQUEST['artist_type'], array(1,2,3))){echo"非法";exit;}
////			$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;	
////			$record_count = $db->getOne("SELECT COUNT(*) FROM " .$ecs->table('brand'). " WHERE artist_type=".$_REQUEST['artist_type']);
////			
////			$pager = get_pager('brand.php', array(), $record_count, $page, $size);
////			var_dump($pager);exit;
////			$artist_list = get_artist($_REQUEST['artist_type'], $size, $pager['start']);
////			$smarty->assign('artist_list', $artist_list);
////			$smarty->assign('pager',  $pager);
//			
//			
//			
//			
//			
//			$smarty->display('artist_list.dwt', $cache_id);
//  		exit();
//			
//			
//			
//			
//		}
//  }

    $smarty->display('brand_list.dwt', $cache_id);
    exit();
}

/* 初始化分页信息 */
$page = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
$size = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 6;
$cate = !empty($_REQUEST['cat'])   && intval($_REQUEST['cat'])   > 0 ? intval($_REQUEST['cat'])   : 0;
$letter = !empty($_REQUEST['letter'])   && intval($_REQUEST['letter'])   > 0 ? intval($_REQUEST['letter'])   : 0;
$times = !empty($_REQUEST['times'])   && intval($_REQUEST['times'])   > 0 ? intval($_REQUEST['times'])   : 0;
	$smarty->assign('top_cat_list', get_top_cat_list());
/* 排序、显示方式以及类型 */
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'brand_id' : 'last_update';

$sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('brand_id','last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;
//$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
//$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';
//setcookie('ECS[display]', $display, gmtime() + 86400 * 7);

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/* 页面的缓存ID */
$cache_id = sprintf('%X', crc32($brand_id . '-' . $sort . '-' . $order . '-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' . $_CFG['lang'] . '-' . $cate. '-' . $letter. '-' . $times));

if (!$smarty->is_cached('artist_lists.dwt', $cache_id))
{
    $brand_info = get_allbrand_info($brand_id);
   //var_dump($brand_info);exit;
    if (empty($brand_info))
    {
        ecs_header("Location: ./\n");
        exit;
    }

    $smarty->assign('data_dir',    DATA_DIR);
    $smarty->assign('keywords',    htmlspecialchars($brand_info['brand_desc']));
    $smarty->assign('description', htmlspecialchars($brand_info['brand_desc']));

    /* 赋值固定内容 */
    assign_template();
    $position = assign_ur_here($cate, $brand_info['brand_name']);
    $smarty->assign('page_title',     $position['title']);   // 页面标题
    $smarty->assign('ur_here',        $position['ur_here']); // 当前位置
    $smarty->assign('artist_type',       $brand_id);
    $smarty->assign('category',       $cate);

    $smarty->assign('categories',     get_categories_tree());        // 分类树
    $smarty->assign('helps',          get_shop_help());              // 网店帮助
    $smarty->assign('top_goods',      get_top10());                  // 销售排行
    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);
    $smarty->assign('brand_cat_list', brand_related_cat($brand_id)); // 相关分类
    $smarty->assign('feed_url',       ($_CFG['rewrite'] == 1) ? "feed-b$brand_id.xml" : 'feed.php?brand=' . $brand_id);

    /* 调查 */
    $vote = get_vote();
    if (!empty($vote))
    {
        $smarty->assign('vote_id',     $vote['id']);
        $smarty->assign('vote',        $vote['content']);
    }

    $smarty->assign('best_goods',      brand_recommend_goods('best', $brand_id, $cate));
    $smarty->assign('promotion_goods', brand_recommend_goods('promote', $brand_id, $cate));
    $smarty->assign('artist_list',           $brand_info);
    $smarty->assign('promotion_info', get_promotion_info());

    //$count = goods_count_by_brand($brand_id, $cate);

    //$goodslist = brand_get_goods($brand_id, $cate, $size, $page, $sort, $order);

//  if($display == 'grid')
//  {
//      if(count($goodslist) % 2 != 0)
//      {
//          $goodslist[] = array();
//      }
//  }
    //$smarty->assign('goods_list',      $goodslist);
    $smarty->assign('script_name', 'brand');
//var_dump($brand_id);
$sql = "SELECT COUNT(*) FROM ".$ecs->table('brand')." WHERE artist_type=".$brand_id;
//echo  $sql;exit;
$count = $GLOBALS['db']->getOne($sql);


    assign_pager('allbrand',              $cate, $count, $size, $sort, $order, $page, '', $brand_id, 0, 0,'','','','',$letter,$times);
    assign_dynamic('artist_list'); // 动态内容
}

$smarty->display('artist_list.dwt', $cache_id);

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得指定品牌的详细信息
 *
 * @access  private
 * @param   integer $id
 * @return  void
 */
function get_brand_info($id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') . " WHERE brand_id = '$id'";

    return $GLOBALS['db']->getRow($sql);
}
function get_allbrand_info($id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') . " WHERE artist_type = '$id'";

    return $GLOBALS['db']->getAll($sql);
}

/**
 * 获得指定品牌下的推荐和促销商品
 *
 * @access  private
 * @param   string  $type
 * @param   integer $brand
 * @return  array
 */
function brand_recommend_goods($type, $brand, $cat = 0)
{
    static $result = NULL;

    $time = gmtime();

    if ($result === NULL)
    {
        if ($cat > 0)
        {
            $cat_where = "AND " . get_children($cat);
        }
        else
        {
            $cat_where = '';
        }

        $sql = 'SELECT g.goods_id, g.goods_name, g.market_price, g.shop_price AS org_price, g.promote_price, ' .
                    "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                    'promote_start_date, promote_end_date, g.goods_brief, g.goods_thumb, goods_img, ' .
                    'b.brand_name, g.is_best, g.is_new, g.is_hot, g.is_promote ' .
                'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON b.brand_id = g.brand_id ' .
                'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp '.
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
                "WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.brand_id = '$brand' AND " .
                    "(g.is_best = 1 OR (g.is_promote = 1 AND promote_start_date <= '$time' AND ".
                    "promote_end_date >= '$time')) $cat_where" .
               'ORDER BY g.sort_order, g.last_update DESC';
        $result = $GLOBALS['db']->getAll($sql);
    }

    /* 取得每一项的数量限制 */
    $num = 0;
    $type2lib = array('best'=>'recommend_best', 'new'=>'recommend_new', 'hot'=>'recommend_hot', 'promote'=>'recommend_promotion');
    $num = get_library_number($type2lib[$type]);

    $idx = 0;
    $goods = array();
    foreach ($result AS $row)
    {
        if ($idx >= $num)
        {
            break;
        }

        if (($type == 'best' && $row['is_best'] == 1) ||
            ($type == 'promote' && $row['is_promote'] == 1 &&
            $row['promote_start_date'] <= $time && $row['promote_end_date'] >= $time))
        {
            if ($row['promote_price'] > 0)
            {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
            }
            else
            {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id']           = $row['goods_id'];
            $goods[$idx]['name']         = $row['goods_name'];
            $goods[$idx]['brief']        = $row['goods_brief'];
            $goods[$idx]['brand_name']   = $row['brand_name'];
            $goods[$idx]['short_style_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                               sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
            $goods[$idx]['market_price'] = price_format($row['market_price']);
            $goods[$idx]['shop_price']   = price_format($row['shop_price']);
            $goods[$idx]['thumb']        = get_image_path($row['goods_id'], $row['goods_thumb'], true);
            $goods[$idx]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
            $goods[$idx]['url']          = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);

            $idx++;
        }
    }

    return $goods;
}

/**
 * 获得指定的品牌下的商品总数
 *
 * @access  private
 * @param   integer     $brand_id
 * @param   integer     $cate
 * @return  integer
 */
function goods_count_by_brand($brand_id, $cate = 0)
{
    $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('goods'). ' AS g '.
            "WHERE brand_id = '$brand_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0";

    if ($cate > 0)
    {
        $sql .= " AND " . get_children($cate);
    }

    return $GLOBALS['db']->getOne($sql);
}

/**
 * 获得品牌下的商品
 *
 * @access  private
 * @param   integer  $brand_id
 * @return  array
 */
function brand_get_goods($brand_id, $cate, $size, $page, $sort, $order)
{
    $cate_where = ($cate > 0) ? 'AND ' . get_children($cate) : '';

    /* 获得商品列表 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.market_price, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, " .
                'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
            "WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.brand_id = '$brand_id' $cate_where".
            "ORDER BY $sort $order";

    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
        }
        else
        {
            $promote_price = 0;
        }

        $arr[$row['goods_id']]['goods_id']      = $row['goods_id'];
        if($GLOBALS['display'] == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];
        }
        $arr[$row['goods_id']]['market_price']  = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']    = price_format($row['shop_price']);
        $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_brief']   = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_thumb']   = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']     = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url']           = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
    }

    return $arr;
}

/**
 * 获得与指定品牌相关的分类
 *
 * @access  public
 * @param   integer $brand
 * @return  array
 */
function brand_related_cat($brand)
{
    $arr[] = array('cat_id' => 0,
                 'cat_name' => $GLOBALS['_LANG']['all_category'],
                 'url'      => build_uri('brand', array('bid' => $brand), $GLOBALS['_LANG']['all_category']));

    $sql = "SELECT c.cat_id, c.cat_name, COUNT(g.goods_id) AS goods_count FROM ".
            $GLOBALS['ecs']->table('category'). " AS c, ".
            $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE g.brand_id = '$brand' AND c.cat_id = g.cat_id ".
            "GROUP BY g.cat_id";
    $res = $GLOBALS['db']->query($sql);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['url'] = build_uri('brand', array('cid' => $row['cat_id'], 'bid' => $brand), $row['cat_name']);
        $arr[] = $row;
    }

    return $arr;
}

function get_top($type,$number){
	switch ($type)
{
case 1:
        $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') .
        "WHERE artist_type = 1 AND is_show = 1 ORDER BY sort_order ASC limit ".$number;
  break;  
case 2:
        $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') .
        "WHERE artist_type = 2 AND is_show = 1 ORDER BY sort_order ASC limit ".$number;
  break;
case 3:
        $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') .
        "WHERE artist_type = 3 AND is_show = 1 ORDER BY sort_order ASC limit ".$number;
}
$res = $GLOBALS['db']->getAll($sql);

return $res;
}
/**/
 function get_artist($artist_type, $num = 16, $start = 0)

{

    $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('brand'). " WHERE artist_type=".$artist_type;

    $arr = array();

    $res = $GLOBALS['db']->SelectLimit($sql, $num, $start);

    while ($row = $GLOBALS['db']->fetchRow($res))

    {

	$arr[] = array(
	'brand_id'         => $row['brand_id'],
	'brand_logo'         => $row['brand_logo'],
	 'brand_name'        => $row['brand_name']);
	}

    return $arr;

}



?>