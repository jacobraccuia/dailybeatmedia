<?php $text = ( 'search' ); ?>
<form method="get" action="<?php echo site_url(); ?>">
<span><input id="s" class="text_input search rounded" type="text" onblur="if (this.value == '') {this.value = 'Search . . .';}" onfocus="if (this.value == 'Search . . .') {this.value = '';}" name="s" placeholder="Search by Title, Keyword, Artist..." value=""/></span>
</form>