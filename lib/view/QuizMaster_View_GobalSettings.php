<?php

/**
 * @property QuizMaster_Model_GlobalSettings settings
 * @property bool isRaw
 * @property QuizMaster_Model_Category[] category
 * @property QuizMaster_Model_Category[] categoryQuiz
 * @property array email
 * @property array userEmail
 * @property QuizMaster_Model_Template[] templateQuiz
 * @property QuizMaster_Model_Template[] templateQuestion
 * @property string toplistDataFormat
 * @property string statisticTimeFormat
 */
class QuizMaster_View_GobalSettings extends QuizMaster_View_View
{

    public function show()
    {
        ?>
        <div class="wrap quizMaster_globalSettings">
            <h2 style="margin-bottom: 10px;"><?php _e('Global settings', 'quizmaster'); ?></h2>

            <a class="button-secondary" href="admin.php?page=quizMaster"><?php _e('back to overview',
                    'quizmaster'); ?></a>

            <div class="quizMaster_tab_wrapper" style="padding: 10px 0px;">
                <a class="button-primary" href="#" data-tab="#globalContent"><?php _e('Global settings',
                        'quizmaster'); ?></a>
                <!-- <a class="button-secondary" href="#" data-tab="#emailSettingsTab"><?php //_e('E-Mail settings', 'quizmaster');
                ?></a> -->
                <a class="button-secondary" href="#" data-tab="#problemContent"><?php _e('Settings in case of problems',
                        'quizmaster'); ?></a>
            </div>

            <form method="post">
                <div id="poststuff">
                    <div id="globalContent">

                        <?php $this->globalSettings(); ?>

                    </div>
                    <!-- <div id="emailSettingsTab" style="display: none;">
				<?php //$this->emailSettingsTab();
                    ?>
			</div>  -->
                    <div class="postbox" id="problemContent" style="display: none;">
                        <?php $this->problemSettings(); ?>
                    </div>
                    <input type="submit" name="submit" class="button-primary" id="quizMaster_save"
                           value="<?php _e('Save', 'quizmaster'); ?>">
                </div>
            </form>
        </div>

        <?php
    }

