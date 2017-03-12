<?php

// category handling
$cats = array();
foreach( $scoreView->getScoreQuestions() as $scoreQuestion ) {

	$question = new QuizMaster_Model_Question( $scoreQuestion->getQuestionId() );
	$catId = $question->getCategoryId();
	if( !$catId ) {
		$catId = 0;
	}
	$cats[ $catId ][] = array(
		'question' => $question,
		'score' => $scoreQuestion,
	);

	print '<pre>';
	// var_dump( $scoreQuestion );
	//var_dump( $question );
	//var_dump( $question->getCategoryId() );
	//var_dump( $question->getCategoryName() );
	print '</pre>';

}

?>

<!-- Categorized Scores Table -->
<table>
  <tr>
    <th>Question</th>
    <th>Points Earned</th>
    <th>Correct Questions</th>
    <th>Hints Used</th>
    <th>Solved</th>
    <th>Time</th>
  </tr>

  <?php foreach( $cats as $cat ) : ?>

    <tr>
      <th colspan="6">Category: <?php print $cat[0]['question']->getCategoryName(); ?></th>
    </tr>

	  <?php foreach( $scoreView->getScoreQuestions() as $scoreQuestion ) :

	    $scoreView->setActiveScoreQuestion( $scoreQuestion );

	  ?>

    <tr>
      <td><?php print $scoreView->getQuestion(); ?></td>
      <td><?php print $scoreView->getPoints() . '/' . $scoreView->getPossiblePoints(); ?></td>
			<td><?php print $scoreView->isCorrect(); ?></td>
      <td><?php print $scoreView->getHintCount(); ?></td>
      <td><?php print $scoreView->getSolvedCount(); ?></td>
      <td><?php print $scoreView->getQuestionTime(); ?></td>
    </tr>

  <?php endforeach; ?>

  <!-- Subotal Row -->
  <tr>
    <th><?php _e('Subtotal', 'quizmaster'); ?></th>
    <th><?php print $scoreView->getPoints() . '/' . $scoreView->getPossiblePoints(); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'correctCount' ) . '/' . $scoreView->getScoreTotal( 'incorrectCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'hintCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'solvedCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'questionTime' ); ?></th>
  </tr>

  <?php endforeach; ?>

  <tfoot>

  	<!-- Totals Row -->
    <tr>
      <th><?php _e('Total', 'quizmaster'); ?></th>
      <th><?php print $scoreModel->getTotalPointsEarned() . '/' . $scoreModel->getTotalPointsPossible(); ?></th>
      <th><?php print $scoreModel->getCorrectRatio(); ?></th>
      <th><?php print $scoreModel->getTotalHints(); ?></th>
      <th><?php print $scoreModel->getTotalSolved(); ?></th>
      <th><?php print $scoreModel->getTotalTime(); ?></th>
    </tr>

  </tfoot>

</table>
