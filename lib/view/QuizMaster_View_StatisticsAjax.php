<?php

/**
 * @property QuizMaster_Model_StatisticHistory[] historyModel
 * @property QuizMaster_Model_Form[] forms
 * @property bool avg
 * @property QuizMaster_Model_StatisticRefModel statisticModel
 * @property string userName
 * @property array userStatistic
 */
class QuizMaster_View_StatisticsAjax extends QuizMaster_View_View
{

    public function getHistoryTable()
    {
        ob_start();

        $this->showHistoryTable();

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function showHistoryTable()
    {
        ?>

        <table class="wp-list-table widefat">
            <thead>
            <tr>
                <th scope="col"><?php _e('Username', 'quizmaster'); ?></th>

                <?php foreach ($this->forms as $form) {
                    /* @var $form QuizMaster_Model_Form */
                    if ($form->isShowInStatistic()) {
                        echo '<th scope="col">' . $form->getFieldname() . '</th>';
                    }
                } ?>

                <th scope="col" style="width: 200px;"><?php _e('Date', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Correct', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Incorrect', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Solved', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Points', 'quizmaster'); ?></th>
                <th scope="col" style="width: 60px;"><?php _e('Results', 'quizmaster'); ?></th>
            </tr>
            </thead>
            <tbody id="quizMaster_statistics_form_data">
            <?php if (!count($this->historyModel)) { ?>
                <tr>
                    <td colspan="6"
                        style="text-align: center; font-weight: bold; padding: 10px;"><?php _e('No data available',
                            'quizmaster'); ?></td>
                </tr>
            <?php } else { ?>
                <?php foreach ($this->historyModel as $model) {
                    /* @var $model QuizMaster_Model_StatisticHistory */ ?>
                    <tr>
                        <th>
                            <a href="#" class="user_statistic"
                               data-ref_id="<?php echo $model->getStatisticRefId(); ?>"><?php echo $model->getUserName(); ?></a>

                            <div class="row-actions">
							<span>
								<a style="color: red;" class="quizMaster_delete" href="#"><?php _e('Delete',
                                        'quizmaster'); ?></a>
							</span>
                            </div>

                        </th>
                        <?php foreach ($model->getFormOverview() as $form) {
                            echo '<th>' . esc_html($form) . '</th>';
                        } ?>
                        <th><?php echo $model->getFormatTime(); ?></th>
                        <th style="color: green;"><?php echo $model->getFormatCorrect(); ?></th>
                        <th style="color: red;"><?php echo $model->getFormatIncorrect(); ?></th>
                        <th><?php echo $model->getSolvedCount() < 0 ? '---' : sprintf(__('%d of %d', 'quizmaster'),
                                $model->getSolvedCount(),
                                $model->getCorrectCount() + $model->getIncorrectCount()); ?></th>
                        <th><?php echo $model->getPoints(); ?></th>
                        <th style="font-weight: bold;"><?php echo $model->getResult(); ?>%</th>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>

        <?php
    }

    public function getUserTable()
    {
        ob_start();

        $this->showUserTable();

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function showUserTable()
    {
        ?>

        <style>
            .quizMaster_questionList {
                margin-bottom: 10px !important;
                background: #F8FAF5 !important;
                border: 1px solid #C3D1A3 !important;
                padding: 5px !important;
                list-style: none !important;
            }

            .quizMaster_questionList > li {
                padding: 3px !important;
                margin-bottom: 5px !important;
                background-image: none !important;
                margin-left: 0 !important;
                list-style: none !important;
            }

            .quizMaster_answerCorrect {
                background: #6DB46D !important;
                font-weight: bold !important;
            }

            .quizMaster_answerIncorrect {
                background: #FF9191 !important;
                font-weight: bold !important;
            }

            .quizMaster_sortable {
                padding: 5px !important;
                border: 1px solid lightGrey !important;
                background-color: #F8FAF5 !important;
            }

            .quizMaster_questionList table {
                border-collapse: collapse !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
            }

            .quizMaster_questionList table {
                border-collapse: collapse !important;
            }

            .quizMaster_mextrixTr > td {
                border: 1px solid #D1D1D1 !important;
                padding: 5px !important;
                vertical-align: middle !important;
            }

            .quizMaster_maxtrixSortCriterion {
                padding: 5px !important;
            }

            .quizMaster_sortStringItem {
                margin: 0 !important;
                background-image: none !important;
                list-style: none !important;
                padding: 5px !important;
                border: 1px solid lightGrey !important;
                background-color: #F8FAF5 !important;
            }

            .quizMaster_cloze {
                padding: 0 4px 2px 4px;
                border-bottom: 1px solid #000;
            }
        </style>
        <h2><?php printf(__('User statistics: %s', 'quizmaster'), esc_html($this->userName)); ?></h2>
        <?php if ($this->avg) { ?>
        <h2>
            <?php echo date_i18n(get_option('quizMaster_statisticTimeFormat', 'Y/m/d g:i A'),
                $this->statisticModel->getMinCreateTime()); ?>
            -
            <?php echo date_i18n(get_option('quizMaster_statisticTimeFormat', 'Y/m/d g:i A'),
                $this->statisticModel->getMaxCreateTime()); ?>
        </h2>
    <?php } else { ?>
        <h2><?php echo date_i18n(get_option('quizMaster_statisticTimeFormat', 'Y/m/d g:i A'),
                $this->statisticModel->getCreateTime()); ?></h2>
    <?php } ?>

        <?php $this->formTable(); ?>

        <table class="wp-list-table widefat" style="margin-top: 20px;">
            <thead>
            <tr>
                <th scope="col" style="width: 50px;"></th>
                <th scope="col"><?php _e('Question', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Points', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Correct', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Incorrect', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Hints used', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Solved', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Time', 'quizmaster'); ?> <span
                        style="font-size: x-small;">(hh:mm:ss)</span></th>
                <th scope="col" style="width: 100px;"><?php _e('Points scored', 'quizmaster'); ?></th>
                <th scope="col" style="width: 60px;"><?php _e('Results', 'quizmaster'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $gCorrect = $gIncorrect = $gHintCount = $gPoints = $gGPoints = $gTime = $gSolvedCount = 0;

            foreach ($this->userStatistic as $cat) {
                $cCorrect = $cIncorrect = $cHintCount = $cPoints = $cGPoints = $cTime = $cSolvedCount = 0;
                ?>
                <tr class="categoryTr">
                    <th colspan="9">
                        <span><?php _e('Category', 'quizmaster'); ?>:</span>
                        <span style="font-weight: bold;"><?php echo esc_html($cat['categoryName']); ?></span>
                    </th>
                </tr>
                <?php foreach ($cat['questions'] as $q) {
                    $index = 1;
                    $sum = $q['correct'] + $q['incorrect'];

                    $cPoints += $q['points'];
                    $cGPoints += $q['gPoints'];
                    $cCorrect += $q['correct'];
                    $cIncorrect += $q['incorrect'];
                    $cHintCount += $q['hintCount'];
                    $cTime += $q['time'];
                    $cSolvedCount += $q['solvedCount'];
                    ?>
                    <tr>
                        <th><?php echo $index++; ?></th>
                        <th>
                            <?php if (!$this->avg && $q['statistcAnswerData'] !== null) {
                                echo '<a href="#" class="statistic_data">' . esc_html($q['questionName']) . '</a>';
                            } else {
                                echo esc_html($q['questionName']);
                            } ?>
                        </th>
                        <th><?php echo $q['gPoints']; ?></th>
                        <th style="color: green;"><?php echo $q['correct'] . ' (' . round(100 * $q['correct'] / $sum,
                                    2) . '%)'; ?></th>
                        <th style="color: red;"><?php echo $q['incorrect'] . ' (' . round(100 * $q['incorrect'] / $sum,
                                    2) . '%)'; ?></th>
                        <th><?php echo $q['hintCount']; ?></th>
                        <th><?php echo $q['solvedCount'] < 0 ? '---' : ($q['solvedCount'] ? __('yes',
                                'quizmaster') : __('no', 'quizmaster')); ?></th>
                        <th><?php echo QuizMaster_Helper_Until::convertToTimeString($q['time']); ?></th>
                        <th><?php echo $q['points']; ?></th>
                        <th></th>
                    </tr>
                    <?php if (!$this->avg && $q['statistcAnswerData'] !== null) { ?>

                        <tr style="display: none;">
                            <th colspan="9">
                                <?php $this->showUserAnswer($q['questionAnswerData'], $q['statistcAnswerData'],
                                    $q['answerType']); ?>
                            </th>
                        </tr>

                        <?php
                    }
                }

                $sum = $cCorrect + $cIncorrect;
                $result = round((100 * $cPoints / $cGPoints), 2) . '%';
                ?>
                <tr class="categoryTr" id="quizMaster_ctr_222">
                    <th colspan="2">
                        <span><?php _e('Sub-Total: ', 'quizmaster'); ?></span>
                    </th>
                    <th><?php echo $cGPoints; ?></th>
                    <th style="color: green;"><?php echo $cCorrect . ' (' . round(100 * $cCorrect / $sum,
                                2) . '%)'; ?></th>
                    <th style="color: red;"><?php echo $cIncorrect . ' (' . round(100 * $cIncorrect / $sum,
                                2) . '%)'; ?></th>
                    <th><?php echo $cHintCount; ?></th>
                    <th><?php echo $cSolvedCount < 0 ? '---' : sprintf(__('%d of %d', 'quizmaster'), $cSolvedCount,
                            $sum); ?></th>
                    <th><?php echo QuizMaster_Helper_Until::convertToTimeString($cTime); ?></th>
                    <th><?php echo $cPoints; ?></th>
                    <th style="font-weight: bold;"><?php echo $result; ?></th>
                </tr>

                <tr>
                    <th colspan="9"></th>
                </tr>
                <?php
                $gPoints += $cPoints;
                $gGPoints += $cGPoints;
                $gCorrect += $cCorrect;
                $gIncorrect += $cIncorrect;
                $gHintCount += $cHintCount;
                $gTime += $cTime;
                $gSolvedCount += $cSolvedCount;

            }
            ?>
            </tbody>
            <?php
            $sum = $gCorrect + $gIncorrect;
            $result = round((100 * $gPoints / $gGPoints), 2) . '%';
            ?>
            <tfoot>
            <tr id="quizMaster_tr_0">
                <th></th>
                <th><?php _e('Total', 'quizmaster'); ?></th>
                <th><?php echo $gGPoints; ?></th>
                <th style="color: green;"><?php echo $gCorrect . ' (' . round(100 * $gCorrect / $sum, 2) . '%)'; ?></th>
                <th style="color: red;"><?php echo $gIncorrect . ' (' . round(100 * $gIncorrect / $sum,
                            2) . '%)'; ?></th>
                <th><?php echo $gHintCount; ?></th>
                <th><?php echo $gSolvedCount < 0 ? '---' : sprintf(__('%d of %d', 'quizmaster'), $gSolvedCount,
                        $sum); ?></th>
                <th><?php echo QuizMaster_Helper_Until::convertToTimeString($gTime); ?></th>
                <th><?php echo $gPoints; ?></th>
                <th style="font-weight: bold;"><?php echo $result; ?></th>
            </tr>
            </tfoot>
        </table>

        <div style="margin-top: 10px;">
            <div style="float: left;">
                <a class="button-secondary quizMaster_update" href="#"><?php _e('Refresh', 'quizmaster'); ?></a>
            </div>
            <div style="float: right;">
                <?php if (current_user_can('quizMaster_reset_statistics')) { ?>
                    <a class="button-secondary" href="#" id="quizMaster_resetUserStatistic"><?php _e('Reset statistics',
                            'quizmaster'); ?></a>
                <?php } ?>
            </div>
            <div style="clear: both;"></div>
        </div>
        <?php
    }

    private function showUserAnswer($qAnswerData, $sAnswerData, $anserType)
    {
        $matrix = array();

        if ($anserType == 'matrix_sort_answer') {
            foreach ($qAnswerData as $k => $v) {
                $matrix[$k][] = $k;

                foreach ($qAnswerData as $k2 => $v2) {
                    if ($k != $k2) {
                        if ($v->getAnswer() == $v2->getAnswer()) {
                            $matrix[$k][] = $k2;
                        } else {
                            if ($v->getSortString() == $v2->getSortString()) {
                                $matrix[$k][] = $k2;
                            }
                        }
                    }
                }
            }
        }
        ?>
        <ul class="quizMaster_questionList">
            <?php for ($i = 0; $i < count($qAnswerData); $i++) {
                $answerText = $qAnswerData[$i]->isHtml() ? $qAnswerData[$i]->getAnswer() : esc_html($qAnswerData[$i]->getAnswer());
                $correct = '';
                ?>
                <?php if ($anserType === 'single' || $anserType === 'multiple') {
                    if ($qAnswerData[$i]->isCorrect()) {
                        $correct = 'quizMaster_answerCorrect';
                    } else {
                        if (isset($sAnswerData[$i]) && $sAnswerData[$i]) {
                            $correct = 'quizMaster_answerIncorrect';
                        }
                    }
                    ?>
                    <li class="<?php echo $correct; ?>">
                        <label>
                            <input disabled="disabled"
                                   type="<?php echo $anserType === 'single' ? 'radio' : 'checkbox'; ?>"
                                <?php echo $sAnswerData[$i] ? 'checked="checked"' : '' ?>>
                            <?php echo $answerText; ?>
                        </label>
                    </li>
                <?php } else {
                    if ($anserType === 'free_answer') {
                        $t = str_replace("\r\n", "\n", strtolower($qAnswerData[$i]->getAnswer()));
                        $t = str_replace("\r", "\n", $t);
                        $t = explode("\n", $t);
                        $t = array_values(array_filter(array_map('trim', $t)));

                        if (isset($sAnswerData[0]) && in_array(strtolower(trim($sAnswerData[0])), $t)) {
                            $correct = 'quizMaster_answerCorrect';
                        } else {
                            $correct = 'quizMaster_answerIncorrect';
                        }
                        ?>
                        <li class="<?php echo $correct ?>">
                            <label>
                                <input type="text" disabled="disabled"
                                       style="width: 300px; padding: 5px;margin-bottom: 5px;"
                                       value="<?php echo esc_attr($sAnswerData[0]); ?>">
                            </label>
                            <br>
                            <?php _e('Correct', 'quizmaster'); ?>:
                            <?php echo implode(', ', $t); ?>
                        </li>
                    <?php } else {
                        if ($anserType === 'sort_answer') {
                            $correct = 'quizMaster_answerIncorrect';
                            $sortText = '';

                            if (isset($sAnswerData[$i]) && isset($qAnswerData[$sAnswerData[$i]])) {
                                if ($sAnswerData[$i] == $i) {
                                    $correct = 'quizMaster_answerCorrect';
                                }

                                $v = $qAnswerData[$sAnswerData[$i]];
                                $sortText = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());
                            }
                            ?>
                            <li class="<?php echo $correct; ?>">
                                <div class="quizMaster_sortable">
                                    <?php echo $sortText; ?>
                                </div>
                            </li>
                        <?php } else {
                            if ($anserType == 'matrix_sort_answer') {
                                $correct = 'quizMaster_answerIncorrect';
                                $sortText = '';

                                if (isset($sAnswerData[$i]) && isset($qAnswerData[$sAnswerData[$i]])) {
                                    if (in_array($sAnswerData[$i], $matrix[$i])) {
                                        $correct = 'quizMaster_answerCorrect';
                                    }

                                    $v = $qAnswerData[$sAnswerData[$i]];
                                    $sortText = $v->isSortStringHtml() ? $v->getSortString() : esc_html($v->getSortString());
                                }

                                ?>
                                <li>
                                    <table>
                                        <tbody>
                                        <tr class="quizMaster_mextrixTr">
                                            <td width="20%">
                                                <div class="quizMaster_maxtrixSortText"><?php echo $answerText; ?></div>
                                            </td>
                                            <td width="80%">
                                                <ul class="quizMaster_maxtrixSortCriterion <?php echo $correct; ?>">
                                                    <li class="quizMaster_sortStringItem" data-pos="0"
                                                        style="box-shadow: 0px 0px; cursor: auto;">
                                                        <?php echo $sortText; ?>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </li>
                            <?php } else {
                                if ($anserType == 'cloze_answer') {
                                    $clozeData = $this->fetchCloze($qAnswerData[$i]->getAnswer(), $sAnswerData);

                                    $this->_clozeTemp = $clozeData['data'];

                                    $cloze = $clozeData['replace'];

                                    echo preg_replace_callback('#@@quizMasterCloze@@#im', array($this, 'clozeCallback'),
                                        $cloze);
                                } else {
                                    if ($anserType == 'assessment_answer') {
                                        $assessmentData = $this->fetchAssessment($qAnswerData[$i]->getAnswer(),
                                            $sAnswerData);

                                        $assessment = do_shortcode(apply_filters('comment_text',
                                            $assessmentData['replace']));

                                        echo preg_replace_callback('#@@quizMasterAssessment@@#im',
                                            array($this, 'assessmentCallback'), $assessment);
                                    }
                                }
                            }
                        }
                    }
                } ?>
            <?php } ?>
        </ul>
        <?php
    }

    private $_assessmetTemp = array();

    private function assessmentCallback($t)
    {
        $a = array_shift($this->_assessmetTemp);

        return $a === null ? '' : $a;
    }

    private function fetchAssessment($answerText, $answerData)
    {
        preg_match_all('#\{(.*?)\}#im', $answerText, $matches);

        $this->_assessmetTemp = array();
        $data = array();

        for ($i = 0, $ci = count($matches[1]); $i < $ci; $i++) {
            $match = $matches[1][$i];

            preg_match_all('#\[([^\|\]]+)(?:\|(\d+))?\]#im', $match, $ms);

            $a = '';

            $checked = isset($answerData[$i]) ? $answerData[$i] - 1 : -1;

            for ($j = 0, $cj = count($ms[1]); $j < $cj; $j++) {
                $v = $ms[1][$j];

                $a .= '<label>
					<input type="radio" disabled="disabled" ' . ($checked == $j ? 'checked="checked"' : '') . '>
					' . $v . '
				</label>';
            }

            $this->_assessmetTemp[] = $a;
        }

        $data['replace'] = preg_replace('#\{(.*?)\}#im', '@@quizMasterAssessment@@', $answerText);

        return $data;
    }

    private $_clozeTemp = array();

    private function fetchCloze($answer_text, $answerData)
    {
        preg_match_all('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $answer_text, $matches, PREG_SET_ORDER);

        $data = array();
        $index = 0;

        foreach ($matches as $k => $v) {
            $text = $v[1];
            $points = !empty($v[2]) ? (int)$v[2] : 1;
            $rowText = $multiTextData = array();
            $len = array();

            if (preg_match_all('#\[(.*?)\]#im', $text, $multiTextMatches)) {
                foreach ($multiTextMatches[1] as $multiText) {
                    $x = mb_strtolower(trim(html_entity_decode($multiText, ENT_QUOTES)));

                    $len[] = strlen($x);
                    $multiTextData[] = $x;
                    $rowText[] = $multiText;
                }
            } else {
                $x = mb_strtolower(trim(html_entity_decode($text, ENT_QUOTES)));

                $len[] = strlen($x);
                $multiTextData[] = $x;
                $rowText[] = $text;
            }

            $correct = 'quizMaster_answerIncorrect';

            if (isset($answerData[$index]) && in_array($answerData[$index], $rowText)) {
                $correct = 'quizMaster_answerCorrect';
            }

// 			$a = '<span class="quizMaster_cloze"><input data-wordlen="'.max($len).'" type="text" value=""> ';
// 			$a .= '<span class="quizMaster_clozeCorrect" style="display: none;">('.implode(', ', $rowText).')</span></span>';
            $a = '<span class="quizMaster_cloze ' . $correct . '">' . esc_html(isset($answerData[$index]) ? empty($answerData[$index]) ? '---' : $answerData[$index]
                    : '---') . '</span> ';
            $a .= '<span>(' . implode(', ', $rowText) . ')</span>';

            $data['correct'][] = $multiTextData;
            $data['points'][] = $points;
            $data['data'][] = $a;

            $index++;
        }

        $data['replace'] = preg_replace('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', '@@quizMasterCloze@@', $answer_text);

        return $data;
    }

    private function clozeCallback($t)
    {
        $a = array_shift($this->_clozeTemp);

        return $a === null ? '' : $a;
    }

    private function formTable()
    {
        if ($this->forms === null || $this->statisticModel === null) {
            return;
        }

        $formData = $this->statisticModel->getFormData();

        if ($formData === null) {
            return;
        }

        ?>

        <div id="quizMaster_form_box">
            <div id="poststuff">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Custom fields', 'quizmaster'); ?></h3>

                    <div class="inside">
                        <table>
                            <tbody>
                            <?php foreach ($this->forms as $form) {
                                /* @var $form QuizMaster_Model_Form */

                                if (!isset($formData[$form->getFormId()])) {
                                    continue;
                                }

                                $str = $formData[$form->getFormId()];
                                ?>
                                <tr>
                                    <td style="padding: 5px;"><?php echo esc_html($form->getFieldname()); ?></td>
                                    <td>
                                        <?php
                                        switch ($form->getType()) {
                                            case QuizMaster_Model_Form::FORM_TYPE_TEXT:
                                            case QuizMaster_Model_Form::FORM_TYPE_TEXTAREA:
                                            case QuizMaster_Model_Form::FORM_TYPE_EMAIL:
                                            case QuizMaster_Model_Form::FORM_TYPE_NUMBER:
                                            case QuizMaster_Model_Form::FORM_TYPE_RADIO:
                                            case QuizMaster_Model_Form::FORM_TYPE_SELECT:
                                                echo esc_html($str);
                                                break;
                                            case QuizMaster_Model_Form::FORM_TYPE_CHECKBOX:
                                                echo $str == '1' ? __('ticked', 'quizmaster') : __('not ticked',
                                                    'quizmaster');
                                                break;
                                            case QuizMaster_Model_Form::FORM_TYPE_YES_NO:
                                                echo $str == 1 ? __('Yes') : __('No');
                                                break;
                                            case QuizMaster_Model_Form::FORM_TYPE_DATE:
                                                echo date_format(date_create($str), get_option('date_format'));
                                                break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function getOverviewTable()
    {
        ob_start();

        $this->showOverviewTable();

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function showOverviewTable()
    {
        ?>
        <table class="wp-list-table widefat">
            <thead>
            <tr>
                <th scope="col"><?php _e('User', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Points', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Correct', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Incorrect', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Hints used', 'quizmaster'); ?></th>
                <th scope="col" style="width: 100px;"><?php _e('Time', 'quizmaster'); ?> <span
                        style="font-size: x-small;">(hh:mm:ss)</span></th>
                <th scope="col" style="width: 60px;"><?php _e('Results', 'quizmaster'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!count($this->statisticModel)) { ?>
                <tr>
                    <td colspan="7"
                        style="text-align: center; font-weight: bold; padding: 10px;"><?php _e('No data available',
                            'quizmaster'); ?></td>
                </tr>
            <?php } else { ?>

                <?php foreach ($this->statisticModel as $model) {
                    /** @var QuizMaster_Model_StatisticOverview $model * */
                    $sum = $model->getCorrectCount() + $model->getIncorrectCount();

                    if (!$model->getUserId()) {
                        $model->setUserName(__('Anonymous', 'quizmaster'));
                    }

                    if ($sum) {
                        $points = $model->getPoints();
                        $correct = $model->getCorrectCount() . ' (' . round(100 * $model->getCorrectCount() / $sum,
                                2) . '%)';
                        $incorrect = $model->getIncorrectCount() . ' (' . round(100 * $model->getIncorrectCount() / $sum,
                                2) . '%)';
                        $hintCount = $model->getHintCount();
                        $time = QuizMaster_Helper_Until::convertToTimeString($model->getQuestionTime());
                        $result = round((100 * $points / $model->getGPoints()), 2) . '%';
                    } else {
                        $result = $time = $hintCount = $incorrect = $correct = $points = '---';
                    }

                    ?>

                    <tr>
                        <th>
                            <?php if ($sum) { ?>
                                <a href="#" class="user_statistic"
                                   data-user_id="<?php echo $model->getUserId(); ?>"><?php echo esc_html($model->getUserName()); ?></a>
                            <?php } else {
                                echo esc_html($model->getUserName());
                            } ?>

                            <div <?php echo $sum ? 'class="row-actions"' : 'style="visibility: hidden;"'; ?>>
							<span>
								<a style="color: red;" class="quizMaster_delete" href="#"><?php _e('Delete',
                                        'quizmaster'); ?></a>
							</span>
                            </div>

                        </th>
                        <th><?php echo $points ?></th>
                        <th style="color: green;"><?php echo $correct ?></th>
                        <th style="color: red;"><?php echo $incorrect ?></th>
                        <th><?php echo $hintCount ?></th>
                        <th><?php echo $time ?></th>
                        <th style="font-weight: bold;"><?php echo $result ?></th>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>

        <?php
    }
}