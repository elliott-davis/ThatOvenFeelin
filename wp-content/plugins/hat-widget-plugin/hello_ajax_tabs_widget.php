<?php

function hat_display_widgets($id, $title) {
	$display_widgets = get_option('hat_display_widgets');
	switch($display_widgets){
		case '0': return $id; break;
		case '1': return $title; break;
		case '2': return $title." (".$id.")"; break;
	}
}

function hat_get_registered_sidebars() {
	$sidebars;
    global $wp_registered_sidebars;
    if( empty( $wp_registered_sidebars ) )
        return;
	foreach( $wp_registered_sidebars as $sidebar ) : 
       $sidebars[]= array('id' => $sidebar['id'], 'name' => $sidebar['name']);
    endforeach; 
	return $sidebars;
}

function hat_get_sidebar_widgets($sidebar_id) {
    $widget_active = get_option('sidebars_widgets');
	if($widget_active && is_array($widget_active[$sidebar_id])){
		foreach( $widget_active[$sidebar_id] as $sidebar ) : 
			$return_array[]= $sidebar;
	    endforeach; 
	 }
	return $return_array;
}

function hat_get_classes($widgets = array()){
	$classes = array();
	if( is_array($widgets) ){
		foreach($widgets as $widget){
			$class = substr($widget, 0, strrpos($widget, "-"));
			$id = substr($widget, strrpos($widget, "-") + 1);
			array_push($classes, array('class' => $class, 'id' => $id));
		}
	}
	return $classes;
}

function hat_get_widget_titles($widgets = array()){
	$classes = hat_get_classes($widgets); 
	$titles = array(); 
	$counter = 1; 
	foreach($classes as $inst){
		$class = get_option('widget_'.$inst['class']);
		$title = $class[$inst['id']]['title'];
		if($title=='') $title = $counter;
			array_push($titles, $title);
			$counter++;
	}
	return $titles;
}

add_action("wp_ajax_hat_ajax_widget", "hat_ajax_widget_callback");
add_action("wp_ajax_nopriv_hat_ajax_widget", "hat_ajax_widget_callback");

function hat_ajax_widget_callback(){
	if ( !function_exists('dynamic_sidebar') || !hat_show_sidebar($_REQUEST['sidebar_id'],'div', array($_REQUEST["widget_id"])) ) : endif;
	die();
}

function hat_show_sidebar($index = 1, $container = 'li', $widgets = array()) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	if ( is_int($index) ) {
		$index = "sidebar-$index";
	} else {
		$index = sanitize_title($index);
		foreach ( (array) $wp_registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}
	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( empty( $sidebars_widgets ) )
		return false;
	if ( empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
		return false;
	$sidebar = $wp_registered_sidebars[$index];
	$did_one = false;
	foreach ( (array) $sidebars_widgets[$index] as $id ) {
		if ( !isset($wp_registered_widgets[$id]) ) continue;
		$params = array_merge(
			array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
			(array) $wp_registered_widgets[$id]['params']
		);
		$classname_ = '';
		foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
			if ( is_string($cn) )
				$classname_ .= '_' . $cn;
			elseif ( is_object($cn) )
				$classname_ .= '_' . get_class($cn);
		}
		$classname_ = ltrim($classname_, '_');
		$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
		$params = apply_filters( 'dynamic_sidebar_params', $params );
		if(!empty($widgets)){
			if(in_array($id,$widgets)){
				$callback = $wp_registered_widgets[$id]['callback'];
	        }else{
				echo $callback='';
			}
		}else{
			$callback = $wp_registered_widgets[$id]['callback'];
		}
				do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );
		if ( is_callable($callback) ) {
			 echo $container ? '<'.$container.' id="'.$id.'" class="clearfix" style="width:100%;height: auto;">' : '';
			call_user_func_array($callback, $params);
			 echo $container ? '</'.$container.'>' : '';
			$did_one = true;
		}
	}
	return $did_one;
}

