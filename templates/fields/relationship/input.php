<?php

$post_type = $field->definition['post_type'][0];
$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => 10 ));

$choices = array();

foreach( $posts as $post ) {
	$choices[ $post->ID ] = $post->post_title;
}

?>

<div class="qm-field qm-field-relationship-wrap">

	<input class="qm-relationship-data" type="hidden" id="<?php print $field->key; ?>" name="<?php print $field->key; ?>" value='<?php $field->renderValue(); ?>' />

	<div class="quizmaster-row">
		<div class="quizmaster-col-12">

			<label class="qm-field-label"><?php print $field->label; ?></label>

			<div>
				<h3>Search Bar</h3>
			</div>

		</div>
	</div>

	<div class="quizmaster-row qm-field-relationship-selector">

		<div class="quizmaster-col-6 qm-field-relationship-left qm-relationship-pool">

			<h2><?php print $field->definition['selection_title']; ?></h2>

			<?php
				foreach( $choices as $choiceKey => $choice ) : ?>

				<li data-key="<?php print $choiceKey; ?>"><?php print $choice; ?></li>

			<?php endforeach; ?>
		</div>

		<div class="quizmaster-col-6 qm-field-relationship-right">

			<h2><?php print $field->definition['selected_title']; ?></h2>

			<div class="qm-relationship-selections">
				<ul></ul>
			</div>

		</div>

	</div>

</div>

<script>

jQuery(document).ready(function( $ ) {

	quizmasterRelationshipInit();

	function quizmasterRelationshipInit() {

		var value = getRelationshipValue()

		$.each( value, function( index, id ) {

			var item = $('.qm-relationship-pool li[data-key="' + id + '"]')
			initSelection( item )

		});

	}

	function getRelationshipValue() {

		if ( $( '.qm-relationship-data' ).val == '' ){

		  return []
		} else {
			var value = $('.qm-relationship-data').val()
			try {
				return JSON.parse( value )
			}
			catch (e) {
				return []
			}

		}

	}

	function saveRelationshipValue( data ) {
		 $('.qm-relationship-data').val( JSON.stringify( data ))
	}

	// relationship pool selection
	$('.qm-relationship-pool li').on( 'click', function() {

		var item = $(this)

		if( item.hasClass('selected') ) {
			return;
		}

		// assign to quiz
		makeSelection( item )

	});

	function initSelection( item ) {

		// set relationship selected
		item.addClass('selected');

		var selectedRelationshipHolder = $('.qm-relationship-selections ul');
		var itemCopy = item.clone()
		var selectedItem = selectedRelationshipHolder.append( itemCopy )
		selectedItem.find( itemCopy ).append('<span class="remove">Remove</span>')

	}

	function makeSelection( item ) {

		// set relationship selected
		item.addClass('selected');

		var selectedRelationshipHolder = $('.qm-relationship-selections ul');
		var itemCopy = item.clone()
		var selectedItem = selectedRelationshipHolder.append( itemCopy )
		selectedItem.find( itemCopy ).append('<span class="remove">Remove</span>')

		// save value
		var value = getRelationshipValue();
		value.push( item.data('key') )
		saveRelationshipValue( value )

	}

	// click on remove
	$( document ).on("click", ".qm-relationship-selections span.remove", function() {

		var item = $(this).closest('li')
	  removeSelection( item )

	});

	// remove selection
	function removeSelection( item ) {

		var itemKey = item.data('key')
		var itemSelector = $('.qm-relationship-pool li[data-key="' + itemKey + '"]')
		itemSelector.removeClass('selected')

		// remove from stored relationship data
		var value = getRelationshipValue()
		var index = value.indexOf( itemKey );

    if (index > -1) {
    	value.splice(index, 1);
    }

		saveRelationshipValue( value )

		item.remove()

	}

	// show remove on hover over selected item
	$( document ).on('mouseenter', '.qm-relationship-selections li', function( event ) {
    $( 'span.remove', this ).show()
		$(this).addClass('hover')
	}).on('mouseleave', '.qm-relationship-selections li', function( event ) {
		$( 'span.remove', this ).hide()
		$(this).removeClass('hover')
	});

});

</script>
