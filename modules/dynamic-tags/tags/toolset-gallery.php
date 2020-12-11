<?php

namespace EAddonsEditor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( function_exists( 'wpcf_admin_fields_get_groups' )
        && class_exists('\ElementorPro\Modules\DynamicTags\Toolset\Tags\Toolset_Gallery')) {
    class Toolset_Gallery extends \ElementorPro\Modules\DynamicTags\Toolset\Tags\Toolset_Gallery {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;        
        public function get_title() {
            return __('Toolset Gallery Background Slider Fix');
        }
    }
} else {
    class Toolset_Gallery extends Base_Tag {        
        public $ignore = true;        
        public function get_title() {
            return __('Toolset Gallery Background Slider Fix');
        }
    }
}