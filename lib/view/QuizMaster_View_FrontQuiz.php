<?php

/**
 * @property QuizMaster_Model_Quiz quiz
 * @property QuizMaster_Model_Question[] question
 * @property QuizMaster_Model_Category[] category
 */
class QuizMaster_View_FrontQuiz extends QuizMaster_View_View {

    public $_clozeTemp = array();
    public $_assessmetTemp = array();
    public $_buttonNames = array();

		public function renderExtensionQuizBoxes() {
			do_action( 'quizmaster_render_quiz_box', $this );
		}

    public function loadButtonNames()
    {
        if (!empty($this->_buttonNames)) {
            return;
        }

        $names = array(
            'start_quiz'      => __('Start quiz', 'quizmaster'),
            'restart_quiz'    => __('Restart quiz', 'quizmaster'),
            'quiz_summary'    => __('Quiz Summary', 'quizmaster'),
            'finish_quiz'     => __('Finish quiz', 'quizmaster'),
            'quiz_is_loading' => __('Quiz is loading...', 'quizmaster'),
            'lock_box_msg'    => __('You have already completed the quiz before and only 1 attempt is allowed.', 'quizmaster'),
            'only_registered_user_msg'  => __('You must sign in or sign up to start the quiz.', 'quizmaster'),
            'prerequisite_msg'          => __('You have to finish following quiz, to start this quiz:', 'quizmaster'),
        );

        $this->_buttonNames = apply_filters('quizmaster_button_names', $names, $this);
    }

    /**
     * @param $data QuizMaster_Model_AnswerTypes
     *
     * @return array
     */
    public function getFreeCorrect($data)
    {
        $t = str_replace("\r\n", "\n", strtolower($data->getAnswer()));
        $t = str_replace("\r", "\n", $t);
        $t = explode("\n", $t);

        return array_values(array_filter(array_map('trim', $t), array($this, 'removeEmptyElements')));
    }

    public function removeEmptyElements($v)
    {
        return !empty($v) || $v === '0';
    }

    public function show() {

      $this->loadButtonNames();
      $this->question_count = count($this->question);
      $this->result = $this->quiz->getResultText();

      // handle graduations
      if ( $this->quiz->isResultGradeEnabled() ) {
        $this->result = array(
          'text' => array($this->result),
          'prozent' => array(0)
        );

        $this->resultsProzent = json_encode( $this->result['prozent'] );
      } else {
        $this->resultsProzent = array(0);
      }

      return quizmaster_parse_template( 'front-quiz.php', array('view' => $this));

    }

    public function createOption($preview)
    {
        $bo = 0;

        $bo |= ((int)$this->quiz->isAnswerRandom()) << 0;
        $bo |= ((int)$this->quiz->isQuestionRandom()) << 1;
        $bo |= ((int)$this->quiz->isDisabledAnswerMark()) << 2;
        $bo |= ((int)($this->quiz->isQuizRunOnce() || $this->quiz->isPrerequisite() || $this->quiz->isStartOnlyRegisteredUser() )) << 3;
        $bo |= ((int)$preview) << 4;
        $bo |= ((int)get_option('quizMaster_corsActivated')) << 5;
        $bo |= ((int)$this->quiz->isShowReviewQuestion()) << 7;
        $bo |= ((int)$this->quiz->isQuizSummaryHide()) << 8;
        $bo |= ((int)(!$this->quiz->isSkipQuestionDisabled() && $this->quiz->isShowReviewQuestion())) << 9;
        $bo |= ((int)$this->quiz->isAutostart()) << 10;
        $bo |= ((int)$this->quiz->isForcingQuestionSolve()) << 11;
        $bo |= ((int)$this->quiz->isHideQuestionPositionOverview()) << 12;
        $bo |= ((int)$this->quiz->isFormActivated()) << 13;
        $bo |= ((int)$this->quiz->isShowMaxQuestion()) << 14;
        $bo |= ((int)$this->quiz->isSortCategories()) << 15;

        return $bo;
    }

