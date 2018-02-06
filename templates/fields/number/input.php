<label class="qm-field-label" for="<?php print $field->key; ?>">
	<?php print $field->label; ?>
</label>

<div class="qm-field-number-input">
	<input type="number" name="<?php print $field->key; ?>" value="<?php $field->renderValue(); ?>" />
</div>
