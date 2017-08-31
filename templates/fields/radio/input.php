<label class="qm-field-label"><?php print $field['label']; ?></label>
<ul>
	<?php foreach( $field['choices'] as $value => $choice ) : ?>
		<li>
			<label title="This is the title description.">
				<input type="radio" id="<?php print $field['key'] ?>" name="<?php print $field['key'] ?>[]" value="<?php print $value; ?>">
					<?php print $choice; ?>
				</input>
			</label>
		</li>
	<?php endforeach; ?>
</ul>

<script>

jQuery(document).ready(function( $ ) {

	$('.qm-field-radio label').click( function() {
		$('.qm-field-radio input').prop('checked', false)
		$(this).find('input').prop('checked', true)
		$('.qm-field-radio label').removeClass('selected')
		$(this).addClass('selected')
	});

});

</script>
