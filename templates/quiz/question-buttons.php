<?php if ($question->isTipEnabled()) { ?>
  <div class="quizMaster_tipp" style="display: none;">
    <div>
      <h5 class="quizMaster_header"><?php __('Hint', 'quizmaster'); ?></h5>
      <?php echo do_shortcode(apply_filters('comment_text', $question->getTipMsg())); ?>
    </div>
  </div>
<?php } ?>

<?php

if ($question->isTipEnabled()) {
	// @TODO make this question setting work outside question
  print quizmaster_get_template( 'quiz/button-hint.php' );
}

?>
