<?php

/**
 * @property bool error
 * @property string importType
 * @property bool finish
 * @property array import
 * @property string importData
 */
class QuizMaster_View_Import extends QuizMaster_View_View
{

    public function show()
    {
        ?>
        <style>
            .quizMaster_importList {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .quizMaster_importList li {
                float: left;
                padding: 5px;
                border: 1px solid #B3B3B3;
                margin-right: 5px;
                background-color: #DAECFF;
            }
        </style>
        <div class="wrap quizMaster_importOverall">
            <h2><?php _e('Import', 'quizmaster'); ?></h2>

            <p><a class="button-secondary" href="admin.php?page=quizMaster"><?php _e('back to overview',
                        'quizmaster'); ?></a></p>
            <?php if ($this->error) { ?>
                <div style="padding: 10px; background-color: rgb(255, 199, 199); margin-top: 20px; border: 1px dotted;">
                    <h3 style="margin-top: 0;"><?php _e('Error', 'quizmaster'); ?></h3>

                    <div>
                        <?php echo $this->error; ?>
                    </div>
                </div>
            <?php } else {
                if ($this->finish) { ?>
                    <div style="padding: 10px; background-color: #C7E4FF; margin-top: 20px; border: 1px dotted;">
                        <h3 style="margin-top: 0;"><?php _e('Successfully', 'quizmaster'); ?></h3>

                        <div>
                            <?php _e('Import completed successfully', 'quizmaster'); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <form method="post">
                        <table class="wp-list-table widefat">
                            <thead>
                            <tr>
                                <th scope="col" width="30px"></th>
                                <th scope="col" width="40%"><?php _e('Quiz name', 'quizmaster'); ?></th>
                                <th scope="col"><?php _e('Questions', 'quizmaster'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->import['master'] as $master) { ?>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="importItems[]"
                                               value="<?php echo $master->getId(); ?>" checked="checked">
                                    </th>
                                    <th><?php echo $master->getName(); ?></th>
                                    <th>
                                        <ul class="quizMaster_importList">
                                            <?php if (isset($this->import['question'][$master->getId()])) { ?>
                                                <?php foreach ($this->import['question'][$master->getId()] as $question) { ?>
                                                    <li><?php echo $question->getTitle(); ?></li>
                                                <?php }
                                            } ?>
                                        </ul>
                                        <div style="clear: both;"></div>
                                    </th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <input name="importData" value="<?php echo $this->importData; ?>" type="hidden">
                        <input name="importType" value="<?php echo $this->importType; ?>" type="hidden">
                        <input style="margin-top: 20px;" class="button-primary" name="importSave"
                               value="<?php echo __('Start import', 'quizmaster'); ?>" type="submit">
                    </form>
                <?php }
            } ?>
        </div>

        <?php
    }
}