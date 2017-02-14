<div class="quizMaster_content" id="quizMaster_<?php echo $view->quiz->getId(); ?>">

  <?php

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
    $view->showStartOnlyAccessCodeBox();
    $view->showPrerequisiteBox();
    $view->showResultBox($view->result, $view->question_count);
    $view->showReviewBox($view->question_count);
    $view->showQuizAnker();

    $quizData = $view->showQuizBox($view->question_count);

  ?>
</div>

<?php

$bo = $view->createOption($view->preview);

?>

<script type="text/javascript">

  window.quizMasterInitList = window.quizMasterInitList || [];

  window.quizMasterInitList.push({
    id: '#quizMaster_<?php echo $view->quiz->getId(); ?>',
    init: {
      quizId: <?php echo (int)$view->quiz->getId(); ?>,
      mode: <?php echo (int)$view->quiz->getQuizModus(); ?>,
      globalPoints: <?php echo (int)$quizData['globalPoints']; ?>,
      timelimit: <?php echo (int)$view->quiz->getTimeLimit(); ?>,
      resultsGrade: <?php echo $view->resultsProzent; ?>,
      bo: <?php echo $bo ?>,
      qpp: <?php echo $view->quiz->getQuestionsPerPage(); ?>,
      catPoints: <?php echo json_encode($quizData['catPoints']); ?>,
      formPos: <?php echo (int)$view->quiz->getFormShowPosition(); ?>,
      lbn: <?php echo json_encode(($view->quiz->isShowReviewQuestion() && !$view->quiz->isQuizSummaryHide()) ? $view->_buttonNames['quiz_summary'] : $view->_buttonNames['finish_quiz']); ?>,
      json: <?php echo json_encode($quizData['json']); ?>
    }
  });

</script>
