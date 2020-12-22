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

class Taxonomy extends Base_Tag {

    public function get_name() {
        return 'e-tag-term-tax';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-term-taxomony';
    }

    public function get_pid() {
        return 7459;
    }

    public function get_title() {
        return __('Term Taxonomy', 'e-addons');
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
                    'options' => [
                        "term_taxonomy_id" => __('Term Taxonomy ID', 'e-addons'),
                        "taxonomy" => __('Taxonomy Query Var', 'e-addons'),
                        "label" => __('Taxonomy Label (Plural)', 'e-addons'),
                        "labels_singular_name" => __('Taxonomy Label Singular', 'e-addons'),  
                        "description" => __('Taxonomy Description', 'e-addons'),
                        "rewrite_slug" => __('Taxonomy Slug', 'e-addons'),
                    ],
                    'default' => 'label',
                    'label_block' => true,
                ]
        );

        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $term_id = $this->get_module()->get_term_id();

        if ($term_id) {
            if (!empty($settings['tag_field'])) {
                $taxonomy = get_term_field('taxonomy', $term_id);
                $tax = get_taxonomy($taxonomy);
                //echo '<pre>';var_dump($tax);echo '</pre>';
                switch ($settings['tag_field']) {
                    case 'taxonomy':                            
                        $meta = $taxonomy;
                        break;
                    case 'term_taxonomy_id':                            
                        $meta = get_term_field($settings['tag_field'], $term_id);
                        break;
                    case 'rewrite_slug':                            
                        if (empty($tax->rewrite)) {
                            $meta = $taxonomy;
                        } else {
                            $meta = $tax->rewrite['slug'];
                        }
                        break;
                    case 'labels_singular_name':
                        $meta = $tax->labels->singular_name;
                        break;
                    case 'description':
                        $meta = $tax->description;
                        break;
                    case 'taxonomy_name':
                        //$meta = $tax->labels->name;
                        //break;
                    default:
                        $meta = $tax->label;                            
                }
            }
            
            echo Utils::to_string($meta);
        }
    }

}
