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

    if ( $question->getCategoryId() ) { ?>
      <div class="quizmaster-category-name">
        <?php printf(__('Category: %s', 'quizmaster'), esc_html($question->getCategoryName())); ?>
      </div>
    <?php } ?>

    <div class="quizMaster_question">
      <div class="quizMaster_question_text">
        <?php print $question->getQuestion(); ?>
      </div>
      <ul class="quizMaster_questionList" data-question_id="<?php echo $question->getId(); ?>"
          data-type="<?php echo $question->getAnswerType(); ?>">
        <?php
          $answer_index = 0;
          foreach ($question->getAnswerData() as $v) {
            $answer_text = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());

            if ($answer_text == '') {
              continue;
            }
          }
        ?>

        <li class="quizMaster_questionListItem" data-pos="<?php echo $answer_index; ?>">
          <label>
              <input class="quizMaster_questionInput" type="text"
                     name="question_<?php echo $question->getId(); ?>"
                     style="width: 300px;">
          </label>
        </li>
      </ul>
    </div>

  </li>
</ol>
