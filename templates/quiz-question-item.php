<div style="display: none;" class="quizMaster_quiz">

  <ol class="quizMaster_list">
    <?php
      $index = 0;
      foreach ($view->question as $question) :
        $index++;
    ?>

    <li class="quizMaster_listItem" style="display: none;">
        <div class="quizMaster_question_page" <?php $view->isDisplayNone($view->quiz->getQuizModus() != QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE && !$view->quiz->isHideQuestionPositionOverview()); ?> >
            <?php printf(__('Question %s of %s', 'quizmaster'), '<span>' . $index . '</span>',
                '<span>' . $questionCount . '</span>'); ?>
        </div>
        <h5 style="<?php echo $view->quiz->isHideQuestionNumbering() ? 'display: none;' : 'display: inline-block;' ?>"
            class="quizMaster_header">
            <span><?php echo $index; ?></span>. <?php _e('Question', 'quizmaster'); ?>
        </h5>

        <ul class="quizMaster_questionList" data-question_id="<?php echo $question->getId(); ?>"
          data-type="<?php echo $question->getAnswerType(); ?>">

            <?php
              // render question
              // @TODO support: $view->quiz->isShowCategory()) , $view->quiz->isShowPoints()
              // $view->quiz->isHideAnswerMessageBox()
              $question->render();
            ?>

        </ul>

        <?php
          // question buttons
          print quizmaster_get_template('quiz/question-buttons.php',
            array(
              'question' => $question,
              'quiz' => $view->quiz,
            )
          );
        ?>

    </li>

    <?php endforeach; ?>
  </ol>

  <!-- quiz completion buttons -->
  <?php if ($view->quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE) : ?>
    <div class="quizmaster-quiz-completion-buttons">
      <?php
        print quizmaster_get_template( 'quiz-button-left-right.php' );
        if ($view->quiz->isShowReviewQuestion() && !$view->quiz->isQuizSummaryHide()) {
          print quizmaster_get_template( 'quiz-button-summary.php', array('view' => $view));
        } else {
          print quizmaster_get_template( 'quiz-button-finish.php', array('view' => $view));
        }
      ?>
    </div>
  <?php endif; ?>

</div>
