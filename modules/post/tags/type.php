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

class Type extends Base_Tag {

    public function get_name() {
        return 'e-tag-post-type';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-post-type';
    }

    public function get_pid() {
        return 7461;
    }
    
    public function get_title() {
        return __('Post Type', 'e-addons');
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
                        "name" => __('Type Name', 'e-addons'),
                        "label" => __('Type Label (Plural)', 'e-addons'),
                        "labels_singular_name" => __('Type Label Singular', 'e-addons'),   
                        "labels_archives" => __('Type Archives Label', 'e-addons'),
                        "description" => __('Type Description', 'e-addons'),
                        "rewrite_slug" => __('Type Slug', 'e-addons'),
                        "query_var" => __('Type Query Var', 'e-addons'),
                        //"taxonomies" => __('Taxonomies', 'e-addons'),
                        //"rest_base" => __('Base path REST API endpoints', 'e-addons'),                        
                    ],
                    'default' => 'label',
                ]
        );

        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $post_id = get_the_ID();

        if ($post_id) {
            $post = get_post($post_id);
            if ($post && !empty($settings['tag_field'])) {
                $post_type = $post->post_type;
                $type = get_post_type_object($post_type);
                //echo '<pre>';var_dump($type);echo '</pre>';
                switch ($settings['tag_field']) {
                    case 'name':                            
                        $meta = $post_type;
                        break;
                    case 'description':                            
                        $meta = $type->description;
                        break;
                    case 'query_var':                            
                        $meta = $type->query_var;
                        break;
                    case 'taxonomies':                            
                        $meta = $type->taxonomies;
                        break;
                    case 'rest_base':                            
                        $meta = $type->rest_base;
                        break;
                    case 'rewrite_slug':      
                        if (empty($type->rewrite)) {
                            $meta = $type->name;
                        } else {
                            $meta = $type->rewrite['slug'];
                        }
                        break;
                    case 'labels_singular_name':
                        $meta = $type->labels->singular_name;
                        break;
                    case 'labels_archives':
                        $meta = $type->labels->archives;
                        break;
                    case 'label':
                        //$meta = $tax->labels->name;
                        //break;
                    default:
                        $meta = $type->label;                            
                }
            }
            
            echo Utils::to_string($meta);
        }
    }

}
