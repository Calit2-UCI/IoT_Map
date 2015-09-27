<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-26 01:25:13 Pacific Daylight Time */ ?>

<div> <a href="http://uci.edu/"> <img src="https://communications.uci.edu/img/graphic-identity/seal/seal-blue.png" alt="UCI" !--style="width:165px;height:63px;"-- align="right" > </a> </div>
<!--big: http://bme240.eng.uci.edu/students/09s/ysantoro/Images/UCIrvine.gif-->
<!--median: http://www.calit2.net/images/articles/UCILogo.jpg-->
<!--small: https://upload.wikimedia.org/wikipedia/en/archive/a/ad/20150908000018%21Ucirvine_logo.png-->
<!--seal: https://communications.uci.edu/img/graphic-identity/seal/seal-blue.png-->
<ul>
<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
<li<?php if ($this->_vars['key'] > 0):  if (!($this->_vars['key'] % 4)): ?> class="clr"<?php endif;  endif; ?>>
	<?php echo $this->_vars['item']['value']; ?>

	<?php if (! empty ( $this->_vars['item']['sub'] )): ?>
	<ul>
	<?php if (is_array($this->_vars['item']['sub']) and count((array)$this->_vars['item']['sub'])): foreach ((array)$this->_vars['item']['sub'] as $this->_vars['subitem']): ?><li><a id="footer_<?php echo $this->_vars['item']['gid']; ?>
_<?php echo $this->_vars['subitem']['gid']; ?>
" href="<?php echo $this->_vars['subitem']['link']; ?>
"><?php echo $this->_vars['subitem']['value']; ?>
</a></li>
	<?php endforeach; endif; ?>	
	</ul>
	<?php endif; ?>
	
</li>
<?php endforeach; endif; ?>
</ul>
