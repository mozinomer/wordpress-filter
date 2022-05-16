


<?php wp_footer(); ?>

<script type="text/javascript">
	$('.selectorBrand select').change( function(e) {
		var val = $(this).val()
		val = val.replaceAll(' ', '-');
		window.location.replace("<?php echo site_url(); ?>/brand/"+val)
	})
</script>

</body>
</html>
