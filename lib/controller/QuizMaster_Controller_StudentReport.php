<?php

class QuizMaster_Controller_StudentReport {

  public function getCompletedQuizTable() {

    $formMapper = new QuizMaster_Model_FormMapper();
    $forms = $formMapper->fetch(1);

    $user = wp_get_current_user();
    $statisticModel = $this->fetchUserHistory( $user->ID );

    foreach ($statisticModel as $model) {
        /* @var $model QuizMaster_Model_StatisticHistory */
        if (!$model->getUserId()) {
            $model->setUserName(__('Anonymous', 'wp-pro-quiz'));
        } else {
            if ($model->getUserName() == '') {
                $model->setUserName(__('Deleted user', 'wp-pro-quiz'));
            }
        }

        $sum = $model->getCorrectCount() + $model->getIncorrectCount();
        $result = round(100 * $model->getPoints() / $model->getGPoints(), 2) . '%';

        $model->setResult($result);
        $model->setFormatTime(QuizMaster_Helper_Until::convertTime($model->getCreateTime(),
            get_option('QuizMaster_statisticTimeFormat', 'Y/m/d g:i A')));

        $model->setFormatCorrect($model->getCorrectCount() . ' (' . round(100 * $model->getCorrectCount() / $sum,
                2) . '%)');
        $model->setFormatIncorrect($model->getIncorrectCount() . ' (' . round(100 * $model->getIncorrectCount() / $sum,
                2) . '%)');

        $formData = $model->getFormData();
        $formOverview = array();

        foreach ($forms as $form) {
            /* @var $form QuizMaster_Model_Form */
            if ($form->isShowInStatistic()) {
                $formOverview[] = $formData != null && isset($formData[$form->getFormId()])
                    ? QuizMaster_Helper_Form::formToString($form, $formData[$form->getFormId()])
                    : '----';
            }
        }

        $model->setFormOverview($formOverview);
    }

    $view = new PQC_Completed_Quiz();
    $view->historyModel = $statisticModel;
    $view->forms = $forms;

    return $view->getHistoryTable();
  }

  /**
   * @param int $user
   * @param $page
   * @param $limit
   * @param int $startTime
   * @param int $endTime
   * @return QuizMaster_Model_StatisticHistory[]
   */
  public function fetchUserHistory($user)
  {

      global $wpdb;
      $mapper = new QuizMaster_Model_Mapper;

      $prefix = $wpdb->prefix . 'wp_pro_quiz_';
      $tableStatistic = $prefix . 'statistic';
      $tableStatisticRef = $prefix . 'statistic_ref';
      $tableQuestion = $prefix . 'question';
      $tableQuiz = $prefix . 'master';

      $result = $wpdb->get_results(
          $wpdb->prepare('
      SELECT
        u.`user_login`, u.`display_name`, u.ID AS user_id,
        sf.*,
        quiz.name AS quiz_name,
        SUM(s.correct_count) AS correct_count,
        SUM(s.incorrect_count) AS incorrect_count,
        SUM(s.solved_count) as solved_count,
        SUM(s.points) AS points,
        SUM(q.points) AS g_points
      FROM
        ' . $tableStatisticRef . ' AS sf
        INNER JOIN ' . $tableStatistic . ' AS s ON(s.statistic_ref_id = sf.statistic_ref_id)
        LEFT JOIN ' . $wpdb->users . ' AS u ON(u.ID = sf.user_id)
        INNER JOIN ' . $tableQuestion . ' AS q ON(q.id = s.question_id)
        LEFT JOIN ' . $tableQuiz . ' AS quiz ON(quiz.id = sf.quiz_id)
      WHERE
        user_id = %d AND sf.is_old = 0
      GROUP BY
        sf.statistic_ref_id
      ORDER BY
        sf.create_time DESC
      LIMIT
        0, 200
    ', $user),
          ARRAY_A
      );

      $r = array();

      foreach ($result as $row) {
        if (!empty($row['user_login'])) {
          $row['user_name'] = $row['user_login'] . ' (' . $row['display_name'] . ')';
        }

        $row['form_data'] = $row['form_data'] === null ? null : @json_decode($row['form_data'], true);

        $r[] = new PQC_UserStatisticHistory($row);
      }

      return $r;
  }

}
