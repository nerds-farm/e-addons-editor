<?php

namespace EAddonsEditor\Modules\Media\Traits;

use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;

trait Medias {
    
    public function add_source_controls() {
        $this->add_control(
                'source',
                [
                    'label' => __('Source', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Featured Image', 'e-addons'),
                        'meta_post' => __('Post Meta Field', 'e-addons'),
                        'meta_user' => __('User Meta Field', 'e-addons'),
                        'meta_author' => __('Author Meta Field', 'e-addons'),
                        'site_option' => __('Site Option', 'e-addons'),
                        'other' => __('Other', 'e-addons'),
                    ],
                //'label_block' => true,
                ]
        );
        
        $this->add_control(
                'meta_post_name',
                [
                    'label' => __('Post Meta field', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Post Meta field', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'post',
                    'condition' => [
                        'source' => 'meta_post',
                    ]
                ]
        );
        $this->add_control(
                'meta_user_name',
                [
                    'label' => __('User Meta field', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select User Meta field', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'user',
                    'condition' => [
                        'source' => ['meta_user', 'meta_author'],
                    ]
                ]
        );
        $this->add_control(
                'option_name',
                [
                    'label' => __('Site Option field', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Site Option', 'elementor'),
                    'description' => __('Leave empty for Site Logo', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'options',
                    'condition' => [
                        'source' => ['site_option'],
                    ]
                ]
        );
        
        $this->add_control(
                'media_id',
                [
                    'label' => __('Media', 'elementor'),
                    'type' => 'file',
                    'placeholder' => __('Select other Media', 'elementor'),
                    'condition' => [
                        'source' => 'other',
                    ]
                ]
        );

    }

    public function get_media_id() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        global $post;
        
        $media_id = get_post_thumbnail_id();
        if (!empty($post) && $post->post_type == 'attachment') {
            $media_id = $post->ID;
        }
        $queried_object = get_queried_object();
        if (!empty($queried_object) && get_class($queried_object) == 'WP_Post' && $queried_object->post_type == 'attachment') {
            $media_id = $queried_object->ID;
        }
        
        if ($settings['source']) {
            switch ($settings['source']) {

                case 'site_option':
                    if (!empty($settings['option_name'])) {
                        return get_option($settings['option_name']);
                    }
                    return get_theme_mod( 'custom_logo' );
                    
                case 'meta_post':
                    return get_post_meta(get_the_ID(), $settings['meta_post_name'], true);
                    
                case 'meta_user':
                    $user_id = get_current_user_id();
                case 'meta_author':
                    if (empty($user_id)) {
                        $user_id = get_the_author_meta('ID');
                    }
                    return get_user_meta($user_id, $settings['meta_user_name'], true);

                case 'other':
                    if ($settings['media_id']) {
                        return $settings['media_id'];
                    }
                    break;

                default:
                    // media page
                    return get_the_ID();
                    
            }
        }

        return $media_id;
    }
}
