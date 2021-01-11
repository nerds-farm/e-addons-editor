<?php

namespace EAddonsEditor\Modules\Post\Traits;

use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;

trait Posts {
    
    public function add_source_controls() {
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
                'excluded_terms',
                [
                    'label' => __('Excluded Terms', 'elementor'),
                    'type' => 'e-query',
                    'placeholder' => __('Select excluded Terms', 'elementor'),
                    'label_block' => true,
                    'query_type' => 'terms',
                    'multiple' => true,
                    'separator' => 'before',
                    'condition' => [
                        'source' => ['previous', 'next'],
                    ]
                ]
        );
        $this->add_control(
                'in_same_term',
                [
                    'label' => __('In same Term', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'separator' => 'before',
                    'condition' => [
                        'source' => ['previous', 'next'],
                    ]
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
                        'in_same_term!' => '',
                        'source' => ['previous', 'next'],
                    ]
                ]
        );
    }
    
    public function get_post_taxonomy($post_id) {
        $post = get_post($post_id);
        if ($post->post_type != 'post') {
            $taxonomies = Utils::get_taxonomies($post->post_type);
            if (!empty($taxonomies)) {
                $taxonomies_keys = array_keys($taxonomies);
                return end($taxonomies_keys);
            }
        }
        return 'category';
    }

    public function get_post_id() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $post_id = get_the_ID();

        if ($settings['source']) {
            switch ($settings['source']) {

                case 'previous':
                    $taxonomy = $settings['taxonomy'] ? $settings['taxonomy'] : $this->get_post_taxonomy($post_id);
                    $prev = get_adjacent_post((bool) $settings['in_same_term'], $settings['excluded_terms'], true, $taxonomy);
                    if ($prev && is_object($prev) && get_class($prev) == 'WP_Post') {
                        return $prev->ID;
                    }
                    break;

                case 'next':
                    $taxonomy = $settings['taxonomy'] ? $settings['taxonomy'] : $this->get_post_taxonomy($post_id);
                    $next = get_adjacent_post((bool) $settings['in_same_term'], $settings['excluded_terms'], false, $taxonomy);
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
                        } while ($parent_id);
                        return $post_id;
                    }
            }
        }

        return $post_id;
    }
}
