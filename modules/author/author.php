<?php

namespace EAddonsEditor\Modules\Author;

use EAddonsForElementor\Base\Module_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Author extends Module_Base {

    public function __construct() {
        parent::__construct();
    }

    public function get_user_id() {
        global $authordata;
        if (empty($authordata->ID)) {
            $post = get_post();
            if (!empty($post)) {
                $authordata = get_userdata($post->post_author); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            }
        }
        return get_the_author_meta('ID');
    }

}
