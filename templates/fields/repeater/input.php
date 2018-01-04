<?php

foreach( $field->definition['sub_fields'] as $subfieldDefinition ) {

	$subfield = QuizMaster_Field::loadFieldByDefinition( $subfieldDefinition );
	print $fieldCtr->renderField( $subfield, false );

}
