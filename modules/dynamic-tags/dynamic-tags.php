<?php

namespace EAddonsEditor\Modules\DynamicTags;

use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Module_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Dynamic_Tags extends Module_Base {

    public function __construct() {
        parent::__construct();
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);

        add_action('elementor/element/parse_css', [$this, 'parse_css'], 10, 2);
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.7.0
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style('e-addons-editor-dynamic-tags');
    }

    /**
     * After element parse CSS.
     *
     * Fires after the CSS of the element is parsed.
     *
     * @since 1.2.0
     *
     * @param Post         $this    The post CSS file.
     * @param Element_Base $element The element.
     */
    public function parse_css($css_post, $element) {
        
        $element_settings = $element->get_settings_for_display();        
        $controls = $css_post->get_style_controls( $element, null, $element->get_parsed_dynamic_settings() );
        //echo '<pre>';var_dump($controls);echo '</pre>'; die();
        $css_post->add_controls_stack_style_rules( $element, $controls, $element_settings, [ '{{ID}}', '{{WRAPPER}}' ], [ $element->get_id(), $css_post->get_element_unique_selector( $element ) ] );

    }

}
