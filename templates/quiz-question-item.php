<div style="display: none;" class="quizMaster_quiz">
  <ol class="quizMaster_list">
    <?php
      $index = 0;
      foreach ($view->question as $question) {
        $index++;

        /* @var $answerArray QuizMaster_Model_AnswerTypes[] */
        $answerArray = $question->getAnswerData();
    ?>

<li class="quizMaster_listItem" style="display: none;">
    <div
        class="quizMaster_question_page" <?php $view->isDisplayNone($view->quiz->getQuizModus() != QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE && !$view->quiz->isHideQuestionPositionOverview()); ?> >
        <?php printf(__('Question %s of %s', 'quizmaster'), '<span>' . $index . '</span>',
            '<span>' . $questionCount . '</span>'); ?>
    </div>
    <h5 style="<?php echo $view->quiz->isHideQuestionNumbering() ? 'display: none;' : 'display: inline-block;' ?>"
        class="quizMaster_header">
        <span><?php echo $index; ?></span>. <?php _e('Question', 'quizmaster'); ?>
    </h5>

    <?php if ($view->quiz->isShowPoints()) { ?>
        <span style="font-weight: bold; float: right;"><?php printf(__('%d points', 'quizmaster'),
                $question->getPoints()); ?></span>
        <div style="clear: both;"></div>
    <?php } ?>

    <?php if ($question->getCategoryId() && $view->quiz->isShowCategory()) { ?>
        <div style="font-weight: bold; padding-top: 5px;">
            <?php printf(__('Category: %s', 'quizmaster'),
                esc_html($question->getCategoryName())); ?>
        </div>
    <?php } ?>
    <div class="quizMaster_question" style="margin: 10px 0 0 0;">
        <div class="quizMaster_question_text">
            <?php echo do_shortcode(apply_filters('comment_text', $question->getQuestion())); ?>
        </div>
        <?php if ($question->getAnswerType() === 'matrix_sort_answer') { ?>
            <div class="quizMaster_matrixSortString">
                <h5 class="quizMaster_header"><?php _e('Sort elements', 'quizmaster'); ?></h5>
                <ul class="quizMaster_sortStringList">
                    <?php
                    $matrix = array();
                    foreach ($answerArray as $k => $v) {
                        $matrix[$k][] = $k;

                        foreach ($answerArray as $k2 => $v2) {
                            if ($k != $k2) {
                                if ($v->getAnswer() == $v2->getAnswer()) {
                                    $matrix[$k][] = $k2;
                                } else {
                                    if ($v->getSortString() == $v2->getSortString()) {
                                        $matrix[$k][] = $k2;
                                    }
                                }
                            }
                        }
                    }

                    foreach ($answerArray as $k => $v) {
                        ?>
                        <li class="quizMaster_sortStringItem" data-pos="<?php echo $k; ?>"
                            data-correct="<?php echo implode(',', $matrix[$k]); ?>">
                            <?php echo $v->isSortStringHtml() ? $v->getSortString() : esc_html($v->getSortString()); ?>
                        </li>
                    <?php } ?>
                </ul>
                <div style="clear: both;"></div>
            </div>
        <?php } ?>
        <ul class="quizMaster_questionList" data-question_id="<?php echo $question->getId(); ?>"
            data-type="<?php echo $question->getAnswerType(); ?>">
            <?php
            $answer_index = 0;

            foreach ($answerArray as $v) {
                $answer_text = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());

                if ($answer_text == '') {
                    continue;
                }



                ?>

                <li class="quizMaster_questionListItem" data-pos="<?php echo $answer_index; ?>">

                    <?php if ($question->getAnswerType() === 'single' || $question->getAnswerType() === 'multiple') { ?>
                        <span <?php echo $view->quiz->isNumberedAnswer() ? '' : 'style="display:none;"' ?>></span>
                        <label>
                            <input class="quizMaster_questionInput"
                                   type="<?php echo $question->getAnswerType() === 'single' ? 'radio' : 'checkbox'; ?>"
                                   name="question_<?php echo $view->quiz->getId(); ?>_<?php echo $question->getId(); ?>"
                                   value="<?php echo($answer_index + 1); ?>"> <?php echo $answer_text; ?>
                        </label>

                    <?php } else {
                        if ($question->getAnswerType() === 'sort_answer') { ?>
                            <div class="quizMaster_sortable">
                                <?php echo $answer_text; ?>
                            </div>
                        <?php } else {
                            if ($question->getAnswerType() === 'free_answer') { ?>
                                <?php
                                  print quizmaster_get_template( 'quiz-free-choice.php', array( 'view' => $view, 'question' => $question ) );
                                ?>

                            <?php } else {
                                if ($question->getAnswerType() === 'matrix_sort_answer') { ?>
                                    <?php
                                    $msacwValue = $question->getMatrixSortAnswerCriteriaWidth() > 0 ? $question->getMatrixSortAnswerCriteriaWidth() : 20;
                                    ?>
                                    <table>
                                        <tbody>
                                        <tr class="quizMaster_mextrixTr">
                                            <td width="<?php echo $msacwValue; ?>%">
                                                <div
                                                    class="quizMaster_maxtrixSortText"><?php echo $answer_text; ?></div>
                                            </td>
                                            <td width="<?php echo 100 - $msacwValue; ?>%">
                                                <ul class="quizMaster_maxtrixSortCriterion"></ul>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                <?php } else {
                                    if ($question->getAnswerType() === 'cloze_answer') {
                                        $clozeData = $view->fetchCloze($v->getAnswer());

                                        $view->_clozeTemp = $clozeData['data'];
                                        $cloze = do_shortcode(apply_filters('comment_text',
                                            $clozeData['replace']));
                                        $cloze = $clozeData['replace'];

                                        echo preg_replace_callback('#@@quizMasterCloze@@#im',
                                            array($view, 'clozeCallback'), $cloze);
                                    } else {
                                        if ($question->getAnswerType() === 'assessment_answer') {
                                            $assessmentData = $view->fetchAssessment($v->getAnswer(),
                                                $view->quiz->getId(), $question->getId());

                                            $assessment = do_shortcode(apply_filters('comment_text',
                                                $assessmentData['replace']));

                                            echo preg_replace_callback('#@@quizMasterAssessment@@#im',
                                                array($view, 'assessmentCallback'), $assessment);

                                        }
                                    }
                                }
                            }
                        }
                    } ?>
                </li>
                <?php
                $answer_index++;
            }
            ?>
        </ul>
    </div>
    <?php if (!$view->quiz->isHideAnswerMessageBox()) { ?>
        <div class="quizMaster_response" style="display: none;">
            <div style="display: none;" class="quizMaster_correct">
                <?php if ($question->isShowPointsInBox() && $question->isAnswerPointsActivated()) { ?>
                    <div>
<span style="float: left;" class="quizMaster_respone_span">
<?php _e('Correct', 'quizmaster'); ?>
</span>
                        <span
                            style="float: right;"><?php echo $question->getPoints() . ' / ' . $question->getPoints(); ?><?php _e('Points',
                                'quizmaster'); ?></span>

                        <div style="clear: both;"></div>
                    </div>
                <?php } else { ?>
                    <span class="quizMaster_respone_span">
<?php _e('Correct', 'quizmaster'); ?>
</span><br>
                <?php }
                $_correctMsg = trim(do_shortcode(apply_filters('comment_text',
                    $question->getCorrectMsg())));

                if (strpos($_correctMsg, '<p') === 0) {
                    echo $_correctMsg;
                } else {
                    echo '<p>', $_correctMsg, '</p>';
                }
                ?>
            </div>
            <div style="display: none;" class="quizMaster_incorrect">
                <?php if ($question->isShowPointsInBox() && $question->isAnswerPointsActivated()) { ?>
                    <div>
<span style="float: left;" class="quizMaster_respone_span">
<?php _e('Incorrect', 'quizmaster'); ?>
</span>
                        <span style="float: right;"><span
                                class="quizMaster_responsePoints"></span> / <?php echo $question->getPoints(); ?> <?php _e('Points',
                                'quizmaster'); ?></span>

                        <div style="clear: both;"></div>
                    </div>
                <?php } else { ?>
                    <span class="quizMaster_respone_span">
<?php _e('Incorrect', 'quizmaster'); ?>
</span><br>
                <?php }

                if ($question->isCorrectSameText()) {
                    $_incorrectMsg = do_shortcode(apply_filters('comment_text',
                        $question->getCorrectMsg()));
                } else {
                    $_incorrectMsg = do_shortcode(apply_filters('comment_text',
                        $question->getIncorrectMsg()));
                }

                if (strpos($_incorrectMsg, '<p') === 0) {
                    echo $_incorrectMsg;
                } else {
                    echo '<p>', $_incorrectMsg, '</p>';
                }

                ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($question->isTipEnabled()) { ?>
        <div class="quizMaster_tipp" style="display: none; position: relative;">
            <div>
                <h5 style="margin: 0 0 10px;" class="quizMaster_header"><?php _e('Hint',
                        'quizmaster'); ?></h5>
                <?php echo do_shortcode(apply_filters('comment_text', $question->getTipMsg())); ?>
            </div>
        </div>
    <?php } ?>

    <?php

    if ($view->quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_CHECK && !$view->quiz->isSkipQuestionDisabled() && $view->quiz->isShowReviewQuestion()) {
        print quizmaster_get_template( 'quiz-button-skip.php' );
    }

      print quizmaster_get_template( 'quiz-button-back.php' );

      if ($question->isTipEnabled()) {
        print quizmaster_get_template( 'quiz-button-hint.php' );
      }
    ?>

    <?php print quizmaster_get_template( 'quiz-button-check-next.php' ); ?>

    <div style="clear: both;"></div>

    <?php if ($view->quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
        <div style="margin-bottom: 20px;"></div>
    <?php } ?>

</li>

<?php
  } ?>
</ol>

<?php
    if ($view->quiz->getQuizModus() == QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
        <div>
          <?php
            print quizmaster_get_template( 'quiz-button-left-right.php' );
            if ($view->quiz->isShowReviewQuestion() && !$view->quiz->isQuizSummaryHide()) {
              print quizmaster_get_template( 'quiz-button-summary.php', array('view' => $view));
            } else {
              print quizmaster_get_template( 'quiz-button-finish.php', array('view' => $view));
            }
          ?>

          <div style="clear: both;"></div>
        </div>
    <?php } ?>
</div>
