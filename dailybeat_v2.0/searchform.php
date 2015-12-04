<?php
	$ph = "GOLD ALL IN MY SEARCH";
?>
<form action="<?php echo home_url(); ?>" id="search_form" class="navbar-form navbar-right" method="get">
	<input type="text" class="search_input" id="s" name="s" autocomplete="off" 
		value="<?php echo $ph; ?>"
		onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
		onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
		placeholder="<?php echo $ph; ?>"/>
	<input type="submit" value="" id="search_submit" class="search_submit button normal" />
</form>
	

