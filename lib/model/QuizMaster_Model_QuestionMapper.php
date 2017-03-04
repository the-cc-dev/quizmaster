<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper {

    private $_table;

    public function __construct()
    {
        parent::__construct();

        $this->_table = $this->_prefix . "question";
    }

    public function delete($id)
    {
        $this->_wpdb->delete($this->_table, array('id' => $id), '%d');
    }

    public function deleteByQuizId($id)
    {
        $this->_wpdb->delete($this->_table, array('quiz_id' => $id), '%d');
    }

    public function updateSort($id, $sort)
    {
        $this->_wpdb->update(
            $this->_table,
            array(
                'sort' => $sort
            ),
            array('id' => $id),
            array('%d'),
            array('%d'));
    }

    public function setOnlineOff($questionId)
    {
        return $this->_wpdb->update($this->_tableQuestion, array('online' => 0), array('id' => $questionId), null,
            array('%d'));
    }

    public function getQuizId($questionId)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT quiz_id FROM {$this->_tableQuestion} WHERE id = %d",
            $questionId));
    }

    public function getMaxSort($quizId)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare(
            "SELECT MAX(sort) AS max_sort FROM {$this->_tableQuestion} WHERE quiz_id = %d AND online = 1", $quizId));
    }

    public function getSortByQuestionId($questionId)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT sort FROM {$this->_tableQuestion} WHERE id = %d",
            $questionId));
    }

    public function save(QuizMaster_Model_Question $question) {

    }

    public function fetch( $id ) {
      $model = new QuizMaster_Model_Question( $id );
      return $model;
    }

    /**
     * @param $id
     * @return QuizMaster_Model_Question|QuizMaster_Model_Question[]|null
     */
    public function fetchById($id) {
      $ids = array_map('intval', (array)$id);
      $a = array();

      foreach ($results as $row) {
        $a[] = new QuizMaster_Model_Question($row);
      }

      return is_array($id) ? $a : (isset($a[0]) ? $a[0] : null);
    }

    /*
     * Return list of questions associated with quiz
     * Used by ajaxLoadQuestionsSort, expect return to be array of fields (not question objects)
     */
    public function fetchAllList($quizId, $list, $sort = false) {

      $results = array();
      $quizPost = get_post( $quizId );
      $quizQuestions = get_field( 'quiz_questions', $quizId );
      foreach( $quizQuestions as $qq ) {

        $quizQuestionID = $qq['quiz_question'];
        $question = new QuizMaster_Model_Question( $quizQuestionID );

      }
      return $results;

    }

    /**
     * @param $quizId
     * @param bool $rand
     * @param int $max
     *
     * @return QuizMaster_Model_Question[]
     */
    public function fetchAll($quizId, $rand = false, $max = 0) {

        $a = array();

        $quizPost = get_post( $quizId );
        $quizQuestions = get_field( QUIZMASTER_QUESTION_SELECTOR_FIELD, $quizId );

        if( empty($quizQuestions)) {
          return false;
        }

        foreach( $quizQuestions as $qq ) {

          $quizQuestionID = $qq[ QUIZMASTER_QUESTION_REFERENCE_FIELD ];
          $q = new QuizMaster_Model_Question( $quizQuestionID );
          $a[] = $q;
          
        }

        return $a;
    }

}
