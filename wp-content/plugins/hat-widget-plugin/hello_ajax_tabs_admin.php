<?php

	function hat_admin_menu() 
	{
		add_theme_page("Hello ajax tabs", "Hello ajax tabs", 'edit_themes', basename(__FILE__), 'hat_options_page');
	}

	add_action('admin_menu', 'hat_admin_menu');

	function hat_options_page(){
		if ( $_POST['hat_options_page_update'] == 'true' ) { hat_options_update(); }
?>
		<div class="wrap hat-admin">
			<div id="icon-themes2" class="hat-logo"><h2>Hello ajax tabs options</h2><span>Version 2.2</span></div>
			
			<form method="POST" action="">
				<input type="hidden" name="hat_options_page_update" value="true" />
				<h4>Additional sidebars (each on new line)</h4>
				<textarea name="hat_additional_sidebars" cols="50" rows="10"><?php echo esc_textarea( get_option('hat_additional_sidebars'));?></textarea>
				<h4><input type="checkbox" name="hat_minified_css" id="hat_minified_css" <?php if(get_option('hat_minified_css')=='on') echo 'checked'; ?> /> Load minified css</h4>
				<h4>
					<select style="display:inline; margin-right:5px;" name="hat_display_widgets" id="hat_display_widgets">
						<?php
							$widget_values = array("Display widget id", "Display widget title", "Display widget title and id");	
							$widget_selected = get_option('hat_display_widgets');
							
							foreach($widget_values as $key => $value){
								if($key == $widget_selected){ 
									$selected = 'selected="selected"'; 
								}else{
									$selected = '';
								}
								echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
							}
						?>
					</select> 
					 Widgets list display options
				</h4>
				<p><input type="submit" name="search" value="Save Options" class="button" /></p>
			</form>
		</div>

		<?php
	}

	function hat_options_update()
	{
		update_option('hat_additional_sidebars', 	$_POST['hat_additional_sidebars']);
		update_option('hat_minified_css',    $_POST['hat_minified_css']);  
		update_option('hat_display_widgets',    $_POST['hat_display_widgets']); 
	}

	$hat_additional_widgets=explode("\n",get_option('hat_additional_sidebars'));
	foreach($hat_additional_widgets  as  $value)
	{
		$value=trim($value);
		if  (!empty($value))
		{
			register_sidebar(array(
								'id' => $value,
								'name' => $value,
								'description' => 'Widgets in this area will be shown as tabs.',
								'before_widget' => '<div>',
								'after_widget' => '</div>',
								'before_title' => '<h2>',
								'after_title' => '</h2>',
							));
		}
	}


?>