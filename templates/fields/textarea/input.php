<label class="qm-field-label" for="<?php print $field->key; ?>">

	<?php print $field->label; ?>
</label>

<div class="qm-field-textarea-input">
	<textarea name="<?php print $field->key; ?>"><?php $field->renderValue(); ?></textarea>
</div>
