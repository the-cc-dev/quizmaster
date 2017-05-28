<!-- Question Category -->
<?php
  print quizmaster_get_template( 'quiz/category.php', array( 'question' => $question ));
?>

<!-- Question Points -->
<?php
  print quizmaster_get_template( 'quiz/question-points.php', array( 'question' => $question, 'quiz' => $quiz ));
?>

<div class="quizMaster_question">

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
                   type="checkbox"
                   name="question_<?php echo $question->getId(); ?>"
                   value="<?php echo($answer_index + 1); ?>"> <?php echo $answer_text; ?>
            </label>
          </li>

    <?php $answer_index++; endforeach; ?>

  </ul>

    </div>
</div>
