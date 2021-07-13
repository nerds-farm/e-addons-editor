<?php

namespace EAddonsEditor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( class_exists( 'Jet_Engine' )
        && class_exists('Jet_Engine_Options_Gallery_Tag')) {
    class Jet_Options_Gallery extends \Jet_Engine_Options_Gallery_Tag {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;
        
        protected function _register_controls() {
            parent::_register_controls();
	}
    }
} else {
    class Jet_Options_Gallery extends Base_Tag {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;
        public $ignore = true;
    }
}

if ( class_exists( 'Jet_Engine' )
        && class_exists('Jet_Engine_Custom_Gallery_Tag')) {
    class Jet_Gallery extends \Jet_Engine_Custom_Gallery_Tag {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;
        
        protected function _register_controls() {
            parent::_register_controls();
	}
    }
} else {
    class Jet_Gallery extends Base_Tag {
        use \EAddonsEditor\Modules\DynamicTags\Traits\Background_Slideshow_Gallery;
        public $ignore = true;
    }
}