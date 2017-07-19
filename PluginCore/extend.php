<?php
	/* REQUIRE THE CORE CLASS */
	require_once ('core.php');
	/*
		Class Definition
	*/
	if (!class_exists('ConversationStarter')) {
		class ConversationStarter extends PluginCore {
			
			/* PHP4 Constructor */
			function ConversationStarter () {
				
				/* SET UP THEME SPECIFIC VARIABLES */
			
				$this->pluginname = "Conversations";
				$this->pluginurl = "http://www.think-press.com/plugins/conversation-manager";
				$this->forumurl = "http://www.think-press.com/conversation-manager-forum";
				$this->shortname = "conversation-starter";
				/*
					OPTION TYPES:
					- checkbox: name, id, desc, std, type
					- radio: name, id, desc, std, type, options
					- text: name, id, desc, std, type
					- colorpicker: name, id, desc, std, type
					- select: name, id, desc, std, type, options
					- textarea: name, id, desc, std, type, options
				*/
				$this->options = array(

					array(	"name" => __('Default Prompt Text <span>set up your default prompt</span>', $this->shortname),
									"type" => "subhead"),

						array(	"name" => __('Default Text', $this->shortname),
										"id" => $this->shortname."_default_prompt_text",
										"desc" => __('It\'s important to customize the prompt text on a post-by-post basis for this plugin to be most effective. This field will become the generic, default text if you choose not to customize the text on posts.', $this->shortname),
										"std" => "What do you think about this post?",
										"type" => "text"),
										
					array(	"name" => __('Color Scheme <span>customize your color scheme</span>', $this->shortname),
									"type" => "subhead"),

						array(	"name" => __('Background color', $this->shortname),
										"id" => $this->shortname."_background_color",
										"desc" => __('Use hex values and be sure to include the leading #.', $this->shortname),
										"std" => "#efefef",
										"type" => "colorpicker"),

					array(	"name" => __('Border Options <span>choose border styles</span>', $this->shortname),
									"type" => "subhead"),

						array(	"name" => __('Show Border', $this->shortname),
										"id" => $this->shortname."_show_border",
										"desc" => __('If enabled, a border will appear around your prompt.', $this->shortname),	
										"std" => "enabled",
										"type" => "select",
										"options" => array( "enabled"  =>  __('Enabled', $this->shortname),
															"disabled" => __('Disabled', $this->shortname))),

						array(	"name" => __('Border Type', $this->shortname),
										"id" => $this->shortname."_border_type",
										"desc" => __('Choose from the following border types: ', $this->shortname),
										"std" => "solid",
										"type" => "select",
										"options" => array(	"solid" => __('Solid', $this->shortname), 
															"dotted" => __('Dotted', $this->shortname), 
															"dashed" => __('Dashed', $this->shortname))),

						array(	"name" => __('Border color', $this->shortname),
										"id" => $this->shortname."_border_color",
										"desc" => __('Use hex values and be sure to include the leading #.', $this->shortname),
										"std" => "#cccccc",
										"type" => "colorpicker"),

						array(	"name" => __('Border Width', $this->shortname),
										"id" => $this->shortname."_border_width",
										"desc" => __('The width of your border (in pixels).', $this->shortname),
										"std" => "1",
										"type" => "text"),
									
					array(	"name" => __('Margin/Padding Options <span>set spacing to match your design</span>', $this->shortname),
									"type" => "subhead"),

						array(	"name" => __('Message Padding', $this->shortname),
										"id" => $this->shortname."_div_padding",
										"desc" => __('The padding (inside the box) of your message (in pixels).', $this->shortname),
										"std" => "10",
										"type" => "text"),
									
						array(	"name" => __('Message Margin', $this->shortname),
										"id" => $this->shortname."_div_margin",
										"desc" => __('The margin (outside the box) of your message (in pixels).', $this->shortname),
										"std" => "0",
										"type" => "text"),
									
					array(	"name" => __('Font Options <span>choose font styles</span>', $this->shortname),
									"type" => "subhead"),
									
						array(	"name" => __('Font Color', $this->shortname),
										"id" => $this->shortname."_font_color",
										"desc" => __('Use hex values and be sure to include the leading #.', $this->shortname),
										"std" => "#ffffff",
										"type" => "colorpicker"),
									
						array(	"name" => __('Font Weight', $this->shortname),
										"id" => $this->shortname."_font_weight",
										"desc" => __('If you want your text to be bold, you can set that here.', $this->shortname),	
										"std" => "normal",
										"type" => "select",
										"options" => array( "normal"  =>  __('Normal', $this->shortname),
															"bold" => __('Bold', $this->shortname))),
				);
				parent::PluginCore();
			}
			
			/*
				ALL OF THE FUNCTIONS BELOW
				ARE BASED ON THE OPTIONS ABOVE
				EVERY OPTION SHOULD HAVE A FUNCTION
				
				THESE FUNCTIONS CURRENTLY JUST
				RETURN THE OPTION, BUT COULD BE
				REWRITTEN TO RETURN DIFFERENT DATA
			*/
			
					
			function padding () {
				return array(
					'top' => esc_html__(get_option($this->shortname.'_div_padding')),
					'right' => esc_html__(get_option($this->shortname.'_div_padding')),
					'bottom' => esc_html__(get_option($this->shortname.'_div_padding')),
					'left' => esc_html__(get_option($this->shortname.'_div_padding')),
				);
			}
			function margin () {
				return array(
					'top' => esc_html__(get_option($this->shortname.'_div_margin')),
					'right' => esc_html__(get_option($this->shortname.'_div_margin')),
					'bottom' => esc_html__(get_option($this->shortname.'_div_margin')),
					'left' => esc_html__(get_option($this->shortname.'_div_margin')),
				);
			}
			function showBorder () {
				if (get_option($this->shortname.'_show_border') == 'enabled') :
					return true;
				else :
					return false;
				endif;
			}
			function borderColor () {
				return esc_html__(get_option($this->shortname.'_border_color'));
			}
			function borderType () {
				return esc_html__(get_option($this->shortname.'_border_type'));
			}
			function borderWidth () {
				return esc_html__(get_option($this->shortname.'_border_width'));
			}
			function backgroundColor () {
				return esc_html__(get_option($this->shortname.'_background_color'));
			}
			function fontColor() {
				return esc_html__(get_option($this->shortname.'_font_color'));
			}
			function fontWeight() {
				return esc_html__(get_option($this->shortname.'_font_weight'));
			}
			function defaultPromptText() {
				return esc_html__(get_option($this->shortname.'_default_prompt_text'));
			}
		}
	}
	/* SETTING EVERYTHING IN MOTION */
	if (class_exists('ConversationStarter')) {
		$convo_starter = new ConversationStarter();
	}

?>