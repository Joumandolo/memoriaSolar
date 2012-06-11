<div id="sidebar">
	<?php if (!is_home()) { ?>
		
		<div id="onecol">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar One Column') ) : ?> 
			<?php endif; ?>
		</div> <!-- end #onecol -->
			
	<?php }; ?>
	
	<?php if (is_home()) { ?>
		<div id="firstcol">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Homepage Left Column') ) : ?> 
			<?php endif; ?>
		</div> <!-- end #firstcol -->
		
		<div id="secondcol">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Homepage Right Column') ) : ?> 
			<?php endif; ?>
		</div> <!-- end #secondcol -->
	<?php } else { ?>
		<div id="firstcol">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Left Column') ) : ?> 
			<?php endif; ?>
		</div> <!-- end #firstcol -->
		
		 <!-- end #secondcol eliminado DIV-->
	<?php }; ?>
	
</div> <!-- end sidebar -->