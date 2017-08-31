<h2 class="qm-field-label"><?php print $field['label']; ?></h2>
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

<style>

.qm-field-radio label {
	padding: 10px 8px;
	background: #e6e6e6;
	display: inline-block;
}

.qm-field-radio label:hover {
	border-bottom: solid 4px #d6d6d6;
}

.qm-field-radio label.selected {
	border-bottom: solid 4px #4EA0D4;
	margin-bottom: 4px;
	background: #d8d8d8;
	font-weight: bold;
}

	.qm-field-radio ul {
		padding: 0;
		margin: 0;
		overflow: hidden;
	}

	.qm-field-radio li {
		float: left;
		display: inline-block;
		padding: 0;
		margin: 0 10px 12px 0;
	}

	.qm-field-radio input {
		position: absolute;
		opacity: 0;
		height: 0;
		width: 0;
		overflow: hidden;
	}

	.qm-field-form .qm-field-wrap h2.qm-field-label {
		padding: 0 !important;
		margin: 0 0 10px 0 !important;
		font-weight: bold;
		font-size: 12px !important;
	}

</style>
