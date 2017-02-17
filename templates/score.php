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

$totals = $scoreModel->getTotals();

get_header(); ?>

<div class="wrap quizmaster">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<h1>Quiz Score</h1>
			<h2>Quiz: <?php print $scoreModel->getQuizName(); ?></h2>
			<h3>User: <?php print $scoreModel->getUserId(); ?></h3>

			<div class="mini-charts-item bgm-bluegray">
	      <div class="clearfix">
          <div class="chart stats-line-2"><canvas width="68" height="35" style="display: inline-block; width: 68px; height: 35px; vertical-align: top;"></canvas></div>
          <div class="count">
            <small>Questions Corrent/Incorrect</small>
            <h2><?php print $scoreModel->getCorrectRatio(); ?></h2>
          </div>
	      </div>
      </div>

<div class="dash-widgets">

<div id="pie-charts" class="dw-item bgm-cyan c-white">

			<div class="dw-item">
			                                        <div class="dwi-header">
			                                            <div class="dwih-title">Quiz Statistics</div>
			                                        </div>

			                                        <div class="clearfix"></div>

			                                        <div class="text-center p-20 m-t-25">
			                                            <div class="easy-pie main-pie" data-percent="<?php print $scoreModel->getScoreResult(); ?>">
			                                                <div class="percent"><?php print $scoreModel->getScoreResult(); ?></div>
			                                                <div class="pie-title">Overall Score</div>
			                                            <canvas height="148" width="148"></canvas></div>
			                                        </div>


	<div class="p-t-25 p-b-20 text-center">
		<div class="easy-pie sub-pie-1" data-percent="<?php print $scoreModel->getSolvedPercentage(); ?>">
			<div class="percent"><?php print $scoreModel->getSolvedPercentage(); ?></div>
			<div class="pie-title">Questions Solved</div>
			<canvas height="90" width="90"></canvas>
		</div>

	<div class="easy-pie sub-pie-2" data-percent="<?php print $scoreModel->getQuestionsCorrectPercentage(); ?>">
	<div class="percent"><?php print $scoreModel->getQuestionsCorrectPercentage(); ?></div>
	<div class="pie-title">Questions Correct</div>
	<canvas height="90" width="90"></canvas>
	</div>

	<div class="easy-pie sub-pie-2" data-percent="<?php print $scoreModel->getQuestionsIncorrectPercentage(); ?>">
	<div class="percent"><?php print $scoreModel->getQuestionsIncorrectPercentage(); ?></div>
	<div class="pie-title">Total Incorrect</div>
	<canvas height="90" width="90"></canvas></div>
	</div>
	</div>
		</div>


		</div>




			<!-- Score Summary -->
			<div class="score-summary">
				<h2><?php print __('Score Summary', 'quizmaster'); ?></h2>

				<div class="quizmaster-score-summary-item">
					<h3>Questions Corrent/Incorrect</h3>
					<?php print $scoreModel->getCorrectRatio(); ?>
				</div>

				<div class="quizmaster-score-summary-item">
					<h3>Hints Used</h3>
					<?php print $scoreModel->getTotalHints(); ?>
				</div>

				<div class="quizmaster-score-summary-item">
					<h3>Completion Time</h3>
					<?php print $scoreModel->getTotalTime(); ?>
				</div>

				<div class="quizmaster-score-summary-item">
					<h3>Total Questions Solved</h3>
					<?php print $scoreModel->getTotalSolved(); ?>
				</div>

			</div>

			<!-- Score Details Table -->
			<table>
				<tr>
					<th>Question</th>
					<th>Points</th>
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
						<td><?php print $scoreView->getPoints() . '/' . $scoreView->getPointsPossible(); ?></td>
						<td><?php print $scoreView->getCorrectCount(); ?></td>
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

			<!-- Return Link -->
			<?php
				$user = wp_get_current_user();
				$adminLink = false;
				if ( in_array( 'teacher', (array) $user->roles ) ) {
					$adminLink = true;
				}
				if ( in_array( 'administrator', (array) $user->roles ) ) {
					$adminLink = true;
				}

				if( $adminLink ) {
					$returnUrl = admin_url( 'edit.php?post_type=quizmaster_score' );
				} else {
					$returnUrl = home_url('student-report');
				}
			?>
			<div class="quizmaster-score-return-button">
				<a class="quizmaster-score-return-link button" href="<?php print $returnUrl; ?>">Return to Scores List</a>
			</div>


		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>

<script>
jQuery(".main-pie").easyPieChart({
		trackColor: "rgba(255,255,255,0.2)",
		scaleColor: "rgba(255,255,255,0)",
		barColor: "rgba(255,255,255,0.7)",
		lineWidth: 2,
		lineCap: "butt",
		size: 148
});
jQuery(".sub-pie-1").easyPieChart({
		trackColor: "rgba(255,255,255,0.2)",
		scaleColor: "rgba(255,255,255,0)",
		barColor: "rgba(255,255,255,0.7)",
		lineWidth: 2,
		lineCap: "butt",
		size: 90
});
jQuery(".sub-pie-2").easyPieChart({
		trackColor: "rgba(255,255,255,0.2)",
		scaleColor: "rgba(255,255,255,0)",
		barColor: "rgba(255,255,255,0.7)",
		lineWidth: 2,
		lineCap: "butt",
		size: 90
});

</script>

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

999999999999 777777777777 333333333333
<?php
print '<br />';
print $scoreModel->getTotalQuestionCount();
print '<br />';
print $scoreModel->getSolvedCount();
print '<br />';
print $scoreModel->getSolvedPercentage();
?>
