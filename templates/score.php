<?php
/**
 * The template for displaying quiz scores
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package QuizMaster
 * @since 1.0
 * @version 1.0
 */

$scoreCtr = QuizMaster_Controller_Score::loadById( $post->ID );
$scoreView = new QuizMaster_View_Score;
$scoreModel = $scoreCtr->getScore();
$scoreView->setScoreQuestions( $scoreModel->getScores() );

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<h2>Quiz <?php print $scoreModel->getQuizId(); ?></h2>
			<h3>User <?php print $scoreModel->getUserId(); ?></h3>
			<table>
				<tr>
					<th>Question</th>
					<th>Possible Points</th>
					<th>Points Scored</th>
					<th>Correct</th>
					<th>Incorrect</th>
					<th>Hints Used</th>
					<th>Solved</th>
					<th>Time</th>
				</tr>

				<?php foreach( $scoreView->getScoreQuestions() as $scoreQuestion ) :

					$scoreView->setActiveScoreQuestion( $scoreQuestion );

				?>
					<tr>
						<td><?php print $scoreView->getQuestion(); ?></td>
						<td><?php print $scoreView->getPossiblePoints(); ?></td>
						<td><?php print $scoreView->getPoints(); ?></td>
						<td><?php print $scoreView->getCorrectCount(); ?></td>
						<td><?php print $scoreView->getIncorrectCount(); ?></td>
						<td><?php print $scoreView->getHintCount(); ?></td>
						<td><?php print $scoreView->getSolvedCount(); ?></td>
						<td><?php print $scoreView->getQuestionTime(); ?></td>
					</tr>
				<?php endforeach; ?>

				<!-- Totals Row -->
				<tfoot>
					<tr>
						<th><?php _e('Total', 'quizmaster'); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'possiblePoints' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'correctCount' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'incorrectCount' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'hintCount' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'solvedCount' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'questionTime' ); ?></th>
						<th><?php print $scoreView->getScoreTotal( 'points' ); ?></th>
					</tr>
				</tfoot>
			</table>

			<div class="score-summary">
				<?php print $scoreView->getScoreResult(); ?>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>


<style>
.quizMaster_questionList {
  margin-bottom: 10px !important;
  background: #F8FAF5 !important;
  border: 1px solid #C3D1A3 !important;
  padding: 5px !important;
  list-style: none !important;
}

.quizMaster_questionList > li {
  padding: 3px !important;
  margin-bottom: 5px !important;
  background-image: none !important;
  margin-left: 0 !important;
  list-style: none !important;
}

.quizMaster_answerCorrect {
  background: #6DB46D !important;
  font-weight: bold !important;
}

.quizMaster_answerIncorrect {
  background: #FF9191 !important;
  font-weight: bold !important;
}

.quizMaster_sortable {
  padding: 5px !important;
  border: 1px solid lightGrey !important;
  background-color: #F8FAF5 !important;
}

.quizMaster_questionList table {
  border-collapse: collapse !important;
  margin: 0 !important;
  padding: 0 !important;
  width: 100%;
}

.quizMaster_questionList table {
  border-collapse: collapse !important;
}

.quizMaster_mextrixTr > td {
  border: 1px solid #D1D1D1 !important;
  padding: 5px !important;
  vertical-align: middle !important;
}

.quizMaster_maxtrixSortCriterion {
  padding: 5px !important;
}

.quizMaster_sortStringItem {
  margin: 0 !important;
  background-image: none !important;
  list-style: none !important;
  padding: 5px !important;
  border: 1px solid lightGrey !important;
  background-color: #F8FAF5 !important;
}

.quizMaster_cloze {
  padding: 0 4px 2px 4px;
  border-bottom: 1px solid #000;
}
</style>
