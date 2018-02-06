<label class="qm-field-label"><?php print $field->label; ?></label>
<ul>
	<?php foreach( $field->definition['choices'] as $value => $choice ) : ?>
		<li>
			<label title="<?php if( array_key_exists( 'instructions', $field->definition )) { print $field->definition['instructions']; } ?>">
				<input<?php if( $field->value == $value ) { print ' checked'; } ?> type="radio" name="<?php print $field->key ?>" value="<?php print $value; ?>">
					<?php print $choice; ?>
				</input>
			</label>
		</li>
	<?php endforeach; ?>
</ul>

<script>

jQuery(document).ready(function( $ ) {

	// init
	$('input[name=<?php print $field->key; ?>]:checked', '.qm-field-radio').closest('label').addClass('selected')

	// selection
	$('.qm-field-radio label').click( function() {
		$('.qm-field-radio input').prop('checked', false)
		$(this).find('input').prop('checked', true)
		$('.qm-field-radio label').removeClass('selected')
		$(this).addClass('selected')
	});

});

</script>
