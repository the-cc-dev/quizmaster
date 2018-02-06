<div class="repeater">

	<div data-repeater-list="<?php print $field->key; ?>">
		<div data-repeater-item>
			<?php

				foreach( $field->definition['sub_fields'] as $subfieldDefinition ) {

					$subfield = QuizMaster_Field::loadFieldByDefinition( $subfieldDefinition );
					print $fieldCtr->renderField( $subfield, false );

				}

			?>
		  <input data-repeater-delete type="button" value="Delete"/>
		</div>
	</div>

	<input data-repeater-create type="button" value="Add"/>

	</div>
