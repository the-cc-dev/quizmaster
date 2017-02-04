<?php

/**
 * @property array users
 * @property QuizMaster_Model_Quiz quiz
 */
class QuizMaster_View_Scores extends QuizMaster_View_View {

    public function show() {
      quizmaster_get_template( 'reports/scores.php', array( 'view' => $this));

      quizmaster_get_template( 'reports/score-list-table.php', array( 'view' => $this));
    }

    public function showHistory() {
      quizmaster_get_template( 'reports/score-history.php', array( 'view' => $this));
    }

    public function showModalWindow() {
      quizmaster_get_template( 'reports/score-modal.php', array( 'view' => $this));
    }

    public function showTabOverview() {
      quizmaster_get_template( 'reports/score-tab.php', array( 'view' => $this));
    }


}