    public function showMaxQuestion() {

        $this->loadButtonNames();
        $question_count = count($this->question);
        $result = $this->quiz->getResultText();

        ?>
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
            $this->showReviewBox($question_count);
            $this->showQuizAnker();
            ?>
        </div>
        <?php

        $bo = $this->createOption(false);

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#quizMaster_<?php echo $this->quiz->getId(); ?>').quizMasterFront({
                    quizId: <?php echo (int)$this->quiz->getId(); ?>,
                    mode: <?php echo (int)$this->quiz->getQuizModus(); ?>,
                    timelimit: <?php echo (int)$this->quiz->getTimeLimit(); ?>,
                    bo: <?php echo $bo ?>,
                    qpp: <?php echo $this->quiz->getQuestionsPerPage(); ?>,
                    formPos: <?php echo (int)$this->quiz->getFormShowPosition(); ?>,
                    lbn: <?php echo json_encode(($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide()) ? $this->_buttonNames['quiz_summary'] : $this->_buttonNames['finish_quiz']); ?>
                });
            });
        </script>
        <?php
    }

    public function getQuizData()
    {
        ob_start();

        $this->loadButtonNames();

        $quizData = $this->showQuizBox(count($this->question));

        $quizData['content'] = ob_get_contents();

        ob_end_clean();

        return $quizData;
    }

    public function showQuizAnker()
    {
        ?>
        <div class="quizMaster_quizAnker" style="display: none;"></div>
        <?php
    }

    public function fetchAssessment($answerText, $quizId, $questionId)
    {
        preg_match_all('#\{(.*?)\}#im', $answerText, $matches);

        $this->_assessmetTemp = array();
        $data = array();

        for ($i = 0, $ci = count($matches[1]); $i < $ci; $i++) {
            $match = $matches[1][$i];

            preg_match_all('#\[([^\|\]]+)(?:\|(\d+))?\]#im', $match, $ms);

            $a = '';

            for ($j = 0, $cj = count($ms[1]); $j < $cj; $j++) {
                $v = $ms[1][$j];

                $a .= '<label>
					<input type="radio" value="' . ($j + 1) . '" name="question_' . $quizId . '_' . $questionId . '_' . $i . '" class="quizMaster_questionInput" data-index="' . $i . '">
					' . $v . '
				</label>';

            }

            $this->_assessmetTemp[] = $a;
        }

        $data['replace'] = preg_replace('#\{(.*?)\}#im', '@@quizMasterAssessment@@', $answerText);

        return $data;
    }

    public function assessmentCallback($t)
    {
        $a = array_shift($this->_assessmetTemp);

        return $a === null ? '' : $a;
    }

    public function showLockBox()
    {
        ?>
        <div style="display: none;" class="quizMaster_lock">
            <p>
                <?php echo $this->_buttonNames['lock_box_msg']; ?>
            </p>
        </div>
        <?php
    }

    public function showStartOnlyRegisteredUserBox() {
        ?>
        <div style="display: none;" class="quizMaster_startOnlyRegisteredUser">
            <p>
                <?php echo $this->_buttonNames['only_registered_user_msg']; ?>
            </p>
        </div>
        <?php
    }

    public function showPrerequisiteBox()
    {
        ?>
        <div style="display: none;" class="quizMaster_prerequisite">
            <p>
                <?php echo $this->_buttonNames['prerequisite_msg']; ?>
                <span></span>
            </p>
        </div>
        <?php
    }

    public function showCheckPageBox($questionCount)
    {
        ?>
        <div class="quizMaster_checkPage" style="display: none;">
            <h4 class="quizMaster_header"><?php echo $this->_buttonNames['quiz_summary']; ?></h4>

            <p>
                <?php printf(__('%s of %s questions completed', 'quizmaster'), '<span>0</span>', $questionCount); ?>
            </p>

            <p><?php _e('Questions', 'quizmaster'); ?>:</p>

            <div style="margin-bottom: 20px;" class="quizMaster_box">
                <ol>
                    <?php for ($xy = 1; $xy <= $questionCount; $xy++) { ?>
                        <li><?php echo $xy; ?></li>
                    <?php } ?>
                </ol>
                <div style="clear: both;"></div>
            </div>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == QuizMaster_Model_Quiz::QUIZ_FORM_POSITION_END
                && ($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide())
            ) {

                ?>
                <h4 class="quizMaster_header"><?php _e('Information', 'quizmaster'); ?></h4>
                <?php
            }

            ?>

            <input type="button" name="endQuizSummary" value="<?php echo $this->_buttonNames['finish_quiz']; ?>"
                   class="quizMaster_button">
        </div>
        <?php
    }

    public function showInfoPageBox()
    {
        ?>
        <div class="quizMaster_infopage" style="display: none;">
            <h4><?php _e('Information', 'quizmaster'); ?></h4>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == QuizMaster_Model_Quiz::QUIZ_FORM_POSITION_END
                && (!$this->quiz->isShowReviewQuestion() || $this->quiz->isQuizSummaryHide())
            ) {
                $this->showFormBox();
            }

            ?>

            <input type="button" name="endInfopage" value="<?php echo $this->_buttonNames['finish_quiz']; ?>"
                   class="quizMaster_button">
        </div>
        <?php
    }

    public function showStartQuizBox()
    {
        ?>
        <div class="quizMaster_text">
            <p>
                <?php echo do_shortcode(apply_filters('comment_text', $this->quiz->getText())); ?>
            </p>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == QuizMaster_Model_Quiz::QUIZ_FORM_POSITION_START) {
                $this->showFormBox();
            }
            ?>

            <div>
                <input class="quizMaster_button" type="button" value="<?php echo $this->_buttonNames['start_quiz']; ?>"
                       name="startQuiz">
            </div>
        </div>
        <?php
    }

    public function showTimeLimitBox()
    {
        ?>
        <div style="display: none;" class="quizMaster_time_limit">
            <div class="time"><?php _e('Time limit', 'quizmaster'); ?>: <span>0</span></div>
            <div class="quizMaster_progress"></div>
        </div>
        <?php
    }

    public function showReviewBox($questionCount)
    {
        ?>
        <div class="quizMaster_reviewDiv" style="display: none;">
            <div class="quizMaster_reviewQuestion">
                <ol>
                    <?php for ($xy = 1; $xy <= $questionCount; $xy++) { ?>
                        <li><?php echo $xy; ?></li>
                    <?php } ?>
                </ol>
                <div style="display: none;"></div>
            </div>
            <div class="quizMaster_reviewLegend">
                <ol>
                    <li>
                        <span class="quizMaster_reviewColor" style="background-color: #6CA54C;"></span>
                        <span class="quizMaster_reviewText"><?php _e('Answered', 'quizmaster'); ?></span>
                    </li>
                    <li>
                        <span class="quizMaster_reviewColor" style="background-color: #FFB800;"></span>
                        <span class="quizMaster_reviewText"><?php _e('Review', 'quizmaster'); ?></span>
                    </li>
                </ol>
                <div style="clear: both;"></div>
            </div>
            <div>
                <?php if ($this->quiz->getQuizModus() != QuizMaster_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
                    <input type="button" name="review" value="<?php _e('Review question', 'quizmaster'); ?>"
                           class="quizMaster_button2" style="float: left; display: block;">
                    <?php if (!$this->quiz->isQuizSummaryHide()) { ?>
                        <input type="button" name="quizSummary"
                               value="<?php echo $this->_buttonNames['quiz_summary']; ?>" class="quizMaster_button2"
                               style="float: right;">
                    <?php } ?>
                    <div style="clear: both;"></div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    public function showResultBox($result, $questionCount) {
        ?>
        <div style="display: none;" class="quizMaster_results">
            <h4 class="quizMaster_header"><?php _e('Results', 'quizmaster'); ?></h4>
            <?php if (!$this->quiz->isHideResultCorrectQuestion()) { ?>
                <p>
                    <?php printf(__('%s of %s questions answered correctly', 'quizmaster'),
                        '<span class="quizMaster_correct_answer">0</span>', '<span>' . $questionCount . '</span>'); ?>
                </p>
            <?php }
            if (!$this->quiz->isHideResultQuizTime()) { ?>
                <p class="quizMaster_quiz_time">
                    <?php _e('Your time: <span></span>', 'quizmaster'); ?>
                </p>
            <?php } ?>
            <p class="quizMaster_time_limit_expired" style="display: none;">
                <?php _e('Time has elapsed', 'quizmaster'); ?>
            </p>
            <?php if (!$this->quiz->isHideResultPoints()) { ?>
                <p class="quizMaster_points">
                    <?php printf(__('You have reached %s of %s points, (%s)', 'quizmaster'), '<span>0</span>',
                        '<span>0</span>', '<span>0</span>'); ?>
                </p>
            <?php } ?>
            <?php if ($this->quiz->isShowAverageResult()) { ?>
                <div class="quizMaster_resultTable">
                    <table>
                        <tbody>
                        <tr>
                            <td class="quizMaster_resultName"><?php _e('Average score', 'quizmaster'); ?></td>
                            <td class="quizMaster_resultValue">
                                <div style="background-color: #6CA54C;">&nbsp;</div>
                                <span>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="quizMaster_resultName"><?php _e('Your score', 'quizmaster'); ?></td>
                            <td class="quizMaster_resultValue">
                                <div style="background-color: #F79646;">&nbsp;</div>
                                <span>&nbsp;</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <div class="quizMaster_catOverview" <?php $this->isDisplayNone($this->quiz->isShowCategoryScore()); ?>>
                <h4><?php _e('Categories', 'quizmaster'); ?></h4>

                <div style="margin-top: 10px;">
                    <ol>

                      <li data-category_id="0">
                        <span class="quizMaster_catName"><?php print __('Uncategorized', 'quizmaster') ?></span>
                        <span class="quizMaster_catPercent">0%</span>
                      </li>

                      <?php
                        if( !empty( $this->category )) :
                          foreach ( $this->category as $catId ) { ?>
                            <li data-category_id="<?php echo $catId; ?>">
                              <span class="quizMaster_catName"><?php echo get_term( $catId )->name; ?></span>
                              <span class="quizMaster_catPercent">0%</span>
                            </li>
                      <?php } endif; ?>
                    </ol>
                </div>
            </div>
            <div>
                <ul class="quizMaster_resultsList">
                  <?php if(!empty( $result['text'] )): foreach ($result['text'] as $resultText) { ?>
                      <li style="display: none;">
                          <div>
                              <?php echo do_shortcode(apply_filters('comment_text', $resultText)); ?>
                          </div>
                      </li>
                  <?php } endif; ?>
                </ul>
            </div>

            <div style="margin: 10px 0px;">
                <?php if (!$this->quiz->isBtnRestartQuizHidden()) { ?>
                    <input class="quizMaster_button" type="button" name="restartQuiz"
                           value="<?php echo $this->_buttonNames['restart_quiz']; ?>">
                <?php }
                if (!$this->quiz->isBtnViewQuestionHidden()) { ?>
                    <input class="quizMaster_button" type="button" name="reShowQuestion"
                           value="<?php _e('View questions', 'quizmaster'); ?>">
                <?php } ?>
            </div>
        </div>
        <?php
    }

    public function showQuizBox( $questionCount ) {

			quizmaster_get_template('quiz/header.php',
        array(
          'view'          => $this,
          'questionCount' => $questionCount,
        )
      );

      quizmaster_get_template('quiz-question-item.php',
        array(
          'view'          => $this,
          'questionCount' => $questionCount,
        )
      );

			quizmaster_get_template('quiz/footer.php',
        array(
          'view'          => $this,
					'quiz'					=> $this->quiz,
          'questionCount' => $questionCount,
        )
      );

      $globalPoints = $this->setGlobalPoints( $this->question );
      $json = $this->setQuizJson( $this->question );

      $catPoints = $this->quiz->fetchQuestionCategoryPoints();

      return array( 'globalPoints' => $globalPoints, 'json' => $json, 'catPoints' => $catPoints );

    }

    public function setGlobalPoints( $questions ) {
      $globalPoints = 0;
      foreach ($questions as $question) {
        $answerArray = $question->getAnswerData();
        $globalPoints += $question->getPoints();
      }
      return $globalPoints;
    }

    public function setQuizJson( $questions ) {
      $json = array();
      foreach ($questions as $question) {
        $answerArray = $question->getAnswerData();

        $json[$question->getId()]['type'] = $question->getAnswerType();
        $json[$question->getId()]['id'] = (int)$question->getId();
        $json[$question->getId()]['catId'] = (int)$question->getCategoryId();
        if ($question->isAnswerPointsActivated() && $question->isAnswerPointsDiffModusActivated() && $question->isDisableCorrect()) {
          $json[$question->getId()]['disCorrect'] = (int)$question->isDisableCorrect();
        }
        if (!$question->isAnswerPointsActivated()) {
          $json[$question->getId()]['points'] = $question->getPoints();
        }
        if ($question->isAnswerPointsActivated() && $question->isAnswerPointsDiffModusActivated()) {
          $json[$question->getId()]['diffMode'] = 1;
        }

        $answer_index = 0;
        foreach ($answerArray as $v) {
          if ($question->isAnswerPointsActivated()) {
            $json[$question->getId()]['points'][] = $v->getPoints();
          }

          // single or multiple
          if ($question->getAnswerType() === 'single' || $question->getAnswerType() === 'multiple') {
            $json[$question->getId()]['correct'][] = (int)$v->isCorrect();
            if ($question->getAnswerType() === 'sort_answer') {
              $json[$question->getId()]['correct'][] = (int)$answer_index;
            }
          }

          // free answer
          if ($question->getAnswerType() === 'free_answer') {
            $json[$question->getId()]['correct'] = $this->getFreeCorrect($v);
          }

          // matrix
          if ($question->getAnswerType() === 'matrix_sort_answer') {
            $json[$question->getId()]['correct'][] = (int)$answer_index;
          }

          // cloze
          if ($question->getAnswerType() === 'cloze_answer') {
            $clozeData = $question->fetchCloze($v->getAnswer());
            $json[$question->getId()]['correct'] = $clozeData['correct'];
            if ($question->isAnswerPointsActivated()) {
              $json[$question->getId()]['points'] = $clozeData['points'];
            }
          }

          $answer_index++;
        }
      }
      return $json;
    }

    public function showLoadQuizBox() {
      quizmaster_get_template('quiz-load-box.php', array( 'view' => $this ));
    }

		public function showStaticHeaderMessage() {

			if( $this->quiz->getStaticHeaderMessage() !== '' ) {
				return true;
			}

			return false;

		}

}
