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
    
    public function get_icon() {
        return 'eicon-database';
    }

}