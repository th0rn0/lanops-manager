<div class="form-group" id="poll_options">
	<div class="form-group">
		{{ Form::text('options[]', '', array('id'=>'', 'class'=>'form-control')) }}
	</div>
</div>
<button type="button" id="poll_options_add" class="btn btn-default btn-block">Extra Option</button> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
 	$(document).ready(function() {
	    var max_fields = 20;
	    var wrapper = $("#poll_options");
	    var add_button = $("#poll_options_add");

	    var x = 1;
	    $(add_button).click(function(e) {
	        e.preventDefault();
	        if (x < max_fields) {
	            x++;
	            $(wrapper).append('<div class="form-group">{{ Form::text("options[]", "", array("id"=>"", "class"=>"form-control")) }}</div>'); //add input box
	        } else {
	            alert('You Reached the limits')
	        }
	    });

	    $(wrapper).on("click", ".delete", function(e) {
	        e.preventDefault();
	        $(this).parent('div').remove();
	        x--;
	    })
	});
</script>
