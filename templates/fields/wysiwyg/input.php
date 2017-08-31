<label class="qm-field-label"><?php print $field['label']; ?></label>

<div class="qm-wysiwyg">
	<?php
		$args = array();

		wp_editor( 'This is the default text!', $field['key'], $args );
	?>
</div>
