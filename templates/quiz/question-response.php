<div class="quizMaster_response" style="display: none;">
    <div style="display: none;" class="quizMaster_correct">
        <?php if ($question->isShowPointsInBox() && $question->isAnswerPointsActivated()) { ?>
            <div>
              <span style="float: left;" class="quizMaster_respone_span">
              <?php _e('Correct', 'quizmaster'); ?>
              </span>
              <span style="float: right;"><?php echo $question->getPoints() . ' / ' . $question->getPoints(); ?><?php _e('Points', 'quizmaster'); ?></span>
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
