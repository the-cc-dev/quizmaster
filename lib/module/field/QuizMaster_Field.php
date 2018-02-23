<?php

class QuizMaster_Field {

	public function save() {

	}

	public static function loadFieldByDefinition( $fieldDefinition ) {
		$field = self::getFieldClassByType( $fieldDefinition['type'] );
		$field->setType( $fieldDefinition['type'] );
		$field->setDefinition( $fieldDefinition );
		$field->setKey( $fieldDefinition['key'] );
		$field->setLabel( $fieldDefinition['label'] );

		// set default value
		$field->setDefaultValue( false );
		if( array_key_exists('default_value', $fieldDefinition)) {
			$field->setDefaultValue( $fieldDefinition['default_value'] );
		}

		$field->setValue( null );
		return $field;
	}

	public static function loadFieldInstanceByDefinition( $fieldDefinition, $postId ) {
		$field = self::loadFieldByDefinition( $fieldDefinition );
		$field->setPostId( $postId );
		$field->loadValue( $postId );
		return $field;
	}

	public function renderValue() {

		print $this->getValue();

	}

	public function getValue() {

		if( isset( $this->value ) && $this->value !== false ) {
			if( is_array( $this->value )) {
				return htmlentities( json_encode( $this->value ) );
			} else {
				return $this->value;
			}
		} elseif( isset( $this->default ) && $this->default !== false ) {
			return $this->default;
		}

	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function setLabel( $label ) {
		$this->label = $label;
	}

	public function setDefaultValue( $defaultValue ) {
		$this->default = $defaultValue;
	}

	public function setPostId( $postId ) {
		$this->postId = $postId;
	}

	public function setDefinition( $fieldDefinition ) {
		$this->definition = $fieldDefinition;
	}

	public function setKey( $fieldKey ) {
		$this->key = $fieldKey;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function loadValue( $postId ) {
		$value = $this->value( $postId, $this->key );
		$this->setValue( $value );
	}

	public static function value( $postId, $key ) {
		return get_post_meta( $postId, $key, 1 );
	}

	public static function getFieldValues( $postId, $key ) {

		$values = array();
		$fieldObj = new QuizMaster_Field;
		$fieldGroup = $fieldObj->loadFieldGroupByPostId( $postId );
		if( $key ) {

			// return specific meta key value for given post
			foreach( $fieldGroup['fields'] as $fieldArray ) {
				if( $fieldArray['key'] == $key ) {

					$fieldClass = self::getFieldClassByType( $fieldArray['type'] );
					$values = $fieldClass->value( $postId, $fieldArray['key'] );

				}
			}

		} else {

			// return all field values for given post
			foreach( $fieldGroup['fields'] as $fieldArray ) {

				if( $fieldArray['type'] == 'tab' || $fieldArray['type'] == 'repeater' ) {
					continue;
				}

				$fieldClass = self::getFieldClassByType( $fieldArray['type'] );
				$values[ $fieldArray['key'] ] = $fieldClass->value( $postId, $fieldArray['key'] );

			}

		}

		return $values;

	}

	public static function getFieldClassByType( $type ) {
		switch( $type ) {
			case 'hidden':
				return new QuizMaster_Field_Hidden;
			case 'number':
				return new QuizMaster_Field_Number;
			case 'radio':
				return new QuizMaster_Field_Radio;
			case 'relationship':
				return new QuizMaster_Field_Relationship;
			case 'repeater':
				return new QuizMaster_Field_Repeater;
			case 'taxonomy':
				return new QuizMaster_Field_Taxonomy;
			case 'tab':
				return new QuizMaster_Field_Tab;
			case 'text':
				return new QuizMaster_Field_Text;
			case 'textarea':
				return new QuizMaster_Field_Textarea;
			case 'true_false':
				return new QuizMaster_Field_TrueFalse;
			case 'wysiwyg':
				return new QuizMaster_Field_Wysiwyg;
		}
	}

	public static function loadFieldData( $postId, $key ) {


	}


	public function loadFieldGroupByPostId( $postId ) {

		global $quizmaster;
		$postType = get_post_type( $postId );

		$fieldGroupKey = str_replace( 'quizmaster_', '', $postType );
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
