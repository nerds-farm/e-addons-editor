<?php

namespace EAddonsEditor\Modules\DynamicTags\Traits;

/**
 * @author francesco
 */
trait Background_Slideshow_Gallery {

    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $options
     *
     * @return mixed
     */
    public function get_content(array $options = []) {

        $value = $this->get_value($options);
        if (!empty($value) && is_array($value)) {
            foreach ($value as $key => $image) {
                if (!empty($image['id']) && empty($image['url'])) {
                    $value[$key]['url'] = wp_get_attachment_url($image['id']); // fix for Background Image Slider
                }
            }
        }

        return $value;
    }
    
    public function unregister_tag() {
        $module = \Elementor\Plugin::$instance->dynamic_tags;
        $module->unregister_tag( $this->get_name()  );
    }
    
    public function get_title() {
        /*
        $tmp = explode('\\', __CLASS__);
        $title = end($tmp);
        $title = str_replace('_', ' ', $title);
        */
        $title = parent::get_title();
        if (isset($_GET['page']) && $_GET['page'] == 'e_addons_settings') {
            $title .= __(' Background Slider Fix');
        }
        return $title;
    }
    
    public function get_icon() {
        return 'eadd-dynamic-tag-bggalleryslider';
    }
    public function get_pid() {
        return 8849;
    }
}