    private function globalSettings()
    {

        ?>
        <div class="postbox">
            <h3 class="hndle"><?php _e('Global settings', 'quizmaster'); ?></h3>

            <div class="inside">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _e('Leaderboard time format', 'quizmaster'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Leaderboard time format', 'quizmaster'); ?></span>
                                </legend>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="d.m.Y H:i" <?php $this->checked($this->toplistDataFormat,
                                        'd.m.Y H:i'); ?>> 06.11.2010 12:50
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d g:i A" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d g:i A'); ?>> 2010/11/06 12:50 AM
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d \a\t g:i A" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d \a\t g:i A'); ?>> 2010/11/06 at 12:50 AM
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d \a\t g:ia" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d \a\t g:ia'); ?>> 2010/11/06 at 12:50am
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="F j, Y g:i a" <?php $this->checked($this->toplistDataFormat,
                                        'F j, Y g:i a'); ?>> November 6, 2010 12:50 am
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="M j, Y @ G:i" <?php $this->checked($this->toplistDataFormat,
                                        'M j, Y @ G:i'); ?>> Nov 6, 2010 @ 0:50
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="custom" <?php echo in_array($this->toplistDataFormat, array(
                                        'd.m.Y H:i',
                                        'Y/m/d g:i A',
                                        'Y/m/d \a\t g:i A',
                                        'Y/m/d \a\t g:ia',
                                        'F j, Y g:i a',
                                        'M j, Y @ G:i'
                                    )) ? '' : 'checked="checked"'; ?> >
                                    <?php _e('Custom', 'quizmaster'); ?>:
                                    <input class="medium-text" name="toplist_date_format_custom" style="width: 100px;"
                                           value="<?php echo $this->toplistDataFormat; ?>">
                                </label>

                                <p>
                                    <a href="http://codex.wordpress.org/Formatting_Date_and_Time"
                                       target="_blank"><?php _e('Documentation on date and time formatting',
                                            'quizmaster'); ?></a>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Statistic time format', 'quizmaster'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Statistic time format', 'quizmaster'); ?></span>
                                </legend>

                                <label>
                                    <?php _e('Select example:', 'quizmaster'); ?>
                                    <select id="statistic_time_format_select">
                                        <option value="0"></option>
                                        <option value="d.m.Y H:i"> 06.11.2010 12:50</option>
                                        <option value="Y/m/d g:i A"> 2010/11/06 12:50 AM</option>
                                        <option value="Y/m/d \a\t g:i A"> 2010/11/06 at 12:50 AM</option>
                                        <option value="Y/m/d \a\t g:ia"> 2010/11/06 at 12:50am</option>
                                        <option value="F j, Y g:i a"> November 6, 2010 12:50 am</option>
                                        <option value="M j, Y @ G:i"> Nov 6, 2010 @ 0:50</option>
                                    </select>
                                </label>

                                <div style="margin-top: 10px;">
                                    <label>
                                        <?php _e('Time format:', 'quizmaster'); ?>:
                                        <input class="medium-text" name="statisticTimeFormat"
                                               value="<?php echo $this->statisticTimeFormat; ?>">
                                    </label>

                                    <p>
                                        <a href="http://codex.wordpress.org/Formatting_Date_and_Time"
                                           target="_blank"><?php _e('Documentation on date and time formatting',
                                                'quizmaster'); ?></a>
                                    </p>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Quiz template management', 'quizmaster'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Quiz template management', 'quizmaster'); ?></span>
                                </legend>
                                <select name="templateQuiz">
                                    <?php foreach ($this->templateQuiz as $templateQuiz) {
                                        echo '<option value="' . $templateQuiz->getTemplateId() . '">' . esc_html($templateQuiz->getName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="templateQuizEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'quizmaster'); ?>"
                                           name="templateQuizDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'quizmaster'); ?>"
                                           name="templateQuizEdit" class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Question template management', 'quizmaster'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Question template management', 'quizmaster'); ?></span>
                                </legend>
                                <select name="templateQuestion">
                                    <?php foreach ($this->templateQuestion as $templateQuestion) {
                                        echo '<option value="' . $templateQuestion->getTemplateId() . '">' . esc_html($templateQuestion->getName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="templateQuestionEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'quizmaster'); ?>"
                                           name="templateQuestionDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'quizmaster'); ?>"
                                           name="templateQuestionEdit" class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }

    private function emailSettings()
    {
        ?>
        <div class="postbox" id="adminEmailSettings">
            <h3 class="hndle"><?php _e('Admin e-mail settings', 'quizmaster'); ?></h3>

            <div class="inside">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _e('To:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="text" name="email[to]" value="<?php echo $this->email['to']; ?>"
                                       class="regular-text">
                            </label>

                            <p class="description">
                                <?php _e('Separate multiple email addresses with a comma, e.g. wp@test.com, test@test.com',
                                    'quizmaster'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('From:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="text" name="email[from]" value="<?php echo $this->email['from']; ?>"
                                       class="regular-text">
                            </label>
                            <!-- 								<p class="description"> -->
                            <?php //_e('Server-Adresse empfohlen, z.B. info@YOUR-PAGE.com', 'quizmaster');
                            ?>
                            <!-- 								</p> -->
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Subject:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="text" name="email[subject]" value="<?php echo $this->email['subject']; ?>"
                                       class="regular-text">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('HTML', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="email[html]"
                                       value="1" <?php $this->checked(isset($this->email['html']) ? $this->email['html'] : false); ?>> <?php _e('Activate',
                                    'quizmaster'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Message body:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <?php
                            wp_editor($this->email['message'], 'adminEmailEditor',
                                array('textarea_rows' => 20, 'textarea_name' => 'email[message]'));
                            ?>

                            <div>
                                <h4><?php _e('Allowed variables', 'quizmaster'); ?>:</h4>
                                <ul>
                                    <li><span>$userId</span> - <?php _e('User-ID', 'quizmaster'); ?></li>
                                    <li><span>$username</span> - <?php _e('Username', 'quizmaster'); ?></li>
                                    <li><span>$quizname</span> - <?php _e('Quiz-Name', 'quizmaster'); ?></li>
                                    <li><span>$result</span> - <?php _e('Result in precent', 'quizmaster'); ?></li>
                                    <li><span>$points</span> - <?php _e('Reached points', 'quizmaster'); ?></li>
                                    <li><span>$ip</span> - <?php _e('IP-address of the user', 'quizmaster'); ?></li>
                                    <li><span>$categories</span> - <?php _e('Category-Overview', 'quizmaster'); ?></li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }

    private function userEmailSettings()
    {
        ?>
        <div class="postbox" id="userEmailSettings" style="display: none;">
            <h3 class="hndle"><?php _e('User e-mail settings', 'quizmaster'); ?></h3>

            <div class="inside">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _e('From:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="text" name="userEmail[from]"
                                       value="<?php echo $this->userEmail['from']; ?>" class="regular-text">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Subject:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="text" name="userEmail[subject]"
                                       value="<?php echo $this->userEmail['subject']; ?>" class="regular-text">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('HTML', 'quizmaster'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="userEmail[html]"
                                       value="1" <?php $this->checked($this->userEmail['html']); ?>> <?php _e('Activate',
                                    'quizmaster'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Message body:', 'quizmaster'); ?>
                        </th>
                        <td>
                            <?php
                            wp_editor($this->userEmail['message'], 'userEmailEditor',
                                array('textarea_rows' => 20, 'textarea_name' => 'userEmail[message]'));
                            ?>

                            <div>
                                <h4><?php _e('Allowed variables', 'quizmaster'); ?>:</h4>
                                <ul>
                                    <li><span>$userId</span> - <?php _e('User-ID', 'quizmaster'); ?></li>
                                    <li><span>$username</span> - <?php _e('Username', 'quizmaster'); ?></li>
                                    <li><span>$quizname</span> - <?php _e('Quiz-Name', 'quizmaster'); ?></li>
                                    <li><span>$result</span> - <?php _e('Result in precent', 'quizmaster'); ?></li>
                                    <li><span>$points</span> - <?php _e('Reached points', 'quizmaster'); ?></li>
                                    <li><span>$ip</span> - <?php _e('IP-address of the user', 'quizmaster'); ?></li>
                                    <li><span>$categories</span> - <?php _e('Category-Overview', 'quizmaster'); ?></li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    private function problemSettings()
    {
        if ($this->isRaw) {
            $rawSystem = __('to activate', 'quizmaster');
        } else {
            $rawSystem = __('not to activate', 'quizmaster');
        }

        ?>

        <div class="updated" id="problemInfo" style="display: none;">
            <h3><?php _e('Please note', 'quizmaster'); ?></h3>

            <p>
                <?php _e('These settings should only be set in cases of problems with QuizMaster.', 'quizmaster'); ?>
            </p>
        </div>

        <h3 class="hndle"><?php _e('Settings in case of problems', 'quizmaster'); ?></h3>
        <div class="inside">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <?php _e('Automatically add [raw] shortcode', 'quizmaster'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Automatically add [raw] shortcode', 'quizmaster'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="addRawShortcode" <?php echo $this->settings->isAddRawShortcode() ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'quizmaster'); ?> <span
                                    class="description">( <?php printf(__('It is recommended %s this option on your system.',
                                        'quizmaster'),
                                        '<span style=" font-weight: bold;">' . $rawSystem . '</span>'); ?> )</span>
                            </label>

                            <p class="description">
                                <?php _e('If this option is activated, a [raw] shortcode is automatically set around QuizMaster shortcode ( [QuizMaster X] ) into [raw] [QuizMaster X] [/raw]',
                                    'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('Own themes changes internal  order of filters, what causes the problems. With additional shortcode [raw] this is prevented.',
                                    'quizmaster'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Do not load the Javascript-files in the footer', 'quizmaster'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Do not load the Javascript-files in the footer',
                                        'quizmaster'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="jsLoadInHead" <?php echo $this->settings->isJsLoadInHead() ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'quizmaster'); ?>
                            </label>

                            <p class="description">
                                <?php _e('Generally all QuizMaster-Javascript files are loaded in the footer and only when they are really needed.',
                                    'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('In very old Wordpress themes this can lead to problems.', 'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you activate this option, all QuizMaster-Javascript files are loaded in the header even if they are not needed.',
                                    'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php printf(__('Anyone who wants to learn more about this topic should read through the following websites %s and %s.',
                                    'quizmaster'),
                                    '<a href="http://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29" target="_blank">Theme Development</a>',
                                    '<a href="http://codex.wordpress.org/Function_Reference/wp_footer" target="_blank">Function Reference/wp footer</a>'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Touch Library', 'quizmaster'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Touch Library', 'quizmaster'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="touchLibraryDeactivate" <?php echo $this->settings->isTouchLibraryDeactivate() ? 'checked="checked"' : '' ?> >
                                <?php _e('Deactivate', 'quizmaster'); ?>
                            </label>

                            <p class="description">
                                <?php _e('In Version 0.13 a new Touch Library was added for mobile devices.',
                                    'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you have any problems with the Touch Library, please deactivate it.',
                                    'quizmaster'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('jQuery support cors', 'quizmaster'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('jQuery support cors', 'quizmaster'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="corsActivated" <?php echo $this->settings->isCorsActivated() ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'quizmaster'); ?>
                            </label>

                            <p class="description">
                                <?php _e('Is required only in rare cases.', 'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you have problems with the front ajax, please activate it.',
                                    'quizmaster'); ?>
                            </p>

                            <p class="description">
                                <?php _e('e.g. Domain with special characters in combination with IE',
                                    'quizmaster'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Repair database', 'quizmaster'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Repair database', 'quizmaster'); ?></span>
                            </legend>
                            <input type="submit" name="databaseFix" class="button-primary"
                                   value="<?php _e('Repair database', 'quizmaster'); ?>">

                            <p class="description">
                                <?php _e('No date will be deleted. Only QuizMaster tables will be repaired.',
                                    'quizmaster'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php
    }

    private function emailSettingsTab()
    {
        ?>

        <div class="quizMaster_tab_wrapper" style="padding-bottom: 10px;">
            <a class="button-primary" href="#" data-tab="#adminEmailSettings"><?php _e('Admin e-mail settings',
                    'quizmaster'); ?></a>
            <a class="button-secondary" href="#" data-tab="#userEmailSettings"><?php _e('User e-mail settings',
                    'quizmaster'); ?></a>
        </div>

        <?php $this->emailSettings(); ?>
        <?php $this->userEmailSettings(); ?>

        <?php
    }
}
