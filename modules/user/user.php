<?php

namespace EAddonsEditor\Modules\User;

use EAddonsForElementor\Base\Module_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class User extends Module_Base {

    public function __construct() {
        parent::__construct();
    }

    public function get_user_id() {
        return get_current_user_id();
    }

}
