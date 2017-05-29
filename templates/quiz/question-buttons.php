<?php if ($question->isTipEnabled()) { ?>
  <div class="quizMaster_tipp" style="display: none;">
    <div>
      <h5 class="quizMaster_header"><?php __('Hint', 'quizmaster'); ?></h5>
      <?php echo do_shortcode(apply_filters('comment_text', $question->getTipMsg())); ?>
    </div>
  </div>
<?php } ?>

<?php

if ($quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_CHECK && !$quiz->isSkipQuestionDisabled() && $quiz->isShowReviewQuestion()) {
    print quizmaster_get_template( 'quiz-button-skip.php' );
}

  print quizmaster_get_template( 'quiz-button-back.php' );

  if ($question->isTipEnabled()) {
    print quizmaster_get_template( 'quiz-button-hint.php' );
  }
?>

<?php print quizmaster_get_template( 'quiz-button-check-next.php' ); ?>
