<?php

class QuizMaster_Model_Model
{

    /**
     * @var QuizMaster_Model_QuizMapper
     */
    protected $_mapper = null;

    public function __construct($array = null) {
      $this->setModelData($array);
    }

    public function setModelData($array) {
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
}
