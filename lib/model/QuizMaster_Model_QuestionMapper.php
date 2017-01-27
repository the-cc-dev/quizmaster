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

    public function fetch($id) {
        $model = new QuizMaster_Model_Question($row);
        return $model;
    }

    /**
     * @param $id
     * @return QuizMaster_Model_Question|QuizMaster_Model_Question[]|null
     */
    public function fetchById($id)
    {

        $ids = array_map('intval', (array)$id);
        $a = array();

        if (empty($ids)) {
            return null;
        }

        $results = $this->_wpdb->get_results(
            "SELECT
					*
				FROM
					" . $this->_table . "
				WHERE
					id IN(" . implode(', ', $ids) . ") AND online = 1",
            ARRAY_A
        );

        foreach ($results as $row) {
            $a[] = new QuizMaster_Model_Question($row);

        }

        return is_array($id) ? $a : (isset($a[0]) ? $a[0] : null);
    }

    /**
     * @param $quizId
     * @param bool $rand
     * @param int $max
     *
     * @return QuizMaster_Model_Question[]
     */
    public function fetchAll($quizId, $rand = false, $max = 0) {

        /*
        if ($rand) {
            $orderBy = 'ORDER BY RAND()';
        } else {
            $orderBy = 'ORDER BY sort ASC';
        }
        */

        $results = array();

        $quizPost = get_post( $quizId );
        $quizQuestions = get_field( 'quiz_questions', $quizId );

        /*
        print '<pre>';
        var_dump( $quizQuestions );
        print '</pre>';
        */

        // get all questions
        /*
        $query = new WP_Query(array(
          'post_type'       => 'quizmaster_question',
          'post_status'     => 'publish',
          'posts_per_page'  => -1,
        ));
        */

        foreach( $quizQuestions as $qq ) {
          $quizQuestionID = $qq['quiz_question'];
          $fields = get_fields( $quizQuestionID );
          $fields['id'] = $quizQuestionID;

          // set answer data
          $acfAnswerData = $fields['answerData'];
          $answerData = array();
          foreach( $acfAnswerData as $acfAnswer ) {
            $answerData[] = new QuizMaster_Model_AnswerTypes( $acfAnswer );
          }

          $fields['answerData'] = $answerData;

          /*
          print '<pre>';
          var_dump( $answerData );
          print '</pre>';
          */

          $model = new QuizMaster_Model_Question( $fields );
          $a[] = $model;
        }

        /*
        print '<pre>';
        $q = $a[0];
        // print $q->getAnswerType();
        var_dump( $a );
        print '</pre>';
        */

        return $a;
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

    public function fetchAllList($quizId, $list, $sort = false)
    {
        $sort = $sort ? 'ORDER BY sort' : '';

        $results = $this->_wpdb->get_results(
            $this->_wpdb->prepare(
                'SELECT
								' . implode(', ', (array)$list) . '
							FROM
								' . $this->_tableQuestion . '
							WHERE
								quiz_id = %d AND online = 1
							' . $sort
                , $quizId),
            ARRAY_A);

        return $results;
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

    public function setMultipeCategories($questionIds, $categoryId)
    {
        $categoryId = $categoryId < 0 ? 0 : $categoryId;

        $questionIds = implode(', ', array_map('intval', (array)$questionIds));

        return $this->_wpdb->query($this->_wpdb->prepare(
            "UPDATE
					{$this->_table}
				SET
					`category_id` = %d
				WHERE id IN(" . $questionIds . ")"
            , $categoryId));
    }
}
