
<?php

//var_dump($field);

$post_type = $field['post_type'][0];
$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => 10 ));

$choices = array();

foreach( $posts as $post ) {
	//var_dump($post);
	$choices[ $post->ID ] = $post->post_title;
}


?>

<div class="qm-field qm-field-relationship-wrap" style="overflow: hidden;">

	<input class="qm-relationship-data" type="hidden" id="<?php print $field['key']; ?>" name="<?php print $field['key']; ?>" value="<?php print $field['value']; ?>" />

	<label class="qm-field-label"><?php print $field['label']; ?></label>

	<div>
		<h3>Search Bar</h3>
	</div>


	<div class="qm-field-relationship-left qm-relationship-pool">

		<h2><?php print $field['selection_title']; ?></h2>

		<?php
			foreach( $choices as $choiceKey => $choice ) : ?>

			<li data-key="<?php print $choiceKey; ?>"><?php print $choice; ?></li>

		<?php endforeach;
		?>
	</div>

	<div class="qm-field-relationship-right">

		<h2><?php print $field['selected_title']; ?></h2>

		<div class="qm-relationship-selections">
			<ul>

			</ul>
		</div>

	</div>

</div>

<style>

.qm-field-relationship-wrap li {
	cursor: pointer;
}

.qm-field-relationship-right {
	border: solid 1px #999;
	padding: 15px;
	float: left;
	width: 50%;
	box-sizing: border-box;
}

.qm-field-relationship-left {
	float: left;
	border: solid 1px #999;
	padding: 15px;
	width: 50%;
	box-sizing: border-box;
}

.qm-field-relationship-left li {
	list-style: none;
	padding: 0;
	margin: 0;
}

.qm-relationship-pool li {
	font-weight:bold;
	margin: 6px 0;
	font-size: 13px;
}
.qm-relationship-pool li.selected {
	color: #ccc;
}

.qm-relationship-selections span.remove {
	margin: 0 0 0 10px;
	cursor: pointer;
	display: none;
}

</style>

<script>

jQuery(document).ready(function( $ ) {

	quizmasterRelationshipInit();

	function quizmasterRelationshipInit() {

		var value = getRelationshipValue()

		$.each( value, function( index, id ) {

			console.log( id )

			var item = $('.qm-relationship-pool li[data-key="' + id + '"]')
			initSelection( item )

		});

	}

	function getRelationshipValue() {

		if ( $( '.qm-relationship-data' ).val == '' ){
		  return []
		} else {
			var value = $('.qm-relationship-data').val()
			return JSON.parse( value )
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
	}).on('mouseleave', '.qm-relationship-selections li', function( event ) {
		$( 'span.remove', this ).hide()
	});

});

</script>
