<?php

foreach( $field->definition['sub_fields'] as $subfield ) {

	print $fieldCtr->renderField( $subfield, false );

}
