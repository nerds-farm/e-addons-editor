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

class Image extends Base_Tag {
    
    public $is_data = true;
    
    public function get_name() {
        return 'e-tag-term-image';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-term-image';
    }

    public function get_pid() {
        return 7459;
    }

    public function get_title() {
        return __('Term Image', 'e-addons');
    }

    public function get_group() {
        return 'term';
    }
    public static function _group() {
        return self::_groups('term');
    }

    public function get_categories() {
        return [
            //'base', //\Elementor\Modules\DynamicTags\Module::BASE_GROUP
            'image', //\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
        ];
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function register_advanced_section() {
        
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
                'image',
                [
                    'label' => __('Image', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Meta key or Name', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'term',
                ]
        );
        
        $this->add_control(
                'source',
                [
                    'label' => __('Source', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Current', 'e-addons'),                        
                        'parent' => __('Parent', 'e-addons'),
                        'root' => __('Root', 'e-addons'),
                        'post' => __('Post', 'e-addons'),
                        'other' => __('Other', 'e-addons'),                        
                    ],
                    //'label_block' => true,
                ]
        );
        
        $this->add_control(
                'taxonomy',
                [
                    'label' => __('Taxonomy', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Taxonomy', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'taxonomies',
                    'condition' => [
                        'source' => 'post',
                    ]
                ]
        );
        
        $this->add_control(
                'term_id',
                [
                    'label' => __('Term', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select other Term', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'terms',
                    'condition' => [
                        'source' => 'other',
                    ]
                ]
        );

        $this->add_control(
                'fallback_image',
                [
                    'label' => __('Fallback', 'elementor'),
                    'type' => Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
        );

        Utils::add_help_control($this);
    }
    
    public function get_term_id() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        
        $term_id = $this->get_module()->get_term_id();
        
        if ($settings['source']) {  
            if ($settings['source'] == 'other') {
                if ($settings['term_id']) {
                    return $settings['term_id'];
                }
            }
            if ($settings['source'] == 'post') {
                $taxonomy = $settings['taxonomy'] ? $settings['taxonomy'] : 'category';
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                if (!empty($terms)) {
                    $term = reset($terms);
                    return $term->term_id;
                }
            }
            if ($term_id) {
                do {
                    $term = get_term($term_id);
                    $parent_id = $term->parent;
                    if ($settings['source'] == 'parent') {
                        return $parent_id;
                    }
                    if ($parent_id) {
                        $term_id = $parent_id;
                    }
                } while($parent_id);
                return $term_id;
            }
        }
        
        return $term_id;
    }

    public function get_value(array $options = []) {
        $settings = $this->get_settings();
        if (empty($settings))
            return;

        $term_id = $this->get_term_id();

        $id = '';
        $url = '';
        if ($term_id) {
            // custom field
            $meta = Utils::get_term_field($settings['image'], $term_id);
            
            $img = Utils::get_image($meta);
            
            if (!Utils::empty($img) && !empty($img['url'])) {
                if (!empty($img['id'])) {
                    $id = $img['id'];
                }
                $url = $img['url'];
            } else {
                if (!empty($settings['fallback_image']['url'])) {
                    $id = $settings['fallback_image']['id'];
                    $url = $settings['fallback_image']['url'];
                }
            }
        }
        //var_dump($url);
        return [
            'id' => $id,
            'url' => $url,
        ];
    }

}
