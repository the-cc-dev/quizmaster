<h2>Quiz Scores</h2>

<table id="quizmaster_score_table" class="display">
  <thead>
    <tr>
      <th>Quiz</th>
      <th>Taken At</th>
      <th>Points</th>
      <th>Correct</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach( $scores as $score ) : ?>
      <tr>
        <td><?php print $view->getQuizTitle( $score ); ?></td>
        <td><?php print $score->getDate(); ?></td>
        <td><?php print $score->getPointsEarned(); ?></td>
        <td><?php print $score->getCorrectRatio() ?></td>
        <td><?php print $view->getLink( $score, "View Details"); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
