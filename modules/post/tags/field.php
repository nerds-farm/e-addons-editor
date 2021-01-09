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
    
    use \EAddonsEditor\Modules\Post\Traits\Posts;

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
                                'post_title' => __('Title', 'e-addons'),
                                'post_content' => __('Content', 'e-addons'),
                                'post_excerpt' => __('Excerpt', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Link', 'e-addons'),
                            'options' => [
                                'permalink' => __('Permalink', 'e-addons'),
                                'guid' => __('Guid', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Date', 'e-addons'),
                            'options' => [
                                'post_date' => __('Creation Date', 'e-addons'),
                                'post_date_gmt' => __('Creation Date GMT', 'e-addons'),
                                'post_modified' => __('Modified Date', 'e-addons'),
                                'post_modified_gmt' => __('Modified Date GMT', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Comment', 'e-addons'),
                            'options' => [
                                'comment_status' => __('Comment Status', 'e-addons'),
                                'comment_count' => __('Comment Count', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Other', 'e-addons'),
                            'options' => [
                                'ID' => __('ID', 'e-addons'),
                                'post_name' => __('Name', 'e-addons'),
                                'post_type' => __('Post Type Slug', 'e-addons'),
                                'post_author' => __('Author ID', 'e-addons'),                                
                                'post_parent' => __('Parent ID', 'e-addons'),
                                'post_status' => __('Status', 'e-addons'),
                                'post_password' => __('Password', 'e-addons'),                                
                                'menu_order' => __('Menu Order', 'e-addons'),
                                'post_content_filtered' => __('Content Filtered', 'e-addons'),
                                'post_mime_type' => __('Mime Type', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Ping', 'e-addons'),
                            'options' => [
                                'ping_status' => __('Ping Status', 'e-addons'),
                                'to_ping' => __('To Ping', 'e-addons'),
                                'pinged	text' => __('Pinged Text', 'e-addons'),
                            ],
                        ],
                    ],
                //'options' => [],
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

        $this->add_source_controls();

        Utils::add_help_control($this);
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
