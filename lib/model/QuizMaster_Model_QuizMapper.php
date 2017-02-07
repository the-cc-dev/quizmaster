<?php

class QuizMaster_Model_QuizMapper extends QuizMaster_Model_Mapper {

  /**
   * @param $id
   * @return QuizMaster_Model_Quiz
   */
  public function fetch($id) {
    $fields = get_fields($id);
    $fields['id'] = $id;
    $fields['name'] = get_the_title( $id );
    $fields['text'] = $fields['quizDescription'];
    $quizModel = new QuizMaster_Model_Quiz( $fields );
    return $quizModel;
  }

  /**
   * @return QuizMaster_Model_Quiz[]
   */
  public function fetchAll() {
    $quizzes = array();
    $args = array(
      'post_type' => 'quizmaster_quiz',
      'orderby' => 'ASC',
      'posts_per_page'=> -1
    );
    $wp_query = new WP_Query($args);
    if( !$wp_query->have_posts() ) {
      return false;
    }
    foreach( $query->posts as $post ) {
      $quizzes[] = $this->fetch( $post->ID );
    }
    return $quizzes;
  }

  /**
   * @param $id
   * @return int
   */
  public function sumQuestionPoints($id) {
    return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT SUM(points) FROM {$this->_tableQuestion} WHERE quiz_id = %d AND online = 1",
      $id));
  }

  public function countQuestion($id) {
    return $this->_wpdb->get_var($this->_wpdb->prepare("SELECT COUNT(*) FROM {$this->_tableQuestion} WHERE quiz_id = %d AND online = 1",
      $id));
  }

}
