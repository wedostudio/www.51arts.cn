						<div id="ft_nav">
							<ul>
							<?php if ($this->_var['helps']): ?>
								<?php $_from = $this->_var['helps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help_cat');if (count($_from)):
    foreach ($_from AS $this->_var['help_cat']):
?>
								<li>
									<ul class="ft_sec_nav">
										<li class="ft_title"><a href="<?php echo $this->_var['help_cat']['cat_id']; ?>"><?php echo $this->_var['help_cat']['cat_name']; ?></a>
										</li>
										<?php $_from = $this->_var['help_cat']['article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
										<li><a href="#">购买流程</a></li>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</ul>
								</li>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							<?php endif; ?>
							</ul>
						</div>