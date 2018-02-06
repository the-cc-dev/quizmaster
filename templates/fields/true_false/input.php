<label>
	<input type="checkbox" name="<?php print $field->key; ?>" value='1' <?php if( $field->value == 1 ) { print 'checked'; } ?> />
	<?php print $field->label; ?>
</label>
