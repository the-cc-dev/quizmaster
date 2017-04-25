<?php

$clozeData = $view->fetchCloze($answers->getAnswer());

$view->_clozeTemp = $clozeData['data'];
$cloze = do_shortcode(apply_filters('comment_text',
    $clozeData['replace']));
$cloze = $clozeData['replace'];

echo preg_replace_callback('#@@quizMasterCloze@@#im', array($view, 'clozeCallback'), $cloze);

?>
