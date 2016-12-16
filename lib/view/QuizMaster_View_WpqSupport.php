<?php

class QuizMaster_View_WpqSupport extends QuizMaster_View_View
{

    public function show()
    {
        ?>

        <div class="wrap">
            <h3>QuizMaster on Github</h3>
            <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=star&count=true"
                    frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
            <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=watch&count=true&v=2"
                    frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
            <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=fork&count=true"
                    frameborder="0" scrolling="0" width="100px" height="20px"></iframe>


            <h3><?php _e('QuizMaster special modification', 'quizmaster'); ?></h3>
            <strong><?php _e('You need special QuizMaster modification for your website?',
                    'quizmaster'); ?></strong><br>
            <a class="button-primary" href="admin.php?page=quizMaster&module=info_adaptation"
               style="margin-top: 5px;"><?php _e('Learn more', 'quizmaster'); ?></a>

            <h3>QuizMaster Wiki</h3>

            <a class="button-primary" target="_blank" href="https://github.com/goldhat/QuizMaster/wiki">--> Wiki
                <--</a>

            <h3 style="margin-top: 40px;"><?php _e('Translate QuizMaster', 'quizmaster'); ?></h3>

            <h4><?php _e('You need:', 'quizmaster'); ?></h4>
            <ul style="list-style: disc; padding-left: 10px; list-style-position: inside;">
                <li><a href="http://www.poedit.net/" target="_blank">PoEdit</a></li>
                <li><a href="http://plugins.svn.wordpress.org/quizmaster/trunk/languages/quizmaster.pot"
                       target="_blank"><?php _e('Latest POT file', 'quizmaster'); ?></a></li>
            </ul>

            <h4>PoEdit:</h4>
            <ul style="padding-left: 10px; list-style: disc inside;">
                <li><?php _e('Open PoEdit', 'quizmaster'); ?></li>
                <li><?php _e('File - New catalogue from POT file...', 'quizmaster'); ?></li>
                <li><?php _e('Choose quizmaster.pot', 'quizmaster'); ?></li>
                <li><?php _e('Set "Translation properties"', 'quizmaster'); ?></li>
                <li><?php _e('Save PO file - with the name "wp-pro-qioz-de_DE.po"', 'quizmaster'); ?>
                    <ul style="list-style: disc; padding-left: 10px; list-style-position: inside;">
                        <li><?php _e('replace de_DE with your countries short code (e.g. en_US, nl_NL...)',
                                'quizmaster'); ?></li>
                    </ul>
                </li>
                <li><?php _e('Translate', 'quizmaster'); ?></li>
                <li><?php _e('Save', 'quizmaster'); ?></li>
                <li><?php _e('Upload generated *.mo file to your server, to /wp-content/plugins/quizmaster/languages',
                        'quizmaster'); ?></li>
                <li><?php _e('Finished', 'quizmaster'); ?></li>
            </ul>

            <h4><?php _e('Please note', 'quizmaster'); ?>:</h4>

            <p><?php _e('You can translate QuizMaster from existing to existing language (e.g. english to english) e.g. to rename buttons.',
                    'quizmaster'); ?></p>

        </div>

        <?php
    }
}
