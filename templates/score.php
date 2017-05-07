<?php

/**
 * Template for displaying quiz scores
 *
 * @package QuizMaster
 * @since 1.0
 * @version 1.0
 *
 *
 * Thanks to Easy Pie Chart, https://github.com/rendro/easy-pie-chart
 * https://rendro.github.io/easy-pie-chart/
 *
 *
 */

$scoreCtr = QuizMaster_Controller_Score::loadById( $post->ID );
$scoreView = new QuizMaster_View_Score;
$scoreModel = $scoreCtr->getScore();
$scoreView->setScoreQuestions( $scoreModel->getScores() );

get_header(); ?>

<div class="wrap quizmaster-wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<h1>Quiz Score</h1>

			<div class="quizmaster-score-summary">

				<table class="quizmaster-table quizmaster-info-table display info">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Quiz</td>
							<td><?php print $scoreModel->getQuizName(); ?></td>
						</tr>
						<tr>
							<td>User</td>
							<td><?php print $scoreModel->getUserName(); ?></td>
						</tr>
						<tr>
							<td>Correct Questions</td>
							<td><?php print $scoreModel->getCorrectRatio(); ?></td>
						</tr>
						<tr>
							<td>Hints Used</td>
							<td><?php print $scoreModel->getTotalHints(); ?></td>
						</tr>


					</tbody>
				</table>

			</div>

			<div class="quizmaster-container">
				<div class="quizmaster-row">

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
					<h3>Completion Time</h3>
					<?php print $scoreModel->getTotalTime(); ?>
				</div>

				<div class="quizmaster-score-summary-item">
					<h3>Total Questions Solved</h3>
					<?php print $scoreModel->getTotalSolved(); ?>
				</div>

			</div>

			<?php
				quizmaster_get_template( 'reports/score-question-table.php',
					array(
						'scoreView' => $scoreView,
						'scoreModel' => $scoreModel
					)
				);
			?>

			<!-- Return Link -->
			<?php
				$user = wp_get_current_user();
				$adminLink = false;
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
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer(); ?>

<script>
jQuery(".main-pie").easyPieChart({
		trackColor: "#000",
		scaleColor: "#999",
		barColor: "#999",
		lineWidth: 2,
		lineCap: "butt",
		size: 150
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

.quizmaster-wrap .quizmaster-score-summary h2 {
	margin: 6px 0;
	padding: 0;
}

.easyPieChart {
    position: relative;
    text-align: center;
}

.easyPieChart canvas {
    position: absolute;
    top: 0;
    left: 0;
}


</style>
