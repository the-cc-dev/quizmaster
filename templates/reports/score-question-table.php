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
    <th>Points</th>
    <th>Correct</th>
    <th>Incorrect</th>
    <th>Hints Used</th>
    <th>Solved</th>
    <th>Time</th>
  </tr>

  <?php foreach( $cats as $cat ) : ?>

    <tr>
      <th colspan="7">Category: <?php print $cat[0]['question']->getCategoryName(); ?></th>
    </tr>

  <?php foreach( $scoreView->getScoreQuestions() as $scoreQuestion ) :

    $scoreView->setActiveScoreQuestion( $scoreQuestion );

  ?>
    <tr>
      <td><?php print $scoreView->getQuestion(); ?></td>
      <td><?php print $scoreView->getPoints() . '/' . $scoreView->getPossiblePoints(); ?></td>
      <td><?php print $scoreView->getCorrectCount(); ?></td>
      <td><?php print $scoreView->getHintCount(); ?></td>
      <td><?php print $scoreView->getSolvedCount(); ?></td>
      <td><?php print $scoreView->getQuestionTime(); ?></td>
    </tr>

  <?php endforeach; ?>

  <!-- Subotal Row -->
  <tr>
    <th><?php _e('Subtotal', 'quizmaster'); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'possiblePoints' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'correctCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'incorrectCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'hintCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'solvedCount' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'questionTime' ); ?></th>
    <th><?php print $scoreView->getScoreTotal( 'points' ); ?></th>
  </tr>

  <?php endforeach; ?>

  <!-- Totals Row -->
  <tfoot>
    <tr>
      <th><?php _e('Total', 'quizmaster'); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'possiblePoints' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'correctCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'incorrectCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'hintCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'solvedCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'questionTime' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'points' ); ?></th>
    </tr>
  </tfoot>

</table>

<!-- Score Details Table -->
<table>
  <tr>
    <th>Question</th>
    <th>Points</th>
    <th>Correct</th>
    <th>Incorrect</th>
    <th>Hints Used</th>
    <th>Solved</th>
    <th>Time</th>
  </tr>

  <?php foreach( $scoreView->getScoreQuestions() as $scoreQuestion ) :
    $scoreView->setActiveScoreQuestion( $scoreQuestion );

  ?>
    <tr>
      <td><?php print $scoreView->getQuestion(); ?></td>
      <td><?php print $scoreView->getPoints() . '/' . $scoreView->getPossiblePoints(); ?></td>
      <td><?php print $scoreView->getCorrectCount(); ?></td>
      <td><?php print $scoreView->getHintCount(); ?></td>
      <td><?php print $scoreView->getSolvedCount(); ?></td>
      <td><?php print $scoreView->getQuestionTime(); ?></td>
    </tr>
  <?php endforeach; ?>

  <!-- Totals Row -->
  <tfoot>
    <tr>
      <th><?php _e('Total', 'quizmaster'); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'possiblePoints' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'correctCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'incorrectCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'hintCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'solvedCount' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'questionTime' ); ?></th>
      <th><?php print $scoreView->getScoreTotal( 'points' ); ?></th>
    </tr>
  </tfoot>
</table>
