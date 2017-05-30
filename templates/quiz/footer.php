<div class="quizmaster-container qm-quiz-footer">
	<div class="quizmaster-row">
		<div class="quizmaster-col-6">

			<?php

				if ($quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_CHECK && !$quiz->isSkipQuestionDisabled() && $quiz->isShowReviewQuestion()) {
				  print quizmaster_get_template( 'quiz-button-skip.php' );
				}

				?>

		</div>
		<div class="quizmaster-col-6 right">

			<?php print quizmaster_get_template( 'quiz-button-check-next.php' ); ?>

		</div>
	</div>
</div>

<!-- Hint Modal -->
<div class="qm-hint-modal"></div>
