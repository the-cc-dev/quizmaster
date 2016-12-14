<?php

class QuizMaster_Model_Email extends QuizMaster_Model_Model
{
    protected $_to = '';
    protected $_toUser = false;
    protected $_toForm = false;
    protected $_from = '';
    protected $_subject = '';
    protected $_html = false;
    protected $_message = '';

    public function setTo($_to)
    {
        $this->_to = (string)$_to;

        return $this;
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function setToUser($_toUser)
    {
        $this->_toUser = (bool)$_toUser;

        return $this;
    }

    public function isToUser()
    {
        return $this->_toUser;
    }

    public function setToForm($_toForm)
    {
        $this->_toForm = (bool)$_toForm;

        return $this;
    }

    public function isToForm()
    {
        return $this->_toForm;
    }

    public function setFrom($_from)
    {
        $this->_from = (string)$_from;

        return $this;
    }

    public function getFrom()
    {
        return $this->_from;
    }

    public function setSubject($_subject)
    {
        $this->_subject = (string)$_subject;

        return $this;
    }

    public function getSubject()
    {
        return $this->_subject;
    }

    public function setHtml($_html)
    {
        $this->_html = (bool)$_html;

        return $this;
    }

    public function isHtml()
    {
        return $this->_html;
    }

    public function setMessage($_message)
    {
        $this->_message = (string)$_message;

        return $this;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public static function getDefault($adminEmail)
    {
        $email = new QuizMaster_Model_Email();

        if ($adminEmail) {
            $email->setSubject(__('QuizMaster: One user completed a quiz', 'quizmaster'));
            $email->setMessage(__('QuizMaster

The user "$username" has completed "$quizname" the quiz.

Points: $points
Result: $result

', 'quizmaster'));
        } else {
            $email->setSubject(__('QuizMaster: One user completed a quiz', 'quizmaster'));
            $email->setMessage(__('QuizMaster

You have completed the quiz "$quizname".

Points: $points
Result: $result

', 'quizmaster'));
        }

        return $email;
    }
}