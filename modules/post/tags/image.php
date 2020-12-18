<?php

namespace EAddonsEditor\Modules\Post\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Image extends Base_Tag {
    
    public $data = true;
    
    public function get_name() {
        return 'e-tag-post-image';
    }
    
    public function get_icon() {
        return 'eadd-dynamic-tag-post-image';
    }

    public function get_pid() {
        return 7461;
    }

    public function get_title() {
        return __('Post Image', 'e-addons');
    }

    public function get_group() {
        return 'post';
    }
    public static function _group() {
        return self::_groups('post');
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
    protected function _register_controls() {

        $this->add_control(
                'featured',
                [
                    'label' => __('Featured Image', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
        );
        $this->add_control(
                'image',
                [
                    'label' => __('Custom Image', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Meta key or Name', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'post',
                    'condition' => [
                        'featured' => '',
                    ]
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
                        'previous' => __('Previous', 'e-addons'),
                        'next' => __('Next', 'e-addons'),
                        'other' => __('Other', 'e-addons'),
                    ],
                    //'label_block' => true,
                ]
        );
        
        $this->add_control(
                'post_id',
                [
                    'label' => __('Post', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select other Post', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
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
    
    public function get_post_id() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        
        $post_id = get_the_ID();
        
        if ($settings['source']) {        
            switch($settings['source']) {
                
                case 'previous':
                    $prev = get_adjacent_post();
                    if ($prev && is_object($prev) && get_class($prev) == 'WP_Post') {
                        return $prev->ID;
                    }
                    break;

                case 'next':
                    $next = get_adjacent_post(false, '', false);
                    if ($next && is_object($next) && get_class($next) == 'WP_Post') {                        
                        return $next->ID;
                    }
                    break;

                case 'other':
                    if ($settings['post_id']) {
                        return $settings['post_id'];
                    }
                    break;

                default:
                    if ($post_id) {
                        do {
                            $parent_id = wp_get_post_parent_id($post_id);
                            if ($settings['source'] == 'parent') {
                                return $parent_id;
                            }
                            if ($parent_id) {
                                $post_id = $parent_id;
                            }
                        } while($parent_id);
                        return $post_id;
                    }
            }
        }
        
        return $post_id;
    }

    public function get_value(array $options = []) {
        $settings = $this->get_settings();
        if (empty($settings))
            return;

        $post_id = $this->get_post_id();

        $id = '';
        $url = '';
        if ($post_id) {
            // custom field
            if ($settings['featured']) {
                $meta = get_post_thumbnail_id($post_id);
            } else {
                $meta = Utils::get_term_field($settings['image'], $post_id);
            }
            
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
