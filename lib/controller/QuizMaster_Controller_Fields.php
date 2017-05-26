<?php

class QuizMaster_Controller_Fields {

  public function __construct() {

  }

  public function loadFieldGroups() {

    foreach( $this->fieldGroups() as $fieldGroupKey ) {
      $fieldGroup = $this->loadFieldGroup( $fieldGroupKey );
      acf_add_local_field_group( $fieldGroup );
    }

  }

  public function loadFieldGroup( $fieldGroupKey ) {

    include( QUIZMASTER_PATH . '/acf/fieldgroups/' . $fieldGroupKey . '.php' );

    // $fieldGroup loaded from file include
    $allFields = array();
    $baseFields = $fieldGroup['fields'];
    $fieldGroup['fields'] = array(); // reset array of fields

    foreach( $baseFields as $baseField ) {
      $fieldGroup['fields'][] = $this->loadField( $baseField );

			// enable extensions to add fields
			$addFields = apply_filters('quizmaster_add_fields_after_' . $baseField['name'], array() );
			if( !empty( $addFields )) {
				foreach( $addFields as $field ) {
					$fieldGroup['fields'][] = $field;
				}
			}

    }

    $fieldGroup = apply_filters( 'quizmaster_add_fieldgroup', $fieldGroup );

    return $fieldGroup;

  }

  public function loadField( $baseField ) {
    return apply_filters('quizmaster_add_field', $baseField, $baseField['name'] );
  }

  public function fieldGroups() {
    return array(
      'question',
      'quiz',
      'score',
      'email',
      'settings'
    );
  }



}
