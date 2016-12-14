<?php

/**
 * @property QuizMaster_Model_Quiz quiz
 */
class QuizMaster_View_AdminToplist extends QuizMaster_View_View
{

    public function show()
    {
        ?>
        <div class="wrap quizMaster_toplist">

            <input type="hidden" name="ajax_quiz_id" value="<?php echo $this->quiz->getId(); ?>">

            <h2><?php _e('Leaderboard', 'quizmaster');
                echo ': ', $this->quiz->getName(); ?></h2>
            <a class="button-secondary" href="admin.php?page=quizMaster"><?php _e('back to overview',
                    'quizmaster'); ?></a>

            <div id="poststuff">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Filter', 'quizmaster'); ?></h3>

                    <div class="inside">
                        <ul>
                            <li>
                                <label>
                                    <?php _e('Sort by:', 'quizmaster'); ?>
                                    <select id="quizMaster_sorting">
                                        <option
                                            value="<?php echo QuizMaster_Model_Quiz::QUIZ_TOPLIST_SORT_BEST; ?>"><?php _e('best user',
                                                'quizmaster'); ?></option>
                                        <option
                                            value="<?php echo QuizMaster_Model_Quiz::QUIZ_TOPLIST_SORT_NEW; ?>"><?php _e('newest entry',
                                                'quizmaster'); ?></option>
                                        <option
                                            value="<?php echo QuizMaster_Model_Quiz::QUIZ_TOPLIST_SORT_OLD; ?>"><?php _e('oldest entry',
                                                'quizmaster'); ?></option>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <?php _e('How many entries should be shown on one page:', 'quizmaster'); ?>
                                    <select id="quizMaster_pageLimit">
                                        <option>1</option>
                                        <option>10</option>
                                        <option>50</option>
                                        <option selected="selected">100</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <span style="font-weight: bold;"><?php _e('Type', 'quizmaster'); ?>
                                    :</span> <?php _e('UR = unregistered user, R = registered user', 'quizmaster'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="quizMaster_loadData" class="quizMaster_blueBox"
                 style="background-color: #F8F5A8;padding: 20px;border: 1px dotted;margin-top: 10px;">
                <img alt="load"
                     src="data:image/gif;base64,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">
                <?php _e('Loading', 'quizmaster'); ?>
            </div>

            <div id="quizMaster_content">
                <table class="wp-list-table widefat" id="quizMaster_toplistTable">
                    <thead>
                    <tr>
                        <th scope="col" width="20px"><input style="margin: 0;" type="checkbox" value="0"
                                                            id="quizMaster_checkedAll"></th>
                        <th scope="col"><?php _e('User', 'quizmaster'); ?></th>
                        <th scope="col"><?php _e('E-Mail', 'quizmaster'); ?></th>
                        <th scope="col" width="50px"><?php _e('Type', 'quizmaster'); ?></th>
                        <th scope="col" width="150px"><?php _e('Entered on', 'quizmaster'); ?></th>
                        <th scope="col" width="70px"><?php _e('Points', 'quizmaster'); ?></th>
                        <th scope="col" width="100px"><?php _e('Results', 'quizmaster'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="">
                    <tr style="display: none;">
                        <td><input type="checkbox" name="checkedData[]"></td>
                        <td>
                            <strong class="quizMaster_username"></strong>
                            <input name="inline_editUsername" class="inline_editUsername" type="text" value=""
                                   style="display: none;">

                            <div class="row-actions">
													
							<span style="display: none;">
								<a class="quizMaster_edit" href="#"><?php _e('Edit', 'quizmaster'); ?></a> | 
							</span>
							<span>
								<a style="color: red;" class="quizMaster_delete" href="#"><?php _e('Delete',
                                        'quizmaster'); ?></a>
							</span>

                            </div>
                            <div class="inline-edit" style="margin-top: 10px; display: none;">
                                <input type="button" value="<?php _e('save', 'quizmaster'); ?>"
                                       class="button-secondary inline_editSave">
                                <input type="button" value="<?php _e('cancel', 'quizmaster'); ?>"
                                       class="button-secondary inline_editCancel">
                            </div>
                        </td>
                        <td>
                            <span class="quizMaster_email"></span>
                            <input name="inline_editEmail" class="inline_editEmail" value="" type="text"
                                   style="display: none;">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;"></td>
                    </tr>
                    </tbody>
                </table>

                <div style="margin-top: 10px;">
                    <div style="float: left;">
                        <select id="quizMaster_actionName">
                            <option value="0" selected="selected"><?php _e('Action', 'quizmaster'); ?></option>
                            <option value="delete"><?php _e('Delete', 'quizmaster'); ?></option>
                        </select>
                        <input class="button-secondary" type="button" value="<?php _e('Apply', 'quizmaster'); ?>"
                               id="quizMaster_action">
                        <input class="button-secondary" type="button"
                               value="<?php _e('Delete all entries', 'quizmaster'); ?>" id="quizMaster_deleteAll">
                    </div>
                    <div style="float: right;">
                        <input style="font-weight: bold;" class="button-secondary" value="&lt;" type="button"
                               id="quizMaster_pageLeft">
                        <select id="quizMaster_currentPage">
                            <option value="1">1</option>
                        </select>
                        <input style="font-weight: bold;" class="button-secondary" value="&gt;" type="button"
                               id="quizMaster_pageRight">
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>

        <?php
    }
}