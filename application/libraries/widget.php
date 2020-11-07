<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (PHP_MAJOR_VERSION >= 7) {
    set_error_handler(function ($errno, $errstr) {
       return strpos($errstr, 'Declaration of') === 0;
    }, E_WARNING);
}
class widget {

	public $key 		= '';

	public $name 		= '';

	public $tags        = array();

	public $left 		= array();

	public $right 		= array();

	public $after 		= '';

	public $before 		= '';

	public $option 		= null;

	public $id 			= 0;

	function __construct($key , $name){
		
		$this->key 		= $key;

		$this->name 	= $name;

		$this->set_after();

        $this->set_before();
	}

	function set_name($name) {
		$this->name = $name;
	}

	function form($left = array(), $right = array()) {

		if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            $left_language = [];

            foreach ($ci->language['language_list'] as $lang_key => $lang_val) {

                if($lang_key == $ci->language['default']) continue;

                $left_language[] = array('field' => 'title_'.$lang_key, 'label' => 'Tiêu đề ('.$lang_val['label'].')', 'type' => 'text');
            }

            foreach ($left as $key => $value) {

                $left_language[] = $value;

                if($value['type'] == 'text' || $value['type'] == 'textarea' || $value['type'] == 'wysiwyg' ) {

                    foreach ($ci->language['language_list'] as $lang_key => $lang_val) {

                        if($lang_key == $ci->language['default']) continue;

                        $left_language[] = array_merge($value, [
                            'field' => $value['field'].'_'.$lang_key, 
                            'label' => $value['label'].' ('.$lang_val['label'].')', 
                        ]);
                    }
                }
            }

            $right_language = [];

            foreach ($right as $key => $value) {

                $right_language[] = $value;

                if($value['type'] == 'text' || $value['type'] == 'textarea' || $value['type'] == 'wysiwyg' ) {

                    foreach ($ci->language['language_list'] as $lang_key => $lang_val) {

                        if($lang_key == $ci->language['default']) continue;

                        $right_language[] = array_merge($value, [
                            'field' => $value['field'].'_'.$lang_key, 
                            'label' => $value['label'].' ('.$lang_val['label'].')', 
                        ]);
                    }
                }
            }

            $left = $left_language;

            $right = $right_language;
        }

		$this->set_left($left);

		$this->set_right($right);
	}

	function set_left($left) {
		$default = array('value' => '',);
		if(have_posts($left)) {
			foreach ($left as $key => $value) {
				$left[$key] = array_merge($default, $value);
			}
			$this->left = $left;
		}
    }

    function set_right($right) {
        $default = array('value' => '',);
		if(have_posts($right)) {
			foreach ($right as $key => $value) {
				$right[$key] = array_merge($default, $value);
			}
			$this->right = $right;
		}
    }

	function set_after($after = '') {
		$this->after = $after;
    }

    function set_before($before = '') {
		$this->before = $before;
    }

	function get_option($option = array()) {

		if(have_posts($this->left)) {
			foreach ($this->left as $key => $value) {
				$this->option[$value['field']] = $value['value'];
			}
		}
		if(have_posts($this->right)) {
			foreach ($this->right as $key => $value) {
				$this->option[$value['field']] = $value['value'];
			}
		}


		if(have_posts($this->option) && have_posts($option)) {
			foreach ($this->option as $key => $value) {
				if(isset($option[$key])) {
					$this->option[$key] = $option[$key];
				}
			}
        }

		return $this->option;
	}

	function get_option_object() {

		return (object)$this->option;
	}

	function widget( $option ) {
		echo "widget hiện chưa có trình hiển thị!";
    }
    
    function widget_none($sidebar_id) {

        if( is_user_logged_in() && is_super_admin() && get_option('cms_widget_builder', 0) == 1 ) {
		?>
        <div class="js_widget_builder_empty" id="js_widget_empty_<?php echo $sidebar_id;?>" data-key="<?php echo $sidebar_id;?>">
            <div class="icon-plus">
                <p><i class="fal fa-plus"></i></p>
                <p>Thêm widget</p>
            </div>
            <div class="clearfix"></div>
        </div>    
        <?php }
    }

	function container_box( $class = '', $option, $id = '' ) {

        //CSS
        $css_inline = '';

        if(!empty($option->bg_color)) {
            $css_inline .= 'background-color:'.$option->bg_color.';';
        }

        //CSS
        if(!empty($option->bg_image)) {
            $css_inline .= 'background:url(\''.get_img_link($option->bg_image).'\');';
            $css_inline .= 'background-size:cover;background-attachment: fixed;background-repeat: no-repeat; background-position: center center;';
        }

        //margin
        if(isset($option->box_size['margin'])) {

            $margin = $option->box_size['margin'];

            foreach ($margin as $position => $value) {

                $unit = 'px';

                if(strpos($value, '%') !== false) {
                    $unit = '%';
                    $value = (int)str_replace('%', '', $value);
                }

                if($value != 0) {

                    $css_inline .= 'margin-'.$position.':'.$value.$unit.';';
                }
            }
        }

        if(isset($option->box_size['padding'])) {

            $padding = $option->box_size['padding'];

            foreach ($padding as $position => $value) {
                
                $unit = 'px';

                if(strpos($value, '%') !== false) {
                    $unit = '%';
                    $value = (int)str_replace('%', '', $value);
                }

                if($value != 0) {

                    $css_inline .= 'padding-'.$position.':'.$value.$unit.';';
                }
            }
        }

        $class_row = '';

        if(isset($option->col_xs)) $class_row .= ($option->col_xs != 0)?'col-xs-'.$option->col_xs:'';

        if(isset($option->col_sm)) $class_row .= " ".(($option->col_sm != 0)?'col-sm-'.$option->col_sm:'');

        if(isset($option->col_md)) $class_row .= " ".(($option->col_md != 0)?'col-md-'.$option->col_md:'');

        //LAYOUT
        $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css_inline.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';

        $after  = '</div>';

        if(isset($option->box)) {

            if($option->box == 'container') {

                $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css_inline.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';

                $before .= '<div class="container">';

                $after = '</div>';

                $after .= '</div>';
            }

            if($option->box == 'in-container') {

                $before = '<div class="container js_widget_builder js_'.$this->key.'_'.$this->id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';

                $before .= '<div class="'.$class.'" style="'.$css_inline.'">';

                $after = '</div>';

                $after .= '</div>';
            }
        }

        if($class_row != '') {

            $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.' '.$class_row.'" style="'.$css_inline.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';

            $after  = '</div>';
        }

        return array( 'before' => $before, 'after' => $after );
    }

    function update( $new_instance, $old_instance ) {

        return $new_instance;
    }
}