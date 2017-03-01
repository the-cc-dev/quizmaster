<?php

class QuizMaster_Model_QuestionMapper extends QuizMaster_Model_Mapper
{
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
     */
    public function fetchAllList($quizId, $list, $sort = false) {

      $results = array();
      $quizPost = get_post( $quizId );
      $quizQuestions = get_field( 'quiz_questions', $quizId );
      foreach( $quizQuestions as $qq ) {

        $quizQuestionID = $qq['quiz_question'];

        // $question = new QuizMaster_Model_Question( $quizQuestionID );

        $fields = get_fields( $quizQuestionID );
        $results[] = array(
          'id'      => $quizQuestionID,
          'points'  => $fields['qm_qe_points'],
        );
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
        $quizQuestions = get_field( 'quiz_questions', $quizId );

        if( empty($quizQuestions)) {
          return false;
        }

        foreach( $quizQuestions as $qq ) {
          $quizQuestionID = $qq['quiz_question'];
          $fields = get_fields( $quizQuestionID );
          $fields['id'] = $quizQuestionID;

          // set answer data
          switch( $fields['answerType'] ) {
            case 'single':
            case 'multiple':
              $answerData = $this->loadAnswerDataClassic( $fields );
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

          // $fields['answerData'] = $answerData;
          // $model = new QuizMaster_Model_Question( $fields );
          $model = new QuizMaster_Model_Question( $quizQuestionID );
          $model->setAnswerData( $answerData );
          $a[] = $model;
        }

        return $a;
    }

    // MOVE TO QUESTION MODEL
    public function loadAnswerDataAssessment( $fields ) {
      $acfAnswerData['answer'] = $fields['assessment_answer'];
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataCloze( $fields ) {
      $acfAnswerData['answer'] = $fields['cloze_answer'];
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataFreeChoice( $fields ) {
      $acfAnswerData = array(
        'answer' => $fields['free_choice_answers']
      );
      $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswerData );
      return $answerData;
    }

    public function loadAnswerDataMatrixSortingAnswer( $fields ) {
      $acfAnswerData = $fields['matrix_sorting_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $acfAnswer['answer']      = $acfAnswer['criterion'];
        $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
      }
      return $answerData;
    }

    public function loadAnswerDataSortingChoice( $fields ) {
      $acfAnswerData = $fields['sorting_choice_answers'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $acfAnswer['answer'] = $acfAnswer['sorting_choice_answer'];
        $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
      }
      return $answerData;
    }

    public function loadAnswerDataClassic( $fields ) {
      $acfAnswerData = $fields['answerData'];
      $answerData = array();
      foreach( $acfAnswerData as $acfAnswer ) {
        $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
      }
      return $answerData;
    }

    /**
     * @param $quizId
     * @param $orderBy
     * @param $order
     * @param $search
     * @param $limit
     * @param $offset
     * @param $filter
     * @return array
     */
    public function fetchTable($quizId, $orderBy, $order, $search, $limit, $offset, $filter)
    {
        $r = array();

        switch ($orderBy) {
            case 'category';
                $_orderBy = 'c.category_name';
                break;
            case 'name':
                $_orderBy = 'q.title';
                break;
            default:
                $_orderBy = 'q.sort';
                $order = 'asc';
                break;
        }

        $whereFilter = '';

        if ($filter) {
            if (isset($filter['cat']) && $filter['cat']) {
                $whereFilter = ' AND q.category_id = ' . ((int)$filter['cat']);
            }
        }

        $results = $this->_wpdb->get_results($this->_wpdb->prepare("
				SELECT
					q.*,
					c.category_name
				FROM
					{$this->_table} AS q
					LEFT JOIN {$this->_tableCategory} AS c
						ON c.category_id = q.category_id
				WHERE
					quiz_id = %d AND q.online = 1 AND
					q.title LIKE %s
					{$whereFilter}
				ORDER BY
					{$_orderBy} " . ($order == 'asc' ? 'asc' : 'desc') . "
				LIMIT %d, %d
			", array(
            $quizId,
            '%' . $search . '%',
            $offset,
            $limit
        )), ARRAY_A);

        foreach ($results as $row) {
            $r[] = new QuizMaster_Model_Question($row);
        }

        $count = $this->_wpdb->get_var($this->_wpdb->prepare("
				SELECT
					COUNT(*) as count_rows
				FROM
					{$this->_table} AS q
				WHERE
					quiz_id = %d AND q.online = 1 AND
					q.title LIKE %s
					{$whereFilter}
			", array(
            $quizId,
            '%' . $search . '%'
        )));

        return array(
            'questions' => $r,
            'count' => $count ? $count : 0
        );
    }

    public function count($quizId)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT COUNT(*) FROM {$this->_table} WHERE quiz_id = %d AND online = 1",
            $quizId));
    }

    public function exists($id)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT COUNT(*) FROM {$this->_table} WHERE id = %d AND online = 1",
            $id));
    }

    public function existsAndWritable($id)
    {
        return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT COUNT(*) FROM {$this->_table} WHERE id = %d AND online = 1",
            $id));
    }

    public function fetchCategoryPoints($quizId)
    {
        $results = $this->_wpdb->get_results(
            $this->_wpdb->prepare(
                'SELECT SUM( points ) AS sum_points , category_id
						FROM ' . $this->_tableQuestion . '
						WHERE quiz_id = %d AND online = 1
						GROUP BY category_id', $quizId));

        $a = array();

        foreach ($results as $result) {
            $a[$result['category_id']] = $result['sum_points'];
        }

        return $a;
    }

}
