<label class="qm-field-label"><?php print $field->label; ?></label>

<div class="qm-wysiwyg">
	<?php
		$args = array();

		wp_editor( $field->value, $field->key, $args );
	?>
</div>
