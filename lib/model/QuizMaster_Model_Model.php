<?php

class QuizMaster_Model_Model {

    /**
     * @var QuizMaster_Model_QuizMapper
     */
    protected $_mapper = null;

    public function __construct($data = null) {
      if( is_array( $data )) {
        $this->setModelData( $data );
      } else {
        $this->setModelByID( $data );
      }

    }

    public function setModelByID( $id ) {
      $fields = get_fields( $id );
      if( !empty( $fields )) {
        $fields = $this->stripFieldPrefixes( $fields );
      }
      $this->setId( $id );
      $fields['id'] = $id;
      $fields['post'] = get_post( $id );
      $fields = $this->processFieldsDuringModelSet( $fields );
      $this->setModelData( $fields );
      $this->afterSetModel();
    }

    public function setPost( $post ) {
      $this->_post = $post;
    }

    public function getDate() {
      return get_the_date( 'Y-m-d H:i:s', $this->getId() );
    }

    public function getPost() {
      return $this->_post;
    }

    public function setId( $id ) {
      $this->_id = $id;
    }

    public function getPermalink() {
      return get_permalink( $this->_id );
    }

    /*
     * Override to alter the fields before setting model data
     */
    public function processFieldsDuringModelSet( $fields ) {
      return $fields;
    }

    /*
     * Override to alter the model after data set automatically
     */
    public function afterSetModel() {
      return;
    }

    public function setModelData($array) {
      if ($array != null) {
        $n = explode(' ', implode('', array_map('ucfirst', explode('_', implode(' _', array_keys($array))))));
        $a = array_combine($n, $array);
        foreach ($a as $k => $v) {
          $this->{'set' . $k}($v);
        }
      }
    }

    public function __call($name, $args)
    {
    }

    /**
     *
     * @return QuizMaster_Model_QuizMapper
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {
            $this->_mapper = new QuizMaster_Model_QuizMapper();
        }

        return $this->_mapper;
    }

    /**
     * @param QuizMaster_Model_QuizMapper $mapper
     * @return QuizMaster_Model_Model
     */
    public function setMapper($mapper) {
        $this->_mapper = $mapper;
        return $this;
    }

    public function stripFieldPrefixes( $fields ) {

      $fieldPrefix = $this->getFieldPrefix();

      foreach( $fields as $key => $val ) {
        $newKey = str_replace( $fieldPrefix, '', $key );
        $fields[$newKey] = $val;
        unset( $fields[$key] );
      }
      return $fields;
    }

}
