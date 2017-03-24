<div class="quizMaster_quiz">
  <ol class="quizMaster_list">

    <li class="quizMaster_listItem">
        <h5 class="quizMaster_header">
          <?php _e('Question', 'quizmaster'); ?>
        </h5>

        <span style="font-weight: bold; float: right;"><?php printf(__('%d points', 'quizmaster'),
                $question->getPoints()); ?></span>
        <div style="clear: both;"></div>

        <?php

        if ($question->getCategoryId()) { ?>
            <div style="font-weight: bold; padding-top: 5px;">
                <?php printf(__('Category: %s', 'quizmaster'),
                    esc_html($question->getCategoryName())); ?>
            </div>
        <?php } ?>
        <div class="quizMaster_question" style="margin: 10px 0 0 0;">
            <div class="quizMaster_question_text">
                <?php echo do_shortcode(apply_filters('comment_text', $question->getQuestion())); ?>
            </div>
            <ul class="quizMaster_questionList" data-question_id="<?php echo $question->getId(); ?>"
                data-type="<?php echo $question->getAnswerType(); ?>">
                <?php
                $answer_index = 0;

                foreach ($question->getAnswerData() as $v) :
                    $answer_text = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());
                    ?>

                    <li class="quizMaster_questionListItem" data-pos="<?php echo $answer_index; ?>">
                      <span></span>
                      <label>
                          <input class="quizMaster_questionInput"
                             type="<?php echo $question->getAnswerType() === 'single' ? 'radio' : 'checkbox'; ?>"
                             name="question_<?php echo $question->getId(); ?>"
                             value="<?php echo($answer_index + 1); ?>"> <?php echo $answer_text; ?>
                      </label>
                    </li>

                <?php endforeach; ?>

                </div>
            </div>

        <div style="clear: both;"></div>

    </li>

  </ol>
</div>
