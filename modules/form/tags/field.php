<?php

namespace EAddonsEditor\Modules\Form\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Field extends Base_Tag {

    public function get_name() {
        return 'e-tag-form-field';
    }

    public function get_icon() {
        return 'eadd-dynamic-tag-form-field';
    }

    public function get_pid() {
        return 12799;
    }

    public function get_title() {
        return __('Form Field', 'e-addons');
    }

    public function get_group() {
        return 'form';
    }

    public static function _group() {
        return self::_groups('form');
    }

    public function get_categories() {
        if (Utils::is_plugin_active('elementor-pro')) {
            return [
                'base', //\Elementor\Modules\DynamicTags\Module::BASE_GROUP
                'text', //\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
                'url', //\Elementor\Modules\DynamicTags\Module::URL_CATEGORY
                'image', //\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
            ];
        }
        return [];
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
                'form_field',
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
                                'post_id' => __('Post ID', 'e-addons'),
                                'queried_id' => __('Queried Object ID', 'e-addons'),
                                'form_id' => __('Form ID', 'e-addons'),
                                'all_fields' => __('All Fields', 'e-addons'),
                                'all_fields_not_empty' => __('All Fields (not empty)', 'e-addons'),
                            //'all_fields_labels' => __('All Fields (with Labels)', 'e-addons'),
                            ],
                        ]
                    ],
                ]
        );

        $this->add_control(
                'custom',
                [
                    'label' => __('Custom ID', 'elementor'),
                    'type' => Controls_Manager::TEXT, //'e-query',
                    'placeholder' => __('Form Field Custom ID', 'elementor'),
                    'label_block' => true,
                    //'query_type' => 'metas',
                    //'object_type' => 'term',
                    'condition' => [
                        'form_field' => '',
                    ]
                ]
        );

        /* $this->add_control(
          'form_id',
          [
          'label' => __('Form ID', 'elementor'),
          'type' => Controls_Manager::TEXT, //'e-query',
          'placeholder' => __('Form ID', 'elementor'),
          'label_block' => true,
          'condition' => [
          'form_field' => '',
          ]
          ]
          ); */

        $this->add_control(
                'form_return',
                [
                    'label' => __('Return', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Value', 'e-addons'),
                        'label' => __('Label', 'e-addons'),
                        'raw' => __('Raw', 'e-addons'),
                        'placeholder' => __('Placeholder', 'e-addons'),
                        'type' => __('Type', 'e-addons'),
                        'media' => __('Image', 'e-addons'),
                    ],
                    'condition' => [
                        'form_field' => '',
                    ]
                ]
        );

        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings();
        if (empty($settings))
            return;

        $value = $this->_render($settings);

        echo $value;
    }

    public function _render($settings = array()) {
        if (empty($settings))
            $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        global $e_form;

        if (Utils::is_preview() || empty($e_form)) {

            if (!empty($settings['form_field'])) {
                $field = $settings['form_field'];
                switch ($field) {
                    case 'all_fields_not_empty':
                        $field = '[all-fields|!empty]';
                        break;
                    case 'all_fields':
                        $field = '[all-fields]';
                        break;
                }
            } else {
                $field = $settings['custom'];
            }

            if (empty($settings['form_return'])) {
                echo $field;
            } else {
                switch ($settings['form_return']) {
                    case 'media':
                        return [
                            'id' => '',
                            'url' => Utils::get_placeholder_image_src(),
                        ];
                        break;
                    default:
                        echo $field;
                }
            }
            return;
        }

        $meta = false;
        if ($e_form) {
            if (!empty($settings['form_field'])) {
                $field = $settings['form_field'];

                if (isset($_POST[$field])) {
                    $meta = $_POST[$field];
                } else {
                    switch ($settings['form_field']) {
                        case 'all_fields_not_empty':
                            $meta = '[all-fields|!empty]';
                            break;
                        case 'all_fields':
                        default:
                            $meta = '[all-fields]';
                    }
                    $meta = \EAddonsForElementor\Core\Utils\Form::replace_content_shortcodes($meta, $e_form);
                }
            } else {
                $field = $settings['custom'];


                switch ($settings['form_return']) {

                    case 'label':
                        if (!empty($_POST['form_id'])) {
                            $form_settings = Utils::get_settings_by_element_id($_POST['form_id']);
                            $form_field = \EAddonsForElementor\Core\Utils\Form::get_field($field, $form_settings);
                            $meta = $form_field['field_label'];
                        }
                        break;
                    case 'placeholder':
                        if (!empty($_POST['form_id'])) {
                            $form_settings = Utils::get_settings_by_element_id($_POST['form_id']);
                            $form_field = \EAddonsForElementor\Core\Utils\Form::get_field($field, $form_settings);
                            $meta = $form_field['placeholder'];
                        }
                        break;
                    case 'type':
                        if (!empty($_POST['form_id'])) {
                            $form_settings = Utils::get_settings_by_element_id($_POST['form_id']);
                            $meta = \EAddonsForElementor\Core\Utils\Form::get_field_type($field, $form_settings);
                        }
                        break;
                    case 'raw':
                        if (isset($_FORM['form_fields'][$field])) {
                            $meta = $_FORM['form_fields'][$field];
                        }
                        break;
                    case 'media':
                        if (isset($e_form[$field])) {
                            $id = '';
                            $url = $e_form[$field];
                            return [
                                'id' => $id,
                                'url' => $url,
                            ];
                        }
                        break;
                    default:
                        if (isset($e_form[$field])) {
                            $meta = $e_form[$field];
                        } else {
                            if (isset($_POST['form_fields'][$field])) {
                                $meta = $_POST['form_fields'][$field];
                            }
                            if (isset($_POST[$field])) {
                                $meta = $_POST[$field];
                            }
                        }
                }
            }

            echo Utils::to_string($meta);
        }
    }

    public function get_value(array $options = []) {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $value = $this->_render($settings);

        // for MEDIA Control
        if ($settings['form_return'] == 'media') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                $image_data = [
                    'url' => $value,
                ];
                $thumbnail_id = Utils::url_to_postid($value);
                if ($thumbnail_id) {
                    $image_data['id'] = $thumbnail_id;
                }
                //var_dump($image_data);
                return $image_data;
            }
        }

        return $value;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $options
     *
     * @return string
     */
    public function get_content(array $options = []) {
        $settings = $this->get_settings();

        if ($settings['form_return'] == 'media') {
            $value = $this->get_value($options);
        } else {
            ob_start();
            $this->render();
            $value = ob_get_clean();
        }

        if (empty($value)) {
            // TODO: fix spaces in `before`/`after` if WRAPPED_TAG ( conflicted with .elementor-tag { display: inline-flex; } );
            if (!\Elementor\Utils::is_empty($settings, 'before')) {
                $value = wp_kses_post($settings['before']) . $value;
            }

            if (!\Elementor\Utils::is_empty($settings, 'after')) {
                $value .= wp_kses_post($settings['after']);
            }
        } elseif (!\Elementor\Utils::is_empty($settings, 'fallback')) {
            $value = $settings['fallback'];
            $value = Utils::get_dynamic_data($value);
        }

        return $value;
    }

}
