<?php

namespace EAddonsEditor\Modules\Term\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Field extends Base_Tag {
    
    use \EAddonsEditor\Modules\Term\Traits\Terms;

    public function get_name() {
        return 'e-tag-term-field';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-term-field';
    }

    public function get_pid() {
        return 7459;
    }

    public function get_title() {
        return __('Term Field', 'e-addons');
    }

    public function get_group() {
        return 'term';
    }
    public static function _group() {
        return self::_groups('term');
    }

    public function get_categories() {
        return [
            'base', //\Elementor\Modules\DynamicTags\Module::BASE_GROUP
            'text', //\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            'url', //\Elementor\Modules\DynamicTags\Module::URL_CATEGORY
        ];
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function register_controls() {

        $this->add_control(
                'tag_field',
                [
                    'label' => __('Field', 'elementor'),
                    'type' => Controls_Manager::SELECT,                    
                    'groups' => [
                        [
                            'label' => __('Custom', 'e-addons'),
                            'options' => [
                                '' => __('Custom', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Common', 'e-addons'),
                            'options' => [                                
                                'name' => __('Name', 'e-addons'),
                                "description" => __('Description', 'e-addons'),                        
                                "count" => __('Count', 'e-addons'),                                
                            ],
                        ],
                        [
                            'label' => __('Link', 'e-addons'),
                            'options' => [
                                "link" => __('Link (to Posts Archive)', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Other', 'e-addons'),
                            'options' => [                                
                                'term_id' => __('ID', 'e-addons'),
                                "parent" => __('Parent ID', 'e-addons'),
                                'slug' => __('Slug', 'e-addons'),
                                "filter" => __('Filter', 'e-addons'),
                                'term_group' => __('Term Group', 'e-addons'),
                            ],
                        ],                       
                    ],
                    //'options' => [],
                ]
        );

        $this->add_control(
                'custom',
                [
                    'label' => __('Custom Meta', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Meta key or Field Name', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'term',
                    'condition' => [
                        'tag_field' => '',
                    ]
                ]
        );
        
        $this->add_source_controls();

        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $term_id = $this->get_term_id();        

        if ($term_id) {
            if (!empty($settings['tag_field'])) {
                $field = $settings['tag_field'];
                if ($field == 'link') {
                    //$meta = Utils::get_term_url($term_id);
                    $meta = get_term_link($term_id);
                    if (is_wp_error($meta) || !filter_var($meta, FILTER_VALIDATE_URL)) {
                        $meta = ''; // Empty Term.                        
                    }
                } else {
                    $meta = get_term_field($field, $term_id);
                }
                if ($field == 'parent') {
                    if ($meta == 0) {
                        $meta = '';
                    }
                }
            } else {
                $field = $settings['custom'];
                $meta = Utils::get_term_field($field, $term_id);
            }
            
            echo Utils::to_string($meta);
        }
    }

}
