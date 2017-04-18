<?php

class QuizMaster_Helper_Submenu {

  public static function position( $slug, $position ) {
    $GLOBALS['quizmaster_menu'][ $position ] = $slug;
  }

  public static function add( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position ) {

    // enable positioning
    self::position( $menu_slug, $position );

    return add_submenu_page(
      $parent_slug,
      __($page_title, 'quizmaster'),
      __($menu_title, 'quizmaster'),
      $capability,
      $menu_slug,
      $function
    );

  }

}
