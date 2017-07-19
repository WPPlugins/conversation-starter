<?php
	/*
		Class Definition
	*/
	if (!class_exists('PluginCore')) {
		class PluginCore {
			
			var $pluginname	="Default Plugin Name";
			var $pluginurl	="http://www.example.com/";
			var $forumurl	="http://www.example.com/forum";
			var $shortname	="my_plugin";
			var $options	=array();
			
			/* PHP4 Compatible Constructor */
			function PluginCore () {
				add_action( 'init',			array( &$this, 'printAdminScripts' ) );
				add_action( 'admin_menu',	array( &$this, 'addAdminPage' ) );
			}
			
			/* Add Custom CSS & JS */
			function printAdminScripts () {
				if ( $_GET['page'] == basename(__FILE__) ) {
					wp_enqueue_style($this->shortname.'_admin_css', WP_PLUGIN_URL.'/'.$this->shortname.'/stylesheets/admin.css');
					wp_enqueue_style('farbtastic');
					wp_enqueue_script($this->shortname.'_admin_js', WP_PLUGIN_URL.'/'.$this->shortname.'/javascripts/admin.js', array('jquery') );
					wp_enqueue_script('farbtastic');
				}
				if (!is_admin()) :
					wp_enqueue_style($this->shortname.'-public', get_option('home').'/index.php?'.$this->shortname.'=frontend_css');
					wp_enqueue_script('jquery');
					// wp_enqueue_script($this->shortname.'-public', get_option('home').'/index.php?'.$this->shortname.'=frontend_js&convo-id='.$post->ID, array('jquery'));
				endif;
			}
			
			function activateMe () {
				foreach ( $this->options as $option )
					update_option( $option['id'], $option['std'] );
			}
			
			/* Process Input and Add Options Page*/
			function addAdminPage() {
				if ( $_GET['page'] == basename(__FILE__) ) {
					if ( 'save' == $_REQUEST['action'] ) {
						// Nonce Magic! - check for sex offenders & pedophiles
						check_admin_referer('conversation-starter-update-options_styling');
						foreach ($this->options as $value) {
							update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
						}
						foreach ($this->options as $value) {
							if ( isset( $_REQUEST[ $value['id'] ] ) ) { 
								update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
							} else { 
								delete_option( $value['id'] ); 
							}
						}		
						header("Location: options-general.php?page=".basename(__FILE__)."&saved=true");
						die;
					} else if( 'reset' == $_REQUEST['action'] ) {
						// Nonce Magic! - check for sex offenders & pedophiles
						check_admin_referer('conversation-starter-reset-options_styling');
						foreach ($this->options as $value) {
							delete_option( $value['id'] ); 
						}
						header("Location: options-general.php?page=".basename(__FILE__)."&reset=true");
						die;
					}
				}
				add_options_page($this->pluginname." Options", $this->pluginname." Options", 'manage_options', basename(__FILE__), array(&$this, 'adminPage'));
			}
			
			/* Output of the Admin Page */
			function adminPage () {
				if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>' . $this->pluginname . __(' settings saved!', $this->shortname) . '</strong></p></div>';
				if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>' . $this->pluginname . __(' settings reset.', $this->shortname) . '</strong></p></div>'; ?>
				
<div id="p-options">
	<div id="vop-header">
		<h1><?php echo $this->pluginname; ?> <?php _e('Options', $this->shortname) ?></h1>
		<p><strong><?php _e( 'Need help with these options?', $this->shortname)?></strong> <a href="<?php echo $this->pluginurl; ?>" target="_blank"><?php _e( 'Read the tutorials' , $this->shortname)?></a> <?php _e('or visit the <a href="'.$this->forumurl.'" target="_blank">support forums.</a>', $this->shortname)?></p>
	</div><!--end vop-header-->
	<div id="vop-body">
		<form method="post">
		<?php
		// Nonce Magic! - register sex offenders & pedophiles
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('conversation-starter-update-options_styling');
		?>
<?php
				for ($i = 0; $i < count($this->options); $i++) :
					switch ($this->options[$i]["type"]) :
						
						case "subhead":
						 	if ($i != 0) { ?>
		<div class="p-save-button submit">
			<input type="hidden" name="action" value="save" />
			<input class="button-primary" type="submit" value="<?php _e('Save changes', $this->shortname); ?>" name="save"/>
		</div><!--end p-save-button-->
	</div>
</div><!--end p-option--><?php } ?>
<div class="p-option">
	<h3><?php echo $this->options[$i]["name"]; ?></h3>
	<div class="p-option-body clear">
		<?php $notice = $this->options[$i]["notice"]; ?>
		<?php if($notice != '') { ?>
		 	<p class="notice"><?php echo $notice; ?></p>
		<?php } ?>
						<?php
							break;
							
							
					case "checkbox":
						?>
		<div class="p-field check clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="checkbox" name="<?php echo $this->options[$i]["id"]; ?>" value="true"<?php echo (get_settings($this->options[$i]['id'])) ? ' checked="checked"' : ''; ?> />
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
		</div><!--end p-field check-->
						<?php
							break;
				
							
						case "radio":
							?>
		<div class="p-field radio clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
			 	<?php
				$radio_setting = get_settings($this->options[$i]['id']);
				$checked = "";
				foreach ($this->options[$i]['options'] as $key => $val) :
					if($radio_setting != '' &&  $key == get_settings($this->options[$i]['id']) ) {
						$checked = ' checked="checked"';
					} else {
						if($key == $this->options[$i]['std']){
							$checked = 'checked="checked"';
						}
					}
					?>
		    <input type="radio" name="<?php echo $this->options[$i]['id']; ?>" value="<?php echo $key; ?>"<?php echo $checked; ?> /><?php echo $val; ?><br />
				<?php endforeach; ?>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
		</div><!--end p-field radio-->
						<?php
							break;
						
						case "text":
							?>
		<div class="p-field text clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="text" name="<?php echo $this->options[$i]["id"]; ?>" value="<?php esc_attr_e((get_settings($this->options[$i]["id"]) != "") ? get_settings($this->options[$i]["id"]) : $this->options[$i]["std"]); ?>" />
		</div><!--end p-field text-->
						<?php
							break;
						
						case "colorpicker":
							?>
		<div class="p-field colorpicker clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]; ?> </div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?> <a href="javascript:return false;" onclick="toggleColorpicker (this, '<?php echo $this->options[$i]["id"]; ?>', 'open', '<?php _e('show color picker', $this->shortname); ?>', '<?php _e('hide color picker', $this->shortname); ?>')"><?php _e('show color picker', $this->shortname); ?></a></label>
			<div id="<?php echo $this->options[$i]["id"]; ?>_colorpicker" class="colorpicker_container"></div>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="text" name="<?php echo $this->options[$i]["id"]; ?>" value="<?php esc_attr_e((get_settings($this->options[$i]["id"]) != "") ? get_settings($this->options[$i]["id"]) : $this->options[$i]["std"]); ?>" />
		</div><!--end p-field colorpicker-->
						<?php
							break;
						
						case "select":
							?>
		<div class="p-field select clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]?></div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
			<select id="<?php echo $this->options[$i]["id"]; ?>" name="<?php echo $this->options[$i]["id"]; ?>">
				<?php
					foreach ($this->options[$i]["options"] as $key => $val) :
						if (get_settings($this->options[$i]["id"]) == "" || is_null(get_settings($this->options[$i]["id"]))) : ?>
					<option value="<?php echo $key; ?>"<?php echo ($key == $this->options[$i]['std']) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
						<?php else : ?>
					<option value="<?php echo $key; ?>"<?php echo get_settings($this->options[$i]["id"]) == $key ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
					<?php
						endif;
					endforeach;
				?>
			</select>
		</div><!--end p-field select-->
						<?php
							break;
						
						case "textarea":
							?>
		<div class="p-field textarea clear">
			<div class="p-field-d"><?php echo $this->options[$i]["desc"]?></div>
			<label for="<?php echo $this->options[$i]["id"]?>"><?php echo $this->options[$i]["name"]?></label>
			<textarea id="<?php echo $this->options[$i]["id"]?>" name="<?php echo $this->options[$i]["id"]?>"<?php echo ($this->options[$i]["options"] ? ' rows="'.$this->options[$i]["options"]["rows"].'" cols="'.$this->options[$i]["options"]["cols"].'"' : ""); ?>><?php 
				esc_html_e (( get_settings($this->options[$i]['id']) != "") ? get_settings($this->options[$i]['id']) : $this->options[$i]['std']);
			?></textarea>
		</div><!--end vop-p-field textarea-->
						<?php
							break;
						
					endswitch;
				endfor;
			?>
					<div class="p-save-button submit">
						<input type="submit" value="<?php _e('Save changes', $this->shortname) ?>" name="save"/>
					</div><!--end p-save-button-->
				</div>
			</div>
			<div class="p-saveall-button submit">
				<input class="button-primary" type="submit" value="<?php _e('Save all changes', $this->shortname) ?>" name="save"/>
			</div>
			</form>
			<div class="p-reset-button submit">
				<form method="post">
				<?php
				// Nonce Magic! - register sex offenders & pedophiles
				if ( function_exists('wp_nonce_field') )
					wp_nonce_field('conversation-starter-reset-options_styling');
				?>
					<input type="hidden" name="action" value="reset" />
					<input class="p-reset" type="submit" value="<?php _e('Reset all options', $this->shortname) ?>" name="reset"/>
				</form>
			</div>

			<script type="text/javascript">
			<?php
				for ($i = 0; $i < count($this->options); $i++) :
					if ($this->options[$i]['type'] == 'colorpicker') :
			?>
					jQuery("#<?php echo $this->options[$i]["id"]; ?>_colorpicker").farbtastic("#<?php echo $this->options[$i]["id"]; ?>");
			<?php
					endif;
				endfor;
			?>
				jQuery('.colorpicker_container').hide();
			</script>
	</div><!--end vop-body-->
</div><!--end p-options-->

			<?php
			}
		}
	}

?>