<?php
	$ph = "GOLD ALL IN MY SEARCH";
?>
<form action="<?php echo home_url(); ?>" class="navbar-form navbar-right" method="get">
	<input type="text" id="s" name="s" autocomplete="off" class="search-box"
		value="<?php echo $ph; ?>"
		onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
		onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
		placeholder="<?php echo $ph; ?>"/>
	<div class="search-icon h-icon"><i class="fa fa-search fw"></i></div>
	<input type="submit" style="position: absolute; left: -9999px"/>
</form>
	

