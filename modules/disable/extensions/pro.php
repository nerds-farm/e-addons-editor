<?php
namespace EAddonsEditor\Modules\Disable\Extensions;

use EAddonsForElementor\Base\Base_Extension;
use EAddonsForElementor\Core\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Pro extends Base_Extension {
    
    /*
    public function get_pid() {
        return 403;
    }
    */
    public function get_icon() {
        return 'eicon-elementor-circle';
    }
    
    
    public function __construct() {
        parent::__construct();
        if (!Utils::is_plugin_active('elementor-pro')) {
            add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);
        }
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.7.0
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style('e-addons-editor-no-pro');
    }

}
