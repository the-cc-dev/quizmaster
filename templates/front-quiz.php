<div class="quizMaster_content" id="quizMaster_<?php echo $view->quiz->getId(); ?>">

  <?php

		$quizData = $view->showQuizBox( $view->question_count );

    if (!$view->quiz->isTitleHidden()) {
      echo '<h2>', $view->quiz->getName(), '</h2>';
    }

    $view->showTimeLimitBox();
    $view->showCheckPageBox($view->question_count);
    $view->showInfoPageBox();
    $view->showStartQuizBox();
    $view->showLockBox();
    $view->showLoadQuizBox();
    $view->showStartOnlyRegisteredUserBox();
    $view->showPrerequisiteBox();
    $view->showReviewBox($view->question_count);
    $view->showQuizAnker();

		// enables quizmaster extension to load quiz boxes via action hook
		$view->renderExtensionQuizBoxes();

		quizmaster_get_template('quiz/header.php',
			array(
				'view'          => $view,
				'questionCount' => $view->question_count,
			)
		);

		$view->showResultBox( $view->result, $view->question_count );

		quizmaster_get_template('quiz/question-item.php',
			array(
				'view'          => $view,
				'questionCount' => $view->question_count,
			)
		);

		quizmaster_get_template('quiz/footer.php',
			array(
				'view'          => $view,
				'quiz'					=> $view->quiz,
				'questionCount' => $view->question_count,
			)
		);

  ?>
</div>

<?php

$bo = $view->createOption($view->preview);

?>

<script type="text/javascript">

  window.quizmasterQuizRegistry = window.quizmasterQuizRegistry || [];

  window.quizmasterQuizRegistry.push({
    id: '#quizMaster_<?php echo $view->quiz->getId(); ?>',
    init: {
      quizId: <?php echo (int)$view->quiz->getId(); ?>,
      mode: <?php echo (int)$view->quiz->getQuizModus(); ?>,
      globalPoints: <?php echo (int)$quizData['globalPoints']; ?>,
      timelimit: <?php echo (int)$view->quiz->getTimeLimit(); ?>,
      bo: <?php echo $bo ?>,
      qpp: <?php echo $view->quiz->getQuestionsPerPage(); ?>,
      catPoints: <?php echo json_encode($quizData['catPoints']); ?>,
      formPos: <?php echo (int)$view->quiz->getFormShowPosition(); ?>,
      lbn: <?php echo json_encode(($view->quiz->isShowReviewQuestion() && !$view->quiz->isQuizSummaryHide()) ? $view->_buttonNames['quiz_summary'] : $view->_buttonNames['finish_quiz']); ?>,
      json: <?php echo json_encode($quizData['json']); ?>
    }
  });

</script>
