<?php

namespace EAddonsEditor\Modules\Media\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Field extends Base_Tag {
    
    use \EAddonsEditor\Modules\Media\Traits\Medias;

    public function get_name() {
        return 'e-tag-media-field';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-media';
    }

    public function get_pid() {
        return 19071;
    }

    public function get_title() {
        return __('Media Field', 'e-addons');
    }

    public function get_group() {
        return 'media';
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
                                '_wp_attachment_image_alt' => __('Alternative Text', 'e-addons'),
                                'post_excerpt' => __('Caption', 'e-addons'),
                                'post_content' => __('Description', 'e-addons'),
                                '_wp_attachment_metadata' => __('Meta Data', 'e-addons'),
                                'post_mime_type' => __('Mime Type', 'e-addons'),                                
                            ],
                        ],
                        [
                            'label' => __('Link', 'e-addons'),
                            'options' => [
                                'permalink' => __('Permalink', 'e-addons'),
                                'guid' => __('File URL', 'e-addons'),
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
                                'post_author' => __('Author ID', 'e-addons'),                                
                                'post_parent' => __('Uploaded to', 'e-addons'),
                                'post_password' => __('Password', 'e-addons'),                                
                                'menu_order' => __('Menu Order', 'e-addons'),
                                'post_content_filtered' => __('Content Filtered', 'e-addons'),
                            ],
                        ],
                    ],
                    'default' => 'guid',
                ]
        );
        
        $this->add_control(
                'date_format',
                [
                    'label' => __('Date Format', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __('Y-m-h', 'elementor'),
                    'condition' => [
                        'tag_field' => ['post_date','post_date_gmt','post_modified','post_modified_gmt'],
                    ]
                ]
        );
        
        $this->add_control(
                'metadata',
                [
                    'label' => __('Meta Data', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'width' => __('The width of the attachment', 'elementor'),
                        'height' => __('The height of the attachment', 'elementor'),
                        'file' => __('The file path relative to wp-content/uploads', 'elementor'),
                        'sizes' => __('Sizes', 'elementor'),
                        'image_meta' => __('Image metadata', 'elementor'),
                    ],
                    'default' => 'file',
                    'condition' => [
                        'tag_field' => ['_wp_attachment_metadata'],
                    ]
                ]
        );
        $this->add_control(
                'metadata_key',
                [
                    'label' => __('Image Metadata', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __('iso', 'elementor'),
                    'condition' => [
                        'tag_field' => ['_wp_attachment_metadata'],
                        'metadata' => ['image_meta'],
                    ]
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
                    'object_type' => 'attachment',
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

        $media_id = $this->get_media_id();        
        
        if ($media_id) {
            if (!empty($settings['tag_field'])) {
                $field = $settings['tag_field'];
                switch ($field) {
                    case 'permalink':
                        $meta = get_permalink($media_id);
                        break;
                    case '_wp_attachment_metadata':
                    case '_wp_attachment_image_alt':                        
                    default:
                        $meta = Utils::get_post_field($field, $media_id);
                }
                if ($field == '_wp_attachment_metadata') {
                    switch($settings['metadata']) {
                        case 'width':
                        case 'height':
                        case 'file':
                            $meta = $meta[$settings['metadata']];
                            break;
                        case 'sizes':
                            $meta = array_keys($meta['sizes']);
                            break;
                        case 'image_meta':
                            if (empty($settings['metadata_key'])) {
                                $meta = array_keys($meta['image_meta']);
                            } else {
                                if (empty($meta['image_meta'][$settings['metadata_key']])) {
                                    $meta = '';
                                } else {
                                    $meta = $meta['image_meta'][$settings['metadata_key']];
                                }
                            }
                    }
                }
                if (in_array($field, ['post_date','post_date_gmt','post_modified','post_modified_gmt'])) {
                    if ($settings['date_format']) {
                        $time = strtotime($meta);
                        $meta = date($settings['date_format'], $time);
                    }
                }
            } else {
                $field = $settings['custom'];
                $meta = Utils::get_post_field($field, $media_id);
            }

            echo Utils::to_string($meta);
        }
    }

}
