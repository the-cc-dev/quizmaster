<?php

class QuizMaster_Field {

	public function save() {

	}

	public static function value( $postId, $key ) {

		$value = '';
		$fieldObj = new QuizMaster_Field;
		$fieldGroup = $fieldObj->loadFieldGroupByPostId( $postId );
		if( $key ) {

			// return specific meta key value for given post
			foreach( $fieldGroup['fields'] as $fieldArray ) {
				if( $fieldArray['key'] == $key ) {

					$fieldClass = $fieldObj->getFieldClassByType( $type );
					$value = $fieldClass->value( $postId );

				}
			}

		} else {

			// return all field values for given post
			foreach( $fieldGroup['fields'] as $fieldArray ) {

				$fieldClass = $fieldObj->getFieldClassByType( $type );
				$value[] = $fieldClass->value( $postId );

			}

		}

		return $value;

	}

	public function getFieldClassByType( $type ) {
		switch( $type ) {
			case 'relationship':
				return new QuizMaster_Field_Relationship;
		}
	}

	public static function loadFieldData( $postId, $key ) {


	}


	public function loadFieldGroupByPostId( $postId ) {

		global $quizmaster;
		$postType = get_post_type( $postId );

var_dump( $postId  );
		var_dump( $postType );

		$fieldGroupKey = str_replace( 'quizmaster_', '', $postType );

		var_dump( $fieldGroupKey );

		return $quizmaster->fields->getFieldGroup( $fieldGroupKey );

	}

	public function getFieldObject( $field, $postId ) {

	}

	public function loadFieldGroup( $fieldGroupKey ) {

    include( QUIZMASTER_PATH . '/fields/fieldgroups/' . $fieldGroupKey . '.php' );

    // $fieldGroup loaded from file include
    $allFields = array();
    $baseFields = $fieldGroup['fields'];
    $fieldGroup['fields'] = array(); // reset array of fields

    foreach( $baseFields as $baseField ) {

      $fieldGroup['fields'][] = $this->loadField( $baseField );

			// enable extensions to add fields
			if( $baseField['type'] != 'tab' ) {
				$addFields = apply_filters('quizmaster_add_fields_after_' . $baseField['key'], array() );
			}

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

		// tabs have no name param
		$key = $baseField['key'];
		return apply_filters('quizmaster_add_field', $baseField, $key );

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
