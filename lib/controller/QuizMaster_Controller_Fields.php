<?php

class QuizMaster_Controller_Fields {

	private $fieldGroups = array();
	private $activeFieldGroup = false;
	private $activeTab = false;
	private $openTab = false;

  public function __construct() {

		if( array_key_exists( 'qm-tab', $_GET )) {
			$this->openTab = $_GET['qm-tab'];
		}


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

	public function renderTabOpen() {
		return '<div class="qm-tab">';
	}

	public function renderTabClose() {
		return '</div>';
	}

	public function renderFieldGroup() {
		$fg = $this->activeFieldGroup;
		$render = '';

		// open container
		$render .= '<div class="quizmaster-container fullwidth"><div class="quizmaster-row"><div class="quizmaster-col-3">';

		// render tabs
		$render .= $this->renderTabs( $fg );

		$render .= '</div>'; // close col
		$render .= '<div class="quizmaster-col-9">';

		// render fields
		$render .= $this->renderFieldFormWrapOpen();

		// loop through fields
		foreach( $fg['fields'] as $field ) {

			if( $field['type'] == 'tab' ) {

				if( !empty( $this->activeTab )) {
					$render .= $this->renderTabClose();
				}

				$render .= $this->renderTabOpen();
				$this->setActiveTab( $field );

				continue;

			}

			$render .= $this->renderField( $field );

		}

		// close tabs
		$render .= $this->renderTabClose();

		$render .= $this->renderFieldFormWrapClose();

		// close col, container
		$render .= '</div></div></div>';

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
			'openTab' => $this->openTab,
		));

		return $content;

	}

	public function renderField( $field, $template = false ) {


		//var_dump($field);

		$content = '';

		// load value
		global $post_id;
		$field['value'] = get_post_meta( $post_id, $field['key'], true );

		$content .= quizmaster_parse_template( 'fields/field-wrap-before.php', array(
			'field' => $field,
			'tab' => $this->activeTab,
		));

		if( !$template ) {
			$content .= quizmaster_parse_template( 'fields/' . $field['type'] . '/input.php', array(
				'field' => $field,
				'fieldCtr' => $this,
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
