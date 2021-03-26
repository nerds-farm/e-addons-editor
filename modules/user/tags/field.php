<?php

namespace EAddonsEditor\Modules\User\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Field extends Base_Tag {
    
    use \EAddonsEditor\Modules\User\Traits\Users;

    public function get_name() {
        return 'e-tag-user-field';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-user-field';
    }

    public function get_pid() {
        return 7450;
    }

    public function get_title() {
        return __('User Field', 'e-addons');
    }

    public function get_group() {
        return 'user';
    }

    public static function _group() {
        return self::_groups('user');
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
                                'display_name' => __('Display Name', 'e-addons'),
                                'description' => __('Description (Bio)', 'e-addons'),
                                'user_login' => __('Login', 'e-addons'),
                                'user_email' => __('Email', 'e-addons'),
                                'user_url' => __('Url (Website)', 'e-addons'),
                                'user_registered' => __('Registered', 'e-addons'),
                                'roles' => __('Roles', 'e-addons'),                                
                            ],
                        ],
                        [
                            'label' => __('Link', 'e-addons'),
                            'options' => [
                                "link" => __('Link (to Posts Archive)', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Name', 'e-addons'),
                            'options' => [
                                'first_name' => __('First Name', 'e-addons'),
                                'last_name' => __('Last Name', 'e-addons'),
                                'nickname' => __('Nickname', 'e-addons'),
                                'user_nicename' => __('Nicename', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Other', 'e-addons'),
                            'options' => [
                                'ID' => __('ID', 'e-addons'),
                                'admin_color' => __('Color', 'e-addons'),
                                'comment_shortcuts' => __('Comment Shortcuts', 'e-addons'),
                                'user_activation_key' => __('Activation Key', 'e-addons'),
                                'user_pass' => __('Password', 'e-addons'),
                                'user_status' => __('Status', 'e-addons'),
                                'user_level' => __('Level', 'e-addons'),
                                'plugins_last_view' => __('Plugins last view', 'e-addons'),
                                'plugins_per_page' => __('Plugins per page', 'e-addons'),
                                'rich_editing' => __('Rich Editing', 'e-addons'),
                                'syntax_highlighting' => __('Syntax Highlighting', 'e-addons'),
                            ],
                        ],
                        [
                            'label' => __('Social (deprecated)', 'e-addons'),
                            'options' => [
                                'aim' => __('AIM', 'e-addons'),
                                'yim' => __('YIM', 'e-addons'),
                                'jabber' => __('Jabber', 'e-addons'),
                            ],
                        ],
                    //'user_description' => __('Description (Bio)', 'e-addons'),
                    //'user_firstname' => __('First Name', 'e-addons'),
                    //'user_lastname' => __('Last Name', 'e-addons'),
                    ],
                //'options' => [],
                //'default' => 'display_name',
                //'label_block' => true,
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
                    'object_type' => 'user',
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

        $user_id = $this->get_user_id();


        if (!empty($settings['tag_field'])) {
            switch ($settings['tag_field']) {
                case 'link':
                    $meta = get_author_posts_url($user_id);
                    break;
                case 'roles':
                    global $wp_roles;
                    //var_dump($wp_roles); die();
                    $user = get_userdata($user_id);
                    $roles = (array) $user->roles;
                    $meta = array();
                    if (!empty($roles)) {
                        foreach ($roles as $role) {
                            //$orole = get_role($role);
                            if (empty($wp_roles->roles[$role]['name'])) {
                                $meta[] = $role;
                            } else {
                                $meta[] = $wp_roles->roles[$role]['name'];
                            }
                        }
                    }
                    break;
                default:
                    $meta = get_the_author_meta($settings['tag_field'], $user_id);
            }
        } else {
            $meta = Utils::get_user_field($user_id, $settings['custom']);
        }

        echo Utils::to_string($meta);
    }

}
