<?php

class QuizMaster_Controller_Fields {

	private $fieldGroups = array();
	private $activeFieldGroup = false;
	private $activeTab = false;

  public function __construct() {
  }

	public function init() {

		global $quizmaster;
		$fieldGroups = $this->loadFieldGroups();

		$quizmaster->fields = new QuizMaster_Controller_Fields();
		$quizmaster->fields->setFieldGroups( $fieldGroups );

	}

	public function setFieldGroups( $fieldGroups ) {
		$this->fieldGroups = $fieldGroups;
	}

	public function getFieldGroups() {
		return $this->fieldGroups;
	}

	public function setActiveFieldGroup( $key ) {
		 $fgs = $this->getFieldGroups();

		 foreach( $fgs as $fg ) {
			 if( $fg['key'] == $key ) {
				 $this->activeFieldGroup = $fg;
			 }
		 }
	}

	public function setActiveTab( $tab ) {
		$this->activeTab = $tab;
	}

	public function renderFieldGroup() {
		$fg = $this->activeFieldGroup;
		$render = '';

		// render tabs
		$render .= $this->renderTabs( $fg );

		// render fields
		$render .= $this->renderFieldFormWrapOpen();


		foreach( $fg['fields'] as $field ) {

			if( $field['type'] == 'tab' ) {
				$this->setActiveTab( $field );
				continue;
			}

			$render .= $this->renderField( $field );

		}

		$render .= $this->renderFieldFormWrapClose();

		return $render;
	}

	public function renderFieldFormWrapOpen() {
		return '<div class="qm-field-form">';
	}

	public function renderFieldFormWrapClose() {
		return '</div>';
	}

	public function renderTabs( $fieldGroup ) {

		$content = '';
		$tabs = array();

		foreach( $fieldGroup['fields'] as $field ) {

			if( $field['type'] == 'tab' ) {
				$tabs[] = $field;
			}

		}

		$content .= quizmaster_parse_template( 'fields/tabs.php', array(
			'tabs' => $tabs,
		));

		return $content;

	}

	public function renderField( $field, $template = false ) {
		$content = '';

		$content .= quizmaster_parse_template( 'fields/field-wrap-before.php', array(
			'field' => $field,
			'tab' => $this->activeTab,
		));

		if( !$template ) {
			$content .= quizmaster_parse_template( 'fields/' . $field['type'] . '/input.php', array(
				'field' => $field,
			));
		}

		$content .= quizmaster_parse_template( 'fields/field-wrap-after.php', array(
			'field' => $field,
		));

		return $content;
	}

  public function loadFieldGroups() {

		$fg = array();

    foreach( $this->fieldGroups() as $fieldGroupKey ) {
      $fg[ $fieldGroupKey ] = $this->loadFieldGroup( $fieldGroupKey );
    }

		return $fg;

  }

	public function registerFieldGroup( $fieldGroup ) {

		global $quizmaster;
		//var_dump($fieldGroup);
		//var_dump($quizmaster); die();
		return;
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
				$addFields = apply_filters('quizmaster_add_fields_after_' . $baseField['name'], array() );
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
		if( $baseField['type'] != 'tab' ) {
			$name = $baseField['name'];
		} else {
			$name = 'tab';
		}

		return apply_filters('quizmaster_add_field', $baseField, $name );

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
