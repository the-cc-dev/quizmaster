<div class="qm-check-page" style="display: none;">

	<h4 class="quizMaster_header"><?php echo $view->_buttonNames['quiz_summary']; ?></h4>

	<p>
			<?php printf(__('%s of %s questions completed', 'quizmaster'), '<span>0</span>', $questionCount); ?>
	</p>

	<p><?php _e('Questions', 'quizmaster'); ?>:</p>

	<div class="quizMaster_box">
		<ol>
			<?php for ($xy = 1; $xy <= $questionCount; $xy++) { ?>
					<li><?php echo $xy; ?></li>
			<?php } ?>
		</ol>
	</div>

	<?php
		if ($view->quiz->isFormActivated() && $view->quiz->getFormShowPosition() == QuizMaster_Model_Quiz::QUIZ_FORM_POSITION_END
				&& ($view->quiz->isShowReviewQuestion() && !$view->quiz->isQuizSummaryHide())
		) {
	?>

		<h4 class="quizMaster_header"><?php _e('Information', 'quizmaster'); ?></h4>

	<?php } ?>

	<input type="button" name="endQuizSummary" value="<?php echo $view->_buttonNames['finish_quiz']; ?>" class="qm-button">

</div>
