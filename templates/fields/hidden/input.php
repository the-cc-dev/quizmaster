<?php
if(  is_array( $field['value'] )) {
	$value = json_encode( $field['value'] );
} else {
	$value = $field['value'];
}

?>

<div class="qm-field-hidden-input">
	<input type="hidden" name="<?php print $field['key']; ?>" value="<?php print $value; ?>" />
</div>
