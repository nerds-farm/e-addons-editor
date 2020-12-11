<?php

namespace EAddonsEditor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( function_exists( 'pods' ) 
        && class_exists('\ElementorPro\Modules\DynamicTags\Pods\Tags\Pods_Gallery')) {
    class Pods_Gallery extends \ElementorPro\Modules\DynamicTags\Pods\Tags\Pods_Gallery {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;   
        public function get_title() {
            return __('Pods Gallery Background Slider Fix');
        }
    }
} else {
    class Pods_Gallery extends Base_Tag {        
        public $ignore = true;    
        public function get_title() {
            return __('Pods Gallery Background Slider Fix');
        }
    }
}