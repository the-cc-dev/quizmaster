<div class="qm-tabs">

	<ul>

		<?php foreach( $tabs as $tab ) : ?>

			<li class="<?php if( $tab['key'] == $openTab ) { print 'active'; } ?>" data-key="<?php print $tab['key']; ?>">
				<?php print $tab['label']; ?>
			</li>

		<?php endforeach; ?>

	</ul>

</div>

<script>

jQuery(document).ready(function( $ ) {

	initTabs();

	// init
	function initTabs() {
		var activeTab = $('.qm-tabs li.active');

		if( !activeTab.length ) {
			activeTab = $('.qm-tabs li:first-child').addClass('active')
		}

		openTab( activeTab.data('key') )

	}

	// tab click
	$('.qm-tabs li').click( function() {

		// make tab active
		$('.qm-tabs li').removeClass('active');
		$(this).addClass('active');

		// show matching fields
		var tabKey = $(this).data('key');
		openTab( tabKey )

	});

	// open tab
	function openTab( tabKey ) {
		$('.qm-field-wrap').hide();
		$('.qm-field-wrap[data-tab=' + tabKey + ']').show();
	}

});

</script>
