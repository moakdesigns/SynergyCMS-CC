</div>
<footer><?php echo $text_footer; ?></footer>
<script>
	$(document).ready(function() {
		var navDisplay = true;
		$('.menuBtn2').click(function(){
			if(navDisplay == true){
				$('nav').hide();
				$('.pageTitle').css('margin-left', '0px');
				$('#container').css('margin-left', '0px');
				navDisplay = false;	
			} else {
				$('nav').show();
				$('.pageTitle').css('margin-left', '53px');
				$('#container').css('margin-left', '53px');
				navDisplay = true;
			}
		})
	});
</script>
</body></html>