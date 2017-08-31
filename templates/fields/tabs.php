<div class="qm-tabs">

	<ul>

		<?php foreach( $tabs as $tab ) : ?>

			<li data-key="<?php print $tab['key']; ?>">
				<?php print $tab['label']; ?>
			</li>

		<?php endforeach; ?>

	</ul>

</div>

<script>

jQuery(document).ready(function( $ ) {

	$('.qm-tabs li').click( function() {

		// make tab active
		$('.qm-tabs li').removeClass('active');
		$(this).addClass('active');

		// show matching fields
		var tabKey = $(this).data('key');
		$('.qm-field-wrap').hide();
		$('.qm-field-wrap[data-tab=' + tabKey + ']').show();

	});

});

</script>
