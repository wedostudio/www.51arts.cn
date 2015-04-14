<div id="topleft">
<?php if ($this->_var['user_info']): ?>
	<span id="welcome">您好，<font class="f4_b"><?php echo $this->_var['user_info']['username']; ?></font>，欢迎来到多元主义！</span>
	<a href="user.php"><?php echo $this->_var['lang']['user_center']; ?></a>
	<span>/</span>
	<a href="user.php?act=logout"><?php echo $this->_var['lang']['user_logout']; ?></a>
<?php else: ?>
	<span id="welcome">您好，欢迎来到多元主义！</span>
	<a href="user.php" id="login_top">登录</a>
	<span>/</span>
	<a href="user.php?act=register">注册</a>
<?php endif; ?>
</div>