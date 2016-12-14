<?php

/**
 * @property QuizMaster_Model_Quiz quiz
 * @property bool inQuiz
 * @property int points
 */
class QuizMaster_View_FrontToplist extends QuizMaster_View_View
{

    public function show()
    {
        ?>
        <div style="margin-bottom: 30px; margin-top: 10px;" class="quizMaster_toplist"
             data-quiz_id="<?php echo $this->quiz->getId(); ?>">
            <?php if (!$this->inQuiz) { ?>
                <h2><?php _e('Leaderboard', 'quizmaster'); ?>: <?php echo $this->quiz->getName(); ?></h2>
            <?php } ?>
            <table class="quizMaster_toplistTable">
                <caption><?php printf(__('maximum of %s points', 'quizmaster'), $this->points); ?></caption>
                <thead>
                <tr>
                    <th style="width: 40px;"><?php _e('Pos.', 'quizmaster'); ?></th>
                    <th style="text-align: left !important;"><?php _e('Name', 'quizmaster'); ?></th>
                    <th style="width: 140px;"><?php _e('Entered on', 'quizmaster'); ?></th>
                    <th style="width: 60px;"><?php _e('Points', 'quizmaster'); ?></th>
                    <th style="width: 75px;"><?php _e('Result', 'quizmaster'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5"><?php _e('Table is loading', 'quizmaster'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td colspan="5"><?php _e('No data available', 'quizmaster'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td></td>
                    <td style="text-align: left !important;"></td>
                    <td style=" color: rgb(124, 124, 124); font-size: x-small;"></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php
    }
}