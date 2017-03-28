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

    include_once( QUIZMASTER_PATH . '/acf/fieldgroups/' . $fieldGroupKey . '.php' );
    // $fieldGroup loaded from file include

    $allFields = array();
    $baseFields = $fieldGroup['fields'];
    $fieldGroup['fields'] = array(); // reset array of fields

    foreach( $baseFields as $baseField ) {
      $fieldGroup['fields'][] = $this->loadField( $baseField );
    }

    $fieldGroup = apply_filters('quizmaster_add_fieldgroup', $fieldGroup );

    return $fieldGroup;

  }

  public function loadField( $baseField ) {
    return apply_filters('quizmaster_add_field', $baseField );
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
