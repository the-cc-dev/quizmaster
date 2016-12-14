<?php

class QuizMaster_Model_Category extends QuizMaster_Model_Model
{

    const CATEGORY_TYPE_QUESTION = 'QUESTION';
    const CATEGORY_TYPE_QUIZ = 'QUIZ';

    protected $_categoryId = 0;
    protected $_categoryName = '';
    protected $_type = QuizMaster_Model_Category::CATEGORY_TYPE_QUESTION;


    public function setCategoryId($_categoryId)
    {
        $this->_categoryId = (int)$_categoryId;

        return $this;
    }

    public function getCategoryId()
    {
        return $this->_categoryId;
    }

    public function setCategoryName($_categoryName)
    {
        $this->_categoryName = (string)$_categoryName;

        return $this;
    }

    public function getCategoryName()
    {
        return $this->_categoryName;
    }

    public function setType($_type)
    {
        $this->_type = (string)$_type;

        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }
}