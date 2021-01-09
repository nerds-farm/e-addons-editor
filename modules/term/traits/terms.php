<?php

namespace EAddonsEditor\Modules\Term\Traits;

use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;

trait Terms {
    
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
                        'post' => __('Current Post', 'e-addons'),
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
                    $term = Utils::get_term($term_id);
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
}
