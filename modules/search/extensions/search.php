<?php
namespace EAddonsEditor\Modules\Search\Extensions;

use EAddonsForElementor\Base\Base_Extension;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Search extends Base_Extension {
    /*
    public function get_pid() {
        return 403;
    }
    */
    
    public function show_in_settings() {
        return false;
    }
    
    public function get_icon() {
        return 'eadd-editor-control-search';
    }
    
    public function __construct() {
        parent::__construct();
        //add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.7.0
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        wp_enqueue_script('e-addons-editor-search');
        wp_enqueue_style('e-addons-editor-search');
    }

}
