<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,utils.js')); ?>
<div id="top_wrap" class="fullwidth">
				<div id="top" class="fitwidth">
					<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
					<div id="search">

						<form name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()" id="top_form">
							<table>
								<tr>
									<td>
										<div id="divselect">
											<cite>筛选</cite>
											<ul>
												<li><a href="javascript:;" selectid="1" class="short_text">所有</a>
												</li>
												<li><a href="javascript:;" selectid="2">艺术品</a>
												</li>
												<li><a href="javascript:;" selectid="3">艺术家</a>
												</li>
												<li><a href="javascript:;" selectid="4" class="short_text">资讯</a>
												</li>
											</ul>
										</div>
										<input name="" type="hidden" value="0" id="inputselect" />
									</td>
									<td>
										<label for="keyword">
											<span id="keyword_def">热门搜索：银器/玉石...</span>
											<input id="keyword" type="text" name="keywords" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" />
										</label>
									</td>
									<td>
										<input id="submit" type="submit" name="submit" value="" />
									</td>
								</tr>
							</table>
						</form>

					</div>

				</div>
			</div>
			<div id="header" class="fitwidth">
				<a href="/index.php">
					<div id="logo"></div>
				</a>
				<div id="logo_sub">
					<a href="/index.php">
						<div id="logo_custom"></div>
					</a>
					<div id="logo_url"></div>
				</div>

				<div id="nav">
					<ul class="main_nav">
						<li class="navs navs_index">
							<a href="/index.php" class="nav_a">首页</a>
						</li>

						<li class="nav_sep">/</li>
						<li class="navs">
							<a href="/category.php?id=6" class="nav_a">艺术仓库</a>
							<ul id="subNav_2" class="sub_nav subnav_artrepos">
								<li><a href="/category.php?id=6">木元</a>
								</li>
								<li><a href="/category.php?id=12">陶元</a>
								</li>
								<li><a href="/category.php?id=1">漆元</a>
								</li>
								<li><a href="/category.php?id=16">石元</a>
								</li>
								<li><a href="/category.php?id=17">艺元</a>
								</li>
								<li><a href="/category.php?id=18">银元</a>
								</li>
								<li><a href="/category.php?id=19">多元</a>
								</li>
							</ul>
						</li>
						<li class="nav_sep">/</li>
						<li class="navs">
							<a href="brand.php" class="nav_a">艺术圈</a>
							<ul id="subNav_3" class="sub_nav subnav_artcircle">
								<li><a href="/brand.php?artist_type=1">艺<span>&nbsp;&nbsp;</span>术<span>&nbsp;&nbsp;</span>家</a>
								</li>
								<li><a href="/brand.php?artist_type=2">艺术机构</a>
								</li>
								<li><a href="/brand.php?artist_type=3">艺术收藏家</a>
								</li>
							</ul>
						</li>
						<li class="nav_sep">/</li>
						<li class="navs">
							<a href="/auction.php?act=index" class="nav_a">在线拍卖</a>
							<ul id="subNav_4" class="sub_nav subnav_auction">
								<li><a href="auction_tobe.html">预<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>展</a>
								</li>
								<li><a href="auction_ing.html">正在拍卖</a>
								</li>
								<li><a href="auction_before.html">往期拍卖</a>
								</li>
							</ul>
						</li>
						<li class="nav_sep">/</li>
						<li class="navs">
							<a href="artinfo.html" class="nav_a">艺术情报</a>
							<ul id="subNav_5" class="sub_nav subnav_artinfo">
								<li><a href="artinfo_superinfo.html">重磅快讯</a>
								</li>
								<li><a href="artinfo_arteye.html">艺<span>&nbsp;&nbsp;</span>术<span>&nbsp;&nbsp;</span>眼</a>
								</li>
								<li><a href="artinfo_artdecode.html">艺术解码</a>
								</li>
							</ul>
						</li>

						<li class="clear"></li>

					</ul>
					<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js')); ?>	
					<?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
				</div>
			</div>
			<div id="rmenu"></div>