<?php

namespace EAddonsEditor\Modules\DynamicTags\Controls\Groups;

use \Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor date/time control.
 *
 * A base control for creating date time control. Displays a date/time picker
 * based on the Flatpickr library @see https://chmln.github.io/flatpickr/ .
 *
 * @since 1.0.0
 */
class Group_Border_Image extends \Elementor\Group_Control_Border {

    use \EAddonsForElementor\Base\Traits\Base;

    /*
      public function get_icon() {
      return 'eadd-dynamic-tag-datetime';
      }

      public function get_pid() {
      return 7750; // 1302;
      }
     */

    /**
     * Init fields.
     *
     * Initialize border control fields.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Control fields.
     */
    protected function init_fields() {
        $fields = parent::init_fields();


        $color = $fields['color'];

        unset($fields['color']);

        $fields['border']['options']['image'] = _x('Image', 'Border Control', 'elementor');
        $fields['border']['options']['gradient'] = _x('Gradient', 'Border Control', 'elementor');

        $fields['width']['selectors']['{{SELECTOR}}'] .= 'border-image-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
        //$fields['width']['selectors']['{{SELECTOR}}'] .= 'border-image-outset: calc({{TOP}}{{UNIT}}/2) calc({{RIGHT}}{{UNIT}}/2) calc({{BOTTOM}}{{UNIT}}/2) calc({{LEFT}}{{UNIT}}/2);';
        //$fields['width']['selectors']['{{SELECTOR}}'] .= 'border-image-outset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';

        $fields['image'] = [
            'label' => _x('Image', 'Border Control', 'elementor'),
            'type' => Controls_Manager::MEDIA,
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-source: url({{URL}});',
            ],
            'condition' => [
                'border' => 'image',
            ],
        ];

        $color['condition']['border!'] = ['', 'image'];
        $fields['color'] = $color;

        $fields['color_stop'] = [
            'label' => _x('Location', 'Background Control', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'render_type' => 'ui',
            'condition' => [
                'border' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b'] = [
            'label' => _x('Second Color', 'Background Control', 'elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#f2295b',
            'render_type' => 'ui',
            'condition' => [
                'border' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b_stop'] = [
            'label' => _x('Location', 'Background Control', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'default' => [
                'unit' => '%',
                'size' => 100,
            ],
            'render_type' => 'ui',
            'condition' => [
                'border' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_type'] = [
            'label' => _x('Type', 'Background Control', 'elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'linear' => _x('Linear', 'Background Control', 'elementor'),
                'radial' => _x('Radial', 'Background Control', 'elementor'),
            ],
            'default' => 'linear',
            'render_type' => 'ui',
            'condition' => [
                'border' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_angle'] = [
            'label' => _x('Angle', 'Background Control', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'default' => [
                'unit' => 'deg',
                'size' => 180,
            ],
            'range' => [
                'deg' => [
                    'step' => 10,
                ],
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'border' => ['gradient'],
                'gradient_type' => 'linear',
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_position'] = [
            'label' => _x('Position', 'Background Control', 'elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'center center' => _x('Center Center', 'Background Control', 'elementor'),
                'center left' => _x('Center Left', 'Background Control', 'elementor'),
                'center right' => _x('Center Right', 'Background Control', 'elementor'),
                'top center' => _x('Top Center', 'Background Control', 'elementor'),
                'top left' => _x('Top Left', 'Background Control', 'elementor'),
                'top right' => _x('Top Right', 'Background Control', 'elementor'),
                'bottom center' => _x('Bottom Center', 'Background Control', 'elementor'),
                'bottom left' => _x('Bottom Left', 'Background Control', 'elementor'),
                'bottom right' => _x('Bottom Right', 'Background Control', 'elementor'),
            ],
            'default' => 'center center',
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-source: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'border' => ['gradient'],
                'gradient_type' => 'radial',
            ],
            'of_type' => 'gradient',
        ];



        $fields['repeat'] = [
            'label' => _x('Repeat', 'Border Control', 'elementor'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '' => _x('Default', 'Border Control', 'elementor'),
                'stretch' => _x('Stretch', 'Border Control', 'elementor'),
                'repeat' => _x('Repeat', 'Border Control', 'elementor'),
                'round' => _x('Round', 'Border Control', 'elementor'),
                'space' => _x('Space', 'Border Control', 'elementor'),
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-repeat: {{VALUE}};',
            ],
            'condition' => [
                'border' => ['image', 'gradient'],
            ],
        ];
        $fields['slice'] = [
            'label' => _x('Slice', 'Border Control', 'elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '1',
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-slice: {{VALUE}};',
            ],
            'condition' => [
                'border' => ['image', 'gradient'],
            ],
        ];

        $fields['outset'] = [
            'label' => _x('Outset', 'Border Control', 'elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{SELECTOR}}' => 'border-image-outset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'border' => ['image', 'gradient'],
            ],
            'responsive' => true,
        ];

        return $fields;
    }

}
