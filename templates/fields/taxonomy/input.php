<label class="qm-field-label" for="<?php print $field->key; ?>"><?php print $field->label; ?></label>

<?php

$taxonomy = $field->definition['taxonomy'];
$terms = get_terms( $taxonomy, array( 'hide_empty' => false ));

?>

<div class="qm-field-taxonomy-wrap">
	<select name="<?php print $field->key; ?>">
		<?php foreach( $terms as $term ) : ?>

			<option value="<?php print $term->term_id; ?>"<?php if( $term->term_id == $field->value ) { print 'selected=selected'; } ?>><?php print $term->name; ?></option>

		<?php endforeach; ?>
	</select>
</div>
