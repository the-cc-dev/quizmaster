<?php

class QuizMaster_View_GlobalHelperTabs
{


    public function getHelperSidebar()
    {
        ob_start();

        $this->showHelperSidebar();

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function getHelperTab()
    {
        ob_start();

        $this->showHelperTabContent();

        $content = ob_get_contents();

        ob_end_clean();

        return array(
            'id' => 'wp_pro_quiz_help_tab_1',
            'title' => __('QuizMaster', 'quizmaster'),
            'content' => $content,
        );
    }

    private function showHelperTabContent()
    {
        ?>

        <h2>QuizMaster</h2>

        <h4>QuizMaster on Github</h4>

        <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=star&count=true"
                frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
        <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=watch&count=true&v=2"
                frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
        <iframe src="https://ghbtns.com/github-btn.html?user=goldhat&repo=QuizMaster&type=fork&count=true"
                frameborder="0" scrolling="0" width="100px" height="20px"></iframe>

        <h4><?php _e('Donate', 'quizmaster'); ?></h4>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="KCZPNURT6RYXY">
            <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0"
                   name="submit" alt="PayPal – The safer, easier way to pay online.">
            <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
        </form>

        <?php
    }

    private function showHelperSidebar()
    {
        ?>

        <p>
            <strong><?php _e('For more information:'); ?></strong>
        </p>
        <p>
            <a href="admin.php?page=quizMaster_wpq_support"><?php _e('Support', 'quizmaster'); ?></a>
        </p>
        <p>
            <a href="https://github.com/goldhat/QuizMaster" target="_blank">Github</a>
        </p>
        <p>
            <a href="https://github.com/goldhat/QuizMaster/wiki" target="_blank"><?php _e('Wiki',
                    'quizmaster'); ?></a>
        </p>
        <p>
            <a target="_blank"
               href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KCZPNURT6RYXY"><?php _e('Donate',
                    'quizmaster'); ?></a>
        </p>


        <?php
    }
}