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
      $fields['id'] = $id;
      $fields = $this->stripFieldPrefixes( $fields );
      $fields = $this->processFieldsDuringModelSet( $fields );
      $this->setModelData( $fields );
    }

    /*
     * Override to alter the fields before setting model data
     */
    public function processFieldsDuringModelSet( $fields ) {
      return $fields;
    }

    public function setModelData($array) {

      /*
      print '<pre>';
      var_dump( $array );
      print '</pre>';
      */

      if ($array != null) {
        $n = explode(' ', implode('', array_map('ucfirst', explode('_', implode(' _', array_keys($array))))));
        $a = array_combine($n, $array);
        foreach ($a as $k => $v) {

          /*
          print '<pre>';
          var_dump( $k );
          var_dump( $v );
          print '</pre>';
          die();
          */

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
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }

    public function stripFieldPrefixes( $fields ) {
      $fieldPrefix = $this->getFieldPrefix();
      foreach( $fields as $key => $val ) {
        $key = str_replace( $fieldPrefix, '', $key );
        $fields[$key] = $val;
      }
      return $fields;
    }

}
