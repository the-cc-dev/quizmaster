<div class="quizmaster-container">

	<div class="quizmaster-row">
		<div class="quizmaster-col-3">

			<?php
		    print quizmaster_get_template( 'quiz-button-hint.php' );
		  ?>

		</div>

		<div class="quizmaster-col-6 center">

			<!-- Static Header Message -->
			<?php if( $view->showStaticHeaderMessage() ) : ?>
				<div class="qm-quiz-static-message">
					<?php print $view->quiz->getStaticHeaderMessage(); ?>
				</div>
			<?php endif; // show static message ?>

		</div>

		<div class="quizmaster-col-3">
			COL3
		</div>

	</div>
</div>
