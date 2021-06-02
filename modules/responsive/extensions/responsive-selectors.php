<?php

namespace EAddonsEditor\Modules\Responsive\Extensions;

use EAddonsForElementor\Core\Utils;
use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Responsive_Selectors extends Base_Tag {
    
    /**
    * Responsive 'desktop' device name.
    */
    const RESPONSIVE_DESKTOP = 'desktop';

    /**
    * Responsive 'tablet' device name.
    */
    const RESPONSIVE_TABLET = 'tablet';

    /**
    * Responsive 'mobile' device name.
    */
    const RESPONSIVE_MOBILE = 'mobile';

    /*
    public function get_pid() {
        return 1302;
    }
    
    public function get_icon() {
        return 'eadd-enhanced-dynamic-tags';
    }
    */

    public static $types = [
        Controls_Manager::DIMENSIONS,
        Controls_Manager::COLOR,
        Controls_Manager::SLIDER,
        Controls_Manager::NUMBER,
    ];

    public function __construct() {
        parent::__construct();
        $this->add_actions();
    }

    public function add_actions() {
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);
        add_action( 'elementor/element/before_section_end', [$this, 'before_section_end'], 99, 3 );
    }

    public function get_name() {
        return 'e-responsive-selectors';
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.7.0
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style('e-addons-editor-no-loading');
    }
    
    public function before_section_end($controls_stack, $section_id, $args) {        
        $controls = $controls_stack->get_controls();        
        foreach ($controls as $ckey => $control) {
            if (!empty($control['name'])) {
                if (in_array($control['type'], self::$types)) {     
                    if (empty($control['responsive'])) {
                        if (!empty($control['selectors']) || !empty($control['selector'])) {   
                            $options = array('position' => array('type' => 'control', 'at' => 'after', 'of' => $control['name']));
                            if (empty($control['classes'])) {
                                $classes = 'elementor-control-responsive-'.self::RESPONSIVE_DESKTOP; 
                            } else {
                                $classes = $control['classes'] .' elementor-control-responsive-'.self::RESPONSIVE_DESKTOP; 
                            }
                            
                            $controls_stack->update_control($control['name'], array('classes' => $classes));
                            $this->add_extra_responsive_control($control['name'], $control, $options, $controls_stack);                              
                        }
                    }
                }
            }
        }
    }
    
    /**
	 * Add new responsive control to stack.
	 *
	 * Register a set of controls to allow editing based on user screen size.
	 * This method registers three screen sizes: Desktop, Tablet and Mobile.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param string $id      Responsive control ID.
	 * @param array  $args    Responsive control arguments.
	 * @param array  $options Optional. Responsive control options. Default is
	 *                        an empty array.
	 */
	public function add_extra_responsive_control( $id, array $args, $options = [], $controls_stack ) {
		unset($args['name']);
                unset($args['tab']);
                unset($args['section']);
                $args['responsive'] = [];

		$devices = [
			self::RESPONSIVE_DESKTOP,
			self::RESPONSIVE_TABLET,
			self::RESPONSIVE_MOBILE,
		];

		if ( isset( $args['devices'] ) ) {
			$devices = array_intersect( $devices, $args['devices'] );

			$args['responsive']['devices'] = $devices;

			unset( $args['devices'] );
		}

		if ( isset( $args['default'] ) ) {
			$args['desktop_default'] = $args['default'];

			unset( $args['default'] );
		}

		foreach ( $devices as $device_name ) {
			$control_args = $args;

			if ( isset( $control_args['device_args'] ) ) {
				if ( ! empty( $control_args['device_args'][ $device_name ] ) ) {
					$control_args = array_merge( $control_args, $control_args['device_args'][ $device_name ] );
				}

				unset( $control_args['device_args'] );
			}

			if ( ! empty( $args['prefix_class'] ) ) {
				$device_to_replace = self::RESPONSIVE_DESKTOP === $device_name ? '' : '-' . $device_name;

				$control_args['prefix_class'] = sprintf( $args['prefix_class'], $device_to_replace );
			}

			$control_args['responsive']['max'] = $device_name;

			if ( isset( $control_args['min_affected_device'] ) ) {
				if ( ! empty( $control_args['min_affected_device'][ $device_name ] ) ) {
					$control_args['responsive']['min'] = $control_args['min_affected_device'][ $device_name ];
				}

				unset( $control_args['min_affected_device'] );
			}

			if ( isset( $control_args[ $device_name . '_default' ] ) ) {
				$control_args['default'] = $control_args[ $device_name . '_default' ];
			}

			unset( $control_args['desktop_default'] );
			unset( $control_args['tablet_default'] );
			unset( $control_args['mobile_default'] );

			$id_suffix = self::RESPONSIVE_DESKTOP === $device_name ? '' : '_' . $device_name;
                        
			if ( ! empty( $options['overwrite'] ) || self::RESPONSIVE_DESKTOP === $device_name ) {
				$controls_stack->update_control( $id . $id_suffix, $control_args, [
					'recursive' => ! empty( $options['recursive'] ),
				] );
			} else {
                            if (empty($controls_stack->get_controls( $id . $id_suffix))) {
				$controls_stack->add_control( $id . $id_suffix, $control_args, $options );
                            }
			}
		}
	}

}
