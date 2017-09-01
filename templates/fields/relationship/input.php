<label class="qm-field-label"><?php print $field['label']; ?></label>

<?php

// var_dump($field);

$post_type = $field['post_type'][0];

$choices = array(
	'0' => 'First',
	'1' => 'Second',
	'2' => 'Third',
);

?>

<div class="qm-field qm-field-relationship-wrap" style="overflow: hidden;">

	<div>
		<h2>Search Bar</h2>
	</div>


	<div class="qm-field-relationship-left qm-relationship-pool">

		<h2>RELATIONSHIP Pool</h2>

		<?php
			foreach( $choices as $choiceKey => $choice ) : ?>

			<li data-key="<?php print $choiceKey; ?>"><?php print $choice; ?></li>

		<?php endforeach;
		?>
	</div>

	<div class="qm-field-relationship-right">
		<h2>Selected RELATIONSHIP ITEMS</h2>

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

	// relationship pool selection
	$('.qm-relationship-pool li').on( 'click', function() {

		var item = $(this)

		if( item.hasClass('selected') ) {
			return;
		}

		var allRelationships = $('.qm-tabs li');

		// set relationship selected
		item.addClass('selected');

		// assign to quiz
		makeSelection( item )

	});

	function makeSelection( item ) {

		var selectedRelationshipHolder = $('.qm-relationship-selections ul');
		var itemCopy = item.clone()
		var selectedItem = selectedRelationshipHolder.append( itemCopy )
		selectedItem.find( itemCopy ).append('<span class="remove">Remove</span>')

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


		console.log( itemSelector )

		item.remove()

	}

	// show remove on hover over selected item
	$( document ).on( 'hover', '.qm-relationship-selections li', function() {


		console.log( $( 'span.remove', this ) );

		$( 'span.remove', this ).show()
	});

});

</script>
