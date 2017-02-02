<?php

class QuizMaster_Model_Email extends QuizMaster_Model_Model
{
    protected $_id = 0;

    protected $_from = '';
    protected $_html = false;
    protected $_message = '';
    protected $_headers = '';

    protected $_key = '';
    protected $_enabled = '';
    protected $_trigger = '';
    protected $_recipients = '';
    protected $_subject = '';
    protected $_type = 'html';

    public function getFieldPrefix() {
      return 'qm_email_';
    }

    public function setId($_id) {
      $this->_id = (int)$_id;
      return $this;
    }

    public function getId() {
      return $this->_id;
    }

    public function getHeaders() {
      $headers = 'From: ' . $this->getFrom();
      return $headers;
    }

    public function setFrom($_from) {
      $this->_from = (string)$_from;
      return $this;
    }

    public function getFrom() {
      return $this->_from;
    }

    public function getSubject() {
      return $this->_subject;
    }

    public function setHtml($_html) {
      $this->_html = (bool)$_html;
      return $this;
    }

    public function isHtml() {
      return $this->_html;
    }

    public function setMessage($_message) {
      $this->_message = (string)$_message;
      return $this;
    }

    public function setKey( $_key ) {
      $this->_key = (string)$_key;
      return $this;
    }

    public function setEnabled( $_enabled ) {
      $this->_enabled = (bool)$_enabled;
      return $this;
    }

    public function setTrigger( $_trigger ) {
      $this->_trigger = (string)$_trigger;
      return $this;
    }

    public function setRecipients( $_recipients ) {
      $this->_recipients = (string)$_recipients;
      return $this;
    }

    public function getRecipients() {
      return $this->_recipients;
    }

    public function setSubject( $_subject ) {
      $this->_subject = (string)$_subject;
      return $this;
    }

    public function setType( $_type ) {
      $this->_type = (string)$_type;
      return $this;
    }

    public function getMessage() {
      return $this->_message;
    }

    /*
     * Override to alter the fields before setting model data
     */
    public function processFieldsDuringModelSet( $fields ) {
      $fields['email_key'] = $fields['qm_email_key'];
      return $fields;
    }

}
