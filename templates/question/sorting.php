<!-- Question Category -->
<?php
  print quizmaster_get_template( 'quiz/category.php', array( 'question' => $question ));
?>

<!-- Question Points -->
<?php
  print quizmaster_get_template( 'quiz/question-points.php', array( 'question' => $question, 'quiz' => $quiz ));
?>

<div class="quizMaster_question">

  <div class="qm-question-text">
    <?php echo do_shortcode(apply_filters('comment_text', $question->getQuestion())); ?>
  </div>

  <ul class="qm-question-list" data-question_id="<?php echo $question->getId(); ?>"
    data-type="<?php echo $question->getAnswerType(); ?>">

    <?php
      $answer_index = 0;
      foreach ($question->getAnswerData() as $v) :
        $answer_text = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());
    ?>

      <li class="qm-question-list-item" data-pos="<?php echo $answer_index; ?>">
        <div class="quizMaster_sortable">
          <?php echo $answer_text; ?>
        </div>
      </li>

      <?php $answer_index++; endforeach;?>

  </ul>

  <?php print quizmaster_get_template('quiz/question-response.php', array('question' => $question)); ?>

</div>
