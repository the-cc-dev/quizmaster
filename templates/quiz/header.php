<div class="quizmaster-container qm-quiz-header">

	<div class="quizmaster-row">
		<div class="quizmaster-col-3">

			<?php
		    print quizmaster_get_template( 'quiz/button-hint.php' );
		  ?>

		</div>

		<div class="quizmaster-col-6 center">

			<!-- Progress Bar -->
			<div class="qm-progress-bar">PROGRESS BAR</div>

			<!-- Static Header Message -->
			<?php if( $view->showStaticHeaderMessage() ) : ?>
				<div class="qm-quiz-static-message">
					<?php print $view->quiz->getStaticHeaderMessage(); ?>
				</div>
			<?php endif; // show static message ?>

		</div>

		<div class="quizmaster-col-3 right">

			<!-- quit button -->
			<a href="<?php print get_home_url(); ?>" class="button qm-quit-button">Quit</a>

		</div>

	</div>
</div>
