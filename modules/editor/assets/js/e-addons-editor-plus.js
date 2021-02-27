/*
 * E-ADDONS for Elementor - EDITOR
 * e-addons.com
 */

// Hide Description
(function ($) {

    "use strict";
    // Load everything when elementor is initialised
    jQuery(window).on('elementor:init', function () {
    //jQuery(window).on('load', function () {
        let elementType = ['section', 'column', 'widget'];
        elementType.forEach(($item) => {
            if (elementor) {
                elementor.hooks.addAction('panel/open_editor/' + $item, function (panel, model, view) {
                    e_hide_description();
                });
            }
        });
        jQuery(document).on('mouseup', '.elementor-control-type-section, .elementor-panel-navigation-tab, .elementor-dynamic-cover', function () {
            e_hide_description();
        });
        function e_hide_description() {
            setTimeout(function () {
                jQuery('.elementor-control-field-description').not('.elementor-control-field-description-hidden').each(function () {
                    var title = jQuery(this).siblings('.elementor-control-field').children('.elementor-control-title');
                    if (title.text().trim()) {
                        var text = jQuery(this).text();
                        text = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                        title.wrapInner('<abbr title="' + text + '"></abbr>');
                        jQuery(this).addClass('elementor-control-field-description-hidden').hide();
                        title.on('click', function () {
                            jQuery(this).parent().siblings('.elementor-control-field-description').toggle();
                            return false;
                        });
                    }
                });
            }, 100);
        }
        
    });
})(jQuery);