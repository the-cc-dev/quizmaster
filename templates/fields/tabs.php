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

	

});

</script>
