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

      print '<pre>';
      var_dump( 113 );
      print '</pre>';

        $a = array();

        $quizPost = get_post( $quizId );
        $quizQuestions = get_field( 'quiz_questions', $quizId );

        if( empty($quizQuestions)) {
          return false;
        }

        foreach( $quizQuestions as $qq ) {
          $quizQuestionID = $qq['quiz_question'];
          $fields = get_fields( $quizQuestionID );

          // set answer data
          switch( $fields[ QUIZMASTER_ANSWER_TYPE_FIELD ] ) {
            case 'single':
              $answerData = $this->loadAnswerDataSingleChoice( $fields );
              break;
            case 'multiple':
              $answerData = $this->loadAnswerDataMultipleChoice( $fields );
              break;
            case 'free_answer':
              $answerData = $this->loadAnswerDataFreeChoice( $fields );
              break;
            case 'sort_answer':
              $answerData = $this->loadAnswerDataSortingChoice( $fields );
              break;
            case 'matrix_sort_answer':
              $answerData = $this->loadAnswerDataMatrixSortingAnswer( $fields );
              break;
            case 'cloze_answer':
              $answerData = $this->loadAnswerDataCloze( $fields );
              break;
            case 'assessment_answer':
              $answerData = $this->loadAnswerDataAssessment( $fields );
              break;
          }

          $model = new QuizMaster_Model_Question( $quizQuestionID );
          $model->setAnswerData( $answerData );

          $a[] = $model;
        }

        return $a;
    }

    // MOVE TO QUESTION MODEL
    public function loadAnswerDataAssessment( $fields ) {
      $acfAnswerData['answer'] = $fields['qmqe_assessment_answers'];
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataCloze( $fields ) {
      $acfAnswerData['answer'] = $fields['qmqe_cloze_answers'];
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataFreeChoice( $fields ) {
      $acfAnswerData = array(
        'answer' => $fields['qmqe_free_choice_answers']
      );
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataMatrixSortingAnswer( $fields ) {
      $acfAnswerData = $fields['qmqe_matrix_sorting_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $acfAnswer['answer'] = $acfAnswer['qmqe_matrix_sorting_criterion'];
        $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
      }
      return $answerData;
    }

    public function loadAnswerDataSortingChoice( $fields ) {
      $acfAnswerData = $fields['qmqe_sorting_choice_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $acfAnswer['answer'] = $acfAnswer['qmqe_sorting_choice_answer'];
        $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
      }
      return $answerData;
    }

    public function loadAnswerDataMultipleChoice( $fields ) {
      $acfAnswerData = $fields['qmqe_multiple_choice_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $answer['answer'] = $acfAnswer['qmqe_multiple_choice_answer'];
        $answer['correct'] = $acfAnswer['qmqe_multiple_choice_correct'];
        $answerData[] = new QuizMaster_Model_AnswerTypes( $answer );
      }
      return $answerData;
    }

    public function loadAnswerDataSingleChoice( $fields ) {

      var_dump(216);
      var_dump( $fields['qmqe_single_choice_answers'] );

      $acfAnswerData = $fields['qmqe_single_choice_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $answer['answer'] = $acfAnswer['qmqe_single_choice_answer'];
        $answer['correct'] = $acfAnswer['qmqe_single_choice_correct'];

        var_dump( $answer );

        $answerData[] = new QuizMaster_Model_AnswerTypes( $answer );
      }
      return $answerData;
    }

}