/**Hello Tabs Widget Class*/
class hat_widget extends WP_Widget {
protected static $did_script = false;

    /** constructor */
    function hat_widget() {
        parent::WP_Widget(false, $name = 'Hello ajax tabs', array( 'description' => 'Widget for displaying hello ajax tabs on your page' ));
		add_action('wp_enqueue_scripts', array($this, 'scripts'));		
    }
  
   function scripts(){
    if(!self::$did_script && is_active_widget(false, false, $this->id_base, true)){
	  wp_register_script( 'easing_js', plugin_dir_url( __FILE__ ).'js/jquery.easing-1.3.min.js',array('jquery'));
	  	wp_register_script( 'hat_min_js', plugin_dir_url( __FILE__ ).'js/hat.2.2.min.js',array('jquery'));
		wp_localize_script( 'hat_min_js', 'hatAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
		wp_enqueue_script('hat_min_js');
      self::$did_script = true;
    }           	
  }
   
   
    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );
        $title 	    = $instance['title'];
		$wp_sidebar 		= $instance['wp_sidebar'];
		$wp_widgets[]     = $instance['wp_widgets'];
		$unique 		= $instance['unique'];
		$animSpeed     = $instance['animSpeed'];
		$animation     = $instance['animation'];
		$easing     = $instance['easing'];
		$easingLoad = $instance['easingload'];
		$ajax     = $instance['ajax'];
		$equalTabs 		= $instance['equalTabs'];
        $classID 		= $instance['classID'];
		$heading 		= $instance['heading'];
		$vertical 		= $instance['vertical'];
		$menuWidth 		= $instance['menuWidth'];
		$menuHeight 		= $instance['menuHeight'];
		$fixedHeight 		= $instance['fixedHeight'];
		$fixedHeightValue 		= $instance['fixedHeightValue'];
		$dropDown = $instance['dropDown'];
		$responsiveDropDown = $instance['responsiveDropDown'];
		$activeTab = $instance['activeTab'];
		if($instance['easingload']) wp_enqueue_script('easing_js');
        ?>
        <?php echo $before_widget;  ?>
		<?php if($unique) $unique = $unique."-";?>
		<div id="<?php echo $unique;?>tabs-wrap" class="tabs-wrap" style="display:none" >
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<div id="<?php echo $unique;?>tabs" class="tabs <?php echo $classID; ?> clearfix">
						<?php if($ajax){ 
								$titles = hat_get_widget_titles($wp_widgets[0]);
								$counter = 0;
						?>
						<ul id="<?php echo $unique; ?>tabs-menu" class="tabs-menu clearfix">
							<?php 
								foreach($titles as $title){
									echo '<li class="'.$wp_widgets[0][$counter].'"><a href="#">'.$title.'</a></li>';
									$counter++;
								}
							?>
						</ul>
						<select class="tabs-menu">
							<?php 
								$counter = 0;
								foreach($titles as $title){
									echo '<option class="'.$wp_widgets[0][$counter].'">'.$title.'</option>';
									$counter++;
								}
							?>
						</select>
						<?php } ?>
				<div id="<?php echo $unique;?>tabs-content" class="tabs-content clearfix">
						<div class="loader-wrap" style="display:none"><div class="loader"></div></div>
						<div id="<?php echo $unique;?>tabs-content-inner" class="tabs-content-inner clearfix" <?php if($fixedHeight && $fixedHeightValue != '') echo 'style="height:'.$fixedHeightValue.'px"'; ?>>
						<?php if(!$ajax){ ?>
									<?php if ( !function_exists('dynamic_sidebar') || !hat_show_sidebar($wp_sidebar,'div', $wp_widgets[0]) ) : ?>
									<?php endif; ?>
								<?php } ?>
						</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				 $('#<?php echo $unique;?>tabs-wrap').fadeIn();
			     $('#<?php echo $unique;?>tabs').helloAjaxTabs({
					menuId: '#<?php echo $unique; ?>tabs-menu',
					contentId: '#<?php echo $unique; ?>tabs-content-inner',
					easing: <?php if($easingLoad){ echo '"'.$easing.'"';}else{echo "false";} ?>,
					effect: <?php echo '"'.$animation ? '"'.$animation.'"' : '"showHideAnimate"'.'"'; ?>,
					speed: <?php echo $animSpeed ? $animSpeed : '500';?>,
					ajax: <?php echo $ajax ? $ajax : 'false'; ?>,
					equalTabs: <?php echo $equalTabs ? $equalTabs : 'false'; ?>,
					vertical: <?php echo $vertical ? $vertical : 'false'; ?>,
					heading: <?php echo $heading ? '"'.$heading.'"' : '"h2"'; ?>,
					dropDown: <?php echo $dropDown ? $dropDown : 'false';  ?>,
					activeTab: <?php echo $activeTab ? $activeTab : '1';  ?>,
					responsiveDropDown: <?php echo $responsiveDropDown ? $responsiveDropDown : 'false';  ?>,
					fixedHeight: <?php echo $fixedHeight ? $fixedHeight : 'false'; ?>,
					menuWidth: <?php echo $menuWidth ? $menuWidth : 'false'; ?>,
					menuHeight: <?php echo $menuHeight ? $menuHeight : 'false'; ?>
					<?php if($ajax) echo ', sidebar: "'.$wp_sidebar.'"'; ?>	
				 });
				
				
			});
		</script>
        <?php echo $after_widget; ?>
        <?php
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['animSpeed'] = $new_instance['animSpeed'];
		$instance['wp_widgets'] = $new_instance['wp_widgets'];
		$instance['easing'] = $new_instance['easing'];
		$instance['animation'] = $new_instance['animation'];
		$instance['wp_sidebar'] = $new_instance['wp_sidebar'];
		$instance['unique'] = strip_tags($new_instance['unique']);
		$instance['classID'] = $new_instance['classID'];
		$instance['heading'] = $new_instance['heading'];
		$instance['ajax'] = $new_instance['ajax'];
		$instance['equalTabs'] = $new_instance['equalTabs'];
		$instance['easingload'] = $new_instance['easingload'];
		$instance['vertical'] = $new_instance['vertical'];
		$instance['menuHeight'] = $new_instance['menuHeight'];
		$instance['menuWidth'] = $new_instance['menuWidth'];
		$instance['fixedHeight'] = $new_instance['fixedHeight'];
		$instance['activeTab'] = $new_instance['activeTab'];
		$instance['fixedHeightValue'] = $new_instance['fixedHeightValue'];
		$instance['responsiveDropDown'] = $new_instance['responsiveDropDown'];
		$instance['dropDown'] = $new_instance['dropDown'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance) {

		$effects = array('swing', 'easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic',
						 'easeOutCubic','easeInOutCubic','easeInQuart','easeOutQuart','easeInOutQuart',
						 'easeInQuint','easeOutQuint','easeInOutQuint','easeInSine','easeOutSine',
						 'easeInOutSine','easeInExpo','easeOutExpo','easeInOutExpo','easeInCirc',
						 'easeOutCirc','easeInOutCirc','easeInElastic','easeOutElastic','easeInOutElastic',
						 'easeInBack','easeOutBack','easeInOutBack','easeInBounce','easeOutBounce','easeInOutBounce'
						);
						
		$animations = array('fadeInFadeOutDelay','fadeInSlideUp','fadeInSlideUpDelay', 'slideDownSlideUpDelay', 
						    'slideDownFadeOutDelay', 'showHide', 'showHideDelay',
						    'leftShowLeftHide', 'topShowLeftHide', 'bottomShowLeftHide',  'rightShowLeftHide', 
							'leftShowRightHide', 'topShowRightHide', 'bottomShowRightHide', 'rightShowRightHide', 
							'bottomShowBottomHide', 'leftShowBottomHide', 'rightShowBottomHide', 'topShowBottomHide',
							'topShowTopHide', 'leftShowTopHide', 'rightShowTopHide', 'bottomShowTopHide' 
							);
		
		
		
		$animation = esc_attr($instance['animation']);	
        $title = esc_attr($instance['title']);	
	    $animSpeed = esc_attr($instance['animSpeed']);
		$wp_widgets[] = $instance['wp_widgets'];
		
		$wp_sidebar = esc_attr($instance['wp_sidebar']);
		$easing =esc_attr($instance['easing']);
	    $unique = esc_attr($instance['unique']);				
        $classID = esc_attr($instance['classID']);
        $ajax = esc_attr($instance['ajax']);
		$equalTabs = esc_attr($instance['equalTabs']);
		$heading = esc_attr($instance['heading']);
		$easingload = esc_attr($instance['easingload']);
		$vertical = esc_attr($instance['vertical']);
		$menuWidth = esc_attr($instance['menuWidth']);
		$menuHeight = esc_attr($instance['menuHeight']);
		$fixedHeight = esc_attr($instance['fixedHeight']);
		$fixedHeightValue = esc_attr($instance['fixedHeightValue']);
		$responsiveDropDown = esc_attr($instance['responsiveDropDown']);
		$dropDown = esc_attr($instance['dropDown']);
		$activeTab = esc_attr($instance['activeTab']);
		
        ?>
         
		 <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		 
		 <span style="color:#afafaf;font-size:10px;"><?php _e('Main settings'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />	
		 
		 <p>	
			<label for="<?php echo $this->get_field_name('wp_sidebar'); ?>"><?php _e('Sidebar:'); ?></label>
			<span style="font-size: 10px;">[<a  title="Choose sidebar which holds your chosen widgets. If you created sidebar on 'Hello ajax tabs' settings page, it will be displayed on top of the page and will be already chosen in this dropdown list!">?</a>] </span>
			<select  name="<?php echo $this->get_field_name('wp_sidebar'); ?>" id="wp_sidebar" class="widefat">
				
				<?php 
				foreach (hat_get_registered_sidebars() as $sb) {
					if($sb['name'] != "Inactive Widgets"){
						if($wp_sidebar == '') $wp_sidebar = $sb['id'];
						echo '<option value="' . $sb['id']. '" id="' . $sb['id'] . '"', $wp_sidebar == $sb['id'] ? ' selected="selected"' : '', '>', $sb['name'], '</option>';
					}
				}
				?>
			</select>		
		</p> 
		
		<p>	

				<?php 
				if($wp_sidebar!='' ){
					is_array($wp_widgets[0]) ? '' : $wp_widgets[0] = hat_get_sidebar_widgets($wp_sidebar);
				?>
				<label for="<?php echo $this->get_field_name('wp_widgets'); ?>"><?php _e('Widgets:'); ?></label>
				<span style="font-size: 10px;">[<a  title="After saving widget's options, this list will appear on widget's form. It holds all widgets ids and titles from sidebar, which you chose above (choose display combination on plugin's settings page). By default all items are selected and all widgets from your sidebar will be displayed in tabs. You can also select/deselect  widgets by using combination of CTRL + Click. Also, plugin uses these widget ids as tabs buttons classes and tabs content ids. (Check HTML structure section of documentation page to see the usage of this parameter).">?</a>] </span>
				<select name="<?php echo $this->get_field_name('wp_widgets'); ?>[]" id="wp_widgets" class="widefat" multiple="multiple">
				
				<?php
				$tc = 0;
				$titles = hat_get_widget_titles($wp_widgets[0]);
				foreach (hat_get_sidebar_widgets($wp_sidebar) as $widget) {
						
					    if($widget != '' ){
						echo '<option value="' . $widget. '" id="' . $widget . '"', in_array($widget,$wp_widgets[0]) ? ' selected="selected"' : '', '>' , hat_display_widgets($widget, isset($titles[$tc]) ? $titles[$tc] : '') ,'</option>';
						}
						$tc++;
				}
				
				?>
				</select>
				<?php } ?>
		</p> 
		
		
		
		
		
		
		 		<span style="color:#afafaf;font-size:10px;"><?php _e('Additional settings'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />
		 
		 <p>
          <label for="<?php echo $this->get_field_id('unique'); ?>"><?php _e('Tabs ID:'); ?></label> 		  			
		  <span style="font-size: 10px;">[<a  title="This parameter is a prefix, which will be added to tabs ID and other tabs elements. Use this only if you want to create few tabs elements on 1 page. By default, tabs id is equal to '#tabs' and if you add for example 'first' word, tabs id will be equal to '#first-tabs'. (Check HTML structure section of documentation page to see the usage of this parameter).">?</a>] </span>
          <input class="widefat" id="<?php echo $this->get_field_id('unique'); ?>" name="<?php echo $this->get_field_name('unique'); ?>" type="text" value="<?php echo $unique; ?>" />

        </p>

		
		<p>
          <label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e('Header selector:'); ?></label> 		  			
		  <span style="font-size: 10px;">[<a  title="This plugin uses widgets titles as tabs button's text. Each of this titles usually surrounded with html tags like '<h2>', or '<h3>' and so on. By default this parameter equals to 'h2' and If you created sidebar for tabs content on 'Hello ajax tabs' settings page, you may leave this field blank.">?</a>] </span>
          <input class="widefat" id="<?php echo $this->get_field_id('heading'); ?>" name="<?php echo $this->get_field_name('heading'); ?>" type="text" value="<?php echo $heading; ?>" />

        </p>
		
		
				<p>
          <label for="<?php echo $this->get_field_id('animSpeed'); ?>"><?php _e('Speed:'); ?></label> 		  			
		  <span style="font-size: 10px;">[<a  title="Transition speed in milliseconds. Default value: '500'. Example: '1000' = 1 sec, '1500' = 1,5 seconds, and so on...">?</a>] </span>
          <input  id="<?php echo $this->get_field_id('animSpeed'); ?>" name="<?php echo $this->get_field_name('animSpeed'); ?>" class="widefat" type="text" value="<?php echo $animSpeed; ?>" />

        </p>
		
		
		<p>	
			<label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Effect:'); ?></label> 
			<span style="font-size: 10px;">[<a  title="Choose transition which you like best. We added fading, sliding and moving transitions.">?</a>] </span>
			<select name="<?php echo $this->get_field_name('animation'); ?>" id="<?php echo $this->get_field_id('animation'); ?>" class="widefat">
				<?php 
				foreach ($animations as $anim->name) {
					echo '<option value="' . $anim->name . '" id="' . $anim->name . '"', $animation == $anim->name ? ' selected="selected"' : '', '>', $anim->name, '</option>';
				}
				?>
			</select>
			
		</p> 
		
		<p>
          <label for="<?php echo $this->get_field_id('activeTab'); ?>"><?php _e('Active tab:'); ?></label> 		  			
		  <span style="font-size: 10px;">[<a  title="Active tab number. Default value: 1">?</a>] </span>
          <input class="widefat" id="<?php echo $this->get_field_id('activeTab'); ?>" name="<?php echo $this->get_field_name('activeTab'); ?>" type="text" value="<?php echo $activeTab; ?>" />

        </p>
		
		<p>
          <input id="<?php echo $this->get_field_id('ajax'); ?>" name="<?php echo $this->get_field_name('ajax'); ?>" type="checkbox" value="1" <?php checked( '1', $ajax ); ?>/>
          <label for="<?php echo $this->get_field_id('ajax'); ?>"><?php _e('Ajax loading'); ?></label> 
		  			<span style="font-size: 10px;">[<a  title="All widgets will be loaded with ajax. First widget in tabs will be loaded automatically and other widgets will be loaded by click on tabs button. This should encrease page's loading time. If you leave it unchecked - all widgets will be loaded along with the page.">?</a>] </span>
        </p>

		 
		<p>
          <input id="<?php echo $this->get_field_id('equalTabs'); ?>" name="<?php echo $this->get_field_name('equalTabs'); ?>" type="checkbox" value="1" <?php checked( '1', $equalTabs ); ?>/>
          <label for="<?php echo $this->get_field_id('equalTabs'); ?>"><?php _e('Equal tabs'); ?></label> 
		  			<span style="font-size: 10px;">[<a  title="Beta version. If you will check this checkbox, plugin will made your tabs buttons equal by width. Do not use this feature with vertical tabs! By default, tabs buttons width depend on inside text and this function will try to make them equal. So, for example your sidebar's width is 500 pixels and you created tabs element with 2 tabs. This function makes paddings, margins, borders calculation and tries to make tabs buttons 250 pixels width each.">?</a>] </span>
        </p>
		
		
		
		<span style="color:#afafaf;font-size:10px;"><?php _e('Easing support'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />
		
		<p>
          <input id="<?php echo $this->get_field_id('easingload'); ?>" name="<?php echo $this->get_field_name('easingload'); ?>" type="checkbox" value="1" <?php checked( '1', $easingload ); ?>/>
          <label for="<?php echo $this->get_field_id('easingload'); ?>"><?php _e('Include easing plugin'); ?></label> 
		  <span style="font-size: 10px;">[<a  title="Include easing plugin. Required if you want to choose easing effect for tabs transition">?</a>] </span>
        </p>
		
		<p>	
			<label for="<?php echo $this->get_field_id('easing'); ?>"><?php _e('Easing:'); ?></label> 
			<span style="font-size: 10px;">[<a  title="Easing method for tabs transition. Don't forget to include easing plugin.">?</a>] </span>
			<select name="<?php echo $this->get_field_name('easing'); ?>" id="<?php echo $this->get_field_id('easing'); ?>" class="widefat">
				<?php 
				foreach ($effects as $effect) {
					echo '<option value="' . $effect . '" id="' . $effect . '"', $easing == $effect ? ' selected="selected"' : '', '>', $effect, '</option>';
				}
				?>
			</select>
		</p> 	
			
		<span style="color:#afafaf;font-size:10px;"><?php _e('Vertical tabs support'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />	
			
		<p>
          <input id="<?php echo $this->get_field_id('vetical'); ?>" name="<?php echo $this->get_field_name('vertical'); ?>" type="checkbox" value="1" <?php checked( '1', $vertical ); ?>/>
          <label for="<?php echo $this->get_field_id('vertical'); ?>"><?php _e('Vertical tabs'); ?></label> 
		  <span style="font-size: 10px;">[<a  title="If you want to transform your standart tabs into vertical tabs - just check this special checkbox. You can also set menu width and height in pixel. For example if you want to make 200 pixels menu width, just add 200 in this field. Do not use it with equal tabs function.">?</a>] </span>
        </p>
		 	
			
		<p>
          <label for="<?php echo $this->get_field_id('menuWidth'); ?>"><?php _e('Menu width:'); ?></label> 
		  <span style="font-size: 10px;">[<a  title="Menu width in pixels for vertical tabs. Example: '200', '400' and so on... ">?</a>] </span>
		  <br/>
          <input class="widefat" id="<?php echo $this->get_field_id('menuWidth'); ?>" name="<?php echo $this->get_field_name('menuWidth'); ?>" type="text" value="<?php echo $menuWidth; ?>" />
        </p>	
			
		
		<p>
          <label for="<?php echo $this->get_field_id('menuHeight'); ?>"><?php _e('Menu height:'); ?></label>
		  <span style="font-size: 10px;">[<a  title="Menu height in pixels for vertical tabs. Example: '200', '400' and so on... ">?</a>] </span>
		  <br/>
          <input class="widefat" id="<?php echo $this->get_field_id('menuHeight'); ?>" name="<?php echo $this->get_field_name('menuHeight'); ?>" type="text" value="<?php echo $menuHeight; ?>" />
        </p>	
		
		
		<span style="color:#afafaf;font-size:10px;"><?php _e('Fixed height support'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />
		
		
		<p>
          <input id="<?php echo $this->get_field_id('fixedHeight'); ?>" name="<?php echo $this->get_field_name('fixedHeight'); ?>" type="checkbox" value="1" <?php checked( '1', $fixedHeight ); ?>/>
          <label for="<?php echo $this->get_field_id('fixedHeight'); ?>"><?php _e('Fixed height'); ?></label> 
		   <span style="font-size: 10px;">[<a  title="If you want to set height to your tabs - just check this checkbox and set height value in pixel below. By default tabs height is dinamically changes, but if you have any issues with this, you may set fixed height to your tabs. You will need to detect tab with the biggest height by yourself. So if you have 2 tabs with 300 pixels height and 500 pixels height - set fixed height value - 500.">?</a>] </span>
        </p>
		
		<p>
          <label for="<?php echo $this->get_field_id('fixedHeightValue'); ?>"><?php _e('Fixed height value:'); ?></label>
		   <span style="font-size: 10px;">[<a  title="Set manually fixed tabs height value in pixels. Example: '500', '650' and so on...">?</a>] </span>		  
          <input class="widefat" id="<?php echo $this->get_field_id('fixedHeightValue'); ?>" name="<?php echo $this->get_field_name('fixedHeightValue'); ?>" type="text" value="<?php echo $fixedHeightValue; ?>" />
        </p>
		
		<span style="color:#afafaf;font-size:10px;"><?php _e('Drop-down list'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />
		<p>
          <input id="<?php echo $this->get_field_id('dropDown'); ?>" name="<?php echo $this->get_field_name('dropDown'); ?>" type="checkbox" value="1" <?php checked( '1', $dropDown ); ?>/>
          <label for="<?php echo $this->get_field_id('dropDown'); ?>"><?php _e('Drop-down list'); ?></label> 
		  			<span style="font-size: 10px;">[<a  title="Creates drop-down list instead of tabs buttons.">?</a>] </span>
        </p>
		<p>
          <input id="<?php echo $this->get_field_id('responsiveDropDown'); ?>" name="<?php echo $this->get_field_name('responsiveDropDown'); ?>" type="checkbox" value="1" <?php checked( '1', $responsiveDropDown ); ?>/>
          <label for="<?php echo $this->get_field_id('responsiveDropDown'); ?>"><?php _e('Add responsive property'); ?></label> 
		  			<span style="font-size: 10px;">[<a  title="Add responsive property to drop-down list. It will be displayed when total buttons' widths will be greater than sidebar width. ('Drop-down list' option should be also checked.)">?</a>] </span>
        </p>

		<span style="color:#afafaf;font-size:10px;"><?php _e('Additional classes'); ?></span> 
		<hr size="1" width="100%" color="dfdfdf" style="margin:0 0 10px 0;" />	
		
		 <p>
          <label for="<?php echo $this->get_field_id('classID'); ?>"><?php _e('Additional class(ses):'); ?></label> 
		  <span style="font-size: 10px;">[<a  title="You may add css classes to tabs structure. We chose this method to give you a change easily add own styles or change something in tabs layout. Just type css classes divided by space.
You may also use predefined styles here. Just add 'h' letter + number from 1 to 15, like 'h1', 'h2' and so on... The meaning is simple - 'h1' = 1 horizontal style, 'h2' = 2 horizontal style. If you are using vertical tabs - use 'v' letter instead of 'h' letter. So, for using predefined styles with vertical tabs, add class like 'v1', 'v2' and so on...">?</a>] </span>
          <input class="widefat" id="<?php echo $this->get_field_id('classID'); ?>" name="<?php echo $this->get_field_name('classID'); ?>" type="text" value="<?php echo $classID; ?>" />
        </p>
		
		<fieldset>
		
        <?php 
    }


} 

add_action('widgets_init', create_function('', 'return register_widget("hat_widget");'));

?>
