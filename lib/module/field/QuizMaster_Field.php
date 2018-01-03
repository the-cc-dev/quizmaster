<?php

class QuizMaster_Field {

	public function save() {

	}

	public function value( $postId, $key ) {

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
		$fieldGroupKey = str_replace( 'quizmaster_', '', $postType );
		return $quizmaster->fields->getFieldGroup( $fieldGroupKey );

	}

	public function getFieldObject( $field, $postId ) {

	}

}
