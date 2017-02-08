<select name="<?php print $selectName; ?>">
<option value=""><?php _e( $defaultLabel, 'quizmaster' ); ?></option>
<?php
  foreach ($values as $val => $label) {
    printf
      (
        '<option value="%s"%s>%s</option>',
        $val,
        $val == $selected? ' selected="selected"':'',
        $label
      );
    }
  ?>
</select>
