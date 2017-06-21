<?php

class QuizMaster_Helper_CheckAnswers {

	public function ajaxCheckAnswer() {

		$_POST = $_POST['data'];
		$quizId = $_POST['quizId'];


		print json_encode( array('test' => 787, 'quizId' => $quizId ));
		die();

	}

}
