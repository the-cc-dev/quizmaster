<div class="quizMaster_content" id="quizMaster_<?php echo $this->quiz->getId(); ?>">
    <?php

    if (!$this->quiz->isTitleHidden()) {
        echo '<h2>', $this->quiz->getName(), '</h2>';
    }

    $this->showTimeLimitBox();
    $this->showCheckPageBox($question_count);
    $this->showInfoPageBox();
    $this->showStartQuizBox();
    $this->showLockBox();
    $this->showLoadQuizBox();
    $this->showStartOnlyRegisteredUserBox();
    $this->showPrerequisiteBox();
    $this->showResultBox($result, $question_count);

    if ($this->quiz->getToplistDataShowIn() == QuizMaster_Model_Quiz::QUIZ_TOPLIST_SHOW_IN_BUTTON) {
        $this->showToplistInButtonBox();
    }

    $this->showReviewBox($question_count);
    $this->showQuizAnker();

    $quizData = $this->showQuizBox($question_count);

    ?>
</div>
<?php

$bo = $this->createOption($preview);

?>

<script type="text/javascript">
    window.quizMasterInitList = window.quizMasterInitList || [];

    window.quizMasterInitList.push({
        id: '#quizMaster_<?php echo $this->quiz->getId(); ?>',
        init: {
            quizId: <?php echo (int)$this->quiz->getId(); ?>,
            mode: <?php echo (int)$this->quiz->getQuizModus(); ?>,
            globalPoints: <?php echo (int)$quizData['globalPoints']; ?>,
            timelimit: <?php echo (int)$this->quiz->getTimeLimit(); ?>,
            resultsGrade: <?php echo $resultsProzent; ?>,
            bo: <?php echo $bo ?>,
            qpp: <?php echo $this->quiz->getQuestionsPerPage(); ?>,
            catPoints: <?php echo json_encode($quizData['catPoints']); ?>,
            formPos: <?php echo (int)$this->quiz->getFormShowPosition(); ?>,
            lbn: <?php echo json_encode(($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide()) ? $this->_buttonNames['quiz_summary'] : $this->_buttonNames['finish_quiz']); ?>,
            json: <?php echo json_encode($quizData['json']); ?>
        }
    });
</script>
