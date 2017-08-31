<label class="qm-field-label" for="<?php print $field['key']; ?>">
	<?php print $field['label']; ?>
</label>

<div class="qm-field-number-input">
	<input type="number" name="<?php print $field['key']; ?>" />
</div>

<style>

.qm-field-wrap .qm-field-label {
	padding: 0 !important;
	margin: 0 0 10px 0 !important;
	font-weight: bold;
	font-size: 12px !important;
}

</style>
