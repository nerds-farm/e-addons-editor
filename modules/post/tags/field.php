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

class Field extends Base_Tag {

    public function get_name() {
        return 'e-tag-post-field';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-post-field';
    }

    public function get_pid() {
        return 7461;
    }

    public function get_title() {
        return __('Post Field', 'e-addons');
    }

    public function get_group() {
        return 'post';
    }
    public static function _group() {
        return self::_groups('post');
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
    protected function _register_controls() {

        $this->add_control(
                'tag_field',
                [
                    'label' => __('Field', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Custom', 'e-addons'),
                        'ID' => __('ID', 'e-addons'),
                        'post_title' => __('Title', 'e-addons'),
                        'post_content' => __('Content', 'e-addons'),                        
                        'post_content_filtered' => __('Content Filtered', 'e-addons'),
                        'post_excerpt' => __('Excerpt', 'e-addons'),                        
                        'post_name' => __('Name', 'e-addons'),
                        'permalink' => __('Permalink', 'e-addons'),
                        //'post_author' => __('Author ID', 'e-addons'),
                        //'post_author_name' => __('Author Name', 'e-addons'),
                        'post_date' => __('Creation Date', 'e-addons'),
                        'post_date_gmt' => __('Creation Date GMT', 'e-addons'),
                        'post_modified' => __('Modified Date', 'e-addons'),
                        'post_modified_gmt' => __('Modified Date GMT', 'e-addons'),
                        'post_status' => __('Status', 'e-addons'),
                        'comment_status' => __('Comment Status', 'e-addons'),
                        'comment_count' => __('Comment Count', 'e-addons'),
                        //'ping_status' => __('Ping Status', 'e-addons'),
                        'post_password' => __('Password', 'e-addons'),                        
                        //'to_ping' => __('To Ping', 'e-addons'),
                        //'pinged	text' => __('Pinged Text', 'e-addons'),                        
                        //'post_parent' => __('Parent', 'e-addons'),
                        //'post_parent_name' => __('Parent Name', 'e-addons'),
                        'guid' => __('Guid', 'e-addons'),
                        'menu_order' => __('Menu Order', 'e-addons'),
                        //'post_type' => __('Comment Count', 'e-addons'),
                        'post_mime_type' => __('Mime Type', 'e-addons'),                        
                    ],
                    //'label_block' => true,
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
                    'object_type' => 'post',
                    'condition' => [
                        'tag_field' => '',
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

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $post_id = $this->get_post_id();

        if ($post_id) {
            if (!empty($settings['tag_field'])) {
                $field = $settings['tag_field'];
                if ($field == 'permalink') {
                    $meta = get_permalink($post_id);
                } else {
                    $meta = get_post_field($field, $post_id);
                }
            } else {
                $field = $settings['custom'];
                $meta = Utils::get_post_field($field, $post_id);
            }

            echo Utils::to_string($meta);
        }
    }

}
