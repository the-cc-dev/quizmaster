<?php

class QuizMaster_View_InfoAdaptation extends QuizMaster_View_View
{
    public function show()
    {
        ?>

        <div class="wrap">
            <h2><?php _e('QuizMaster special modification', 'quizmaster'); ?></h2>

            <p><?php _e('You need special QuizMaster modification for your website?', 'quizmaster'); ?></p>

            <h3><?php _e('We offer you:', 'quizmaster'); ?></h3>
            <ol style="list-style-type: disc;">
                <li><?php _e('Design adaption for your theme', 'quizmaster'); ?></li>
                <li><?php _e('Creation of additional modules for your needs', 'quizmaster'); ?></li>
                <li style="display: none;"><?php _e('Premium Support', 'quizmaster'); ?></li>
            </ol>

            <h3><?php _e('Contact us:', 'quizmaster'); ?></h3>
            <ol style="list-style-type: disc;">
                <li><?php _e('Send us an e-mail', 'quizmaster'); ?> <a href="mailto:quizmaster@it-gecko.de"
                                                                        style="font-weight: bold;">quizmaster@it-gecko.de</a>
                </li>
                <li><?php _e('The e-mail must be written in english or german', 'quizmaster'); ?></li>
                <li><?php _e('Explain your wish detailed and exactly as possible', 'quizmaster'); ?>
                    <ol style="list-style-type: disc;">
                        <li><?php _e('You can send us screenshots, sketches and attachments', 'quizmaster'); ?></li>
                    </ol>
                </li>
                <li><?php _e('Send us your full name and your web address (webpage-URL)', 'quizmaster'); ?></li>
                <li><?php _e('If you wish design adaption, we additionally need the name of your theme',
                        'quizmaster'); ?></li>
            </ol>

            <p>
                <?php _e('After receiving your e-mail we will verify your request on feasibility. After this you will receive e-mail from us with further details and offer.',
                    'quizmaster'); ?>
            </p>

            <p>
                <?php _e('Extended support in first 6 months. Reported bugs and updates of QuizMaster are supported. Exception are major releases (update of main version)',
                    'quizmaster'); ?>
            </p>
        </div>

        <?php
    }
}