/*
 * E-ADDONS for Elementor - EDITOR
 * e-addons.com
 */
(function ($) {
    jQuery(window).load(function () {

        function e_addons_controls_finder(panel, model, view) {
            console.log(panel);
            console.log(model);
            console.log(view);
            /*if ('section' !== model.elType && 'column' !== model.elType) {
             return;
             }*/
            //var $element = view.$el.find('.elementor-selector');

            var epanel = jQuery("#elementor-panel");
            var econtrols = epanel.find('#elementor-controls');
            var econtrols_finder = jQuery('<div id="elementor-controls-finder"><div id="elementor-controls-finder__search"><i class="eicon-search"></i><input id="elementor-controls-finder__search__input" placeholder="Type to find '+model.attributes.elType.toUpperCase()+' Controls"></div><div id="elementor-controls-finder__results"></div>');
            econtrols_finder.insertBefore(econtrols);
            var econtrols_finder_results = econtrols_finder.find('#elementor-controls-finder__results');
            econtrols_finder.find('#elementor-controls-finder__search__input').focus().on('change keyup', function () {                
                var search_txt = jQuery(this).val().toLowerCase();
                econtrols_finder_results.html('');
                econtrols_finder_results.append('<div class="elementor-control elementor-control-section_controls_finder elementor-control-type-section elementor-label-inline elementor-control-separator-none elementor-open"><div class="elementor-control-content"><div class="elementor-panel-heading"><div class="elementor-panel-heading-toggle elementor-section-toggle" data-collapse_id="section_controls_finder"><i class="eicon" aria-hidden="true"></i></div><div class="elementor-panel-heading-title elementor-section-title">Search Results:</div></div></div></div>');
                console.log(model.attributes.settings.controls);
                jQuery.each(model.attributes.settings.controls, function (index, element) {
                    if (element.type == 'hidden')
                        return;
                    if (element.type == 'tab')
                        return;
                    if (index.includes(search_txt) 
                            || element.label.toLowerCase().includes(search_txt) 
                            || element.description.toLowerCase().includes(search_txt)) {
                        //console.log(element.label);
                        econtrols_finder_results.append('<div class="elementor-control elementor-controls-finder-' + index + ' elementor-label-inline elementor-control-separator-default"><div class="elementor-control-content"><div class="elementor-control-field"><label class="elementor-control-title"><b>' + element.label + '</b><br>' + index + '</label><div class="elementor-control-input-wrapper elementor-control-unit-5">Type: ' + element.type + '<br>Tab: ' + element.tab + (element.section ? '<br>Section: ' + element.section : '') + '</div></div></div></div>');
                        jQuery('.elementor-controls-finder-' + index).on('click', function () {
                            econtrols_finder_results.html('');
                            console.log(element);
                            var tab = jQuery(".elementor-component-tab.elementor-panel-navigation-tab.elementor-tab-control-" + element.tab);
                            var section_name = (element.type == 'section') ? element.name : element.section;
                            console.log(tab);
                            console.log(section_name);                            
                            if (!tab.hasClass('elementor-active')) {
                                console.log('click tab ' + element.tab);
                                tab.trigger('click');
                            }
                            setTimeout(() => {
                                var section = ".elementor-control.elementor-control-type-section.elementor-control-" + section_name;
                                console.log(section);
                                if (!jQuery(section).hasClass('elementor-open')) {
                                    console.log('click section ' + section_name);
                                    jQuery(section).trigger('click');
                                }
                                if (element.type != 'section') {
                                    setTimeout(() => {
                                        var control = jQuery('.elementor-control.elementor-control-' + element.name);
                                        //jQuery('.elementor-control').addClass('elementor-hidden-control');
                                        //jQuery('.elementor-control.elementor-control-'+element.name).removeClass('elementor-hidden-control');
                                        if (control.hasClass('elementor-hidden-control') && !control.hasClass('elementor-group-control')) {
                                            var hint = '<div class="elementor-control elementor-controls-finder-hidden elementor-controls-finder-hidden-' + index + ' elementor-control-type-raw_html elementor-control-separator-default"><div class="elementor-control-content"><div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><strong>The <b>' + element.label + '</b> control is currently hidden</strong><ul>';
                                            console.log(element.condition);
                                            if (element.condition) {
                                                jQuery.each(element.condition, function (cond, val) {
                                                    hint += '<li><i>"' + cond + '"</i> : "' + val + '"</li>';
                                                });
                                            }
                                            hint += '</ul></div></div></div>';
                                            control.before(hint);
                                        }
                                        control.addClass('elementor-highlighted-control');
                                        control.on('click', function () {
                                            jQuery(this).removeClass('elementor-highlighted-control');
                                            jQuery('.elementor-controls-finder-hidden-' + index).slideUp();
                                        });
                                        //jQuery(section).removeClass('elementor-hidden-control');
                                    }, 100);
                                }
                            }, 100);
                        });
                    }
                });
            });

        }

        elementor.hooks.addAction('panel/open_editor/section', function (panel, model, view) {
            e_addons_controls_finder(panel, model, view);
        });
        elementor.hooks.addAction('panel/open_editor/column', function (panel, model, view) {
            e_addons_controls_finder(panel, model, view);
        });
        elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
            e_addons_controls_finder(panel, model, view);
        });
        
        /*jQuery(document).on('click', '.elementor-component-tab.elementor-panel-navigation-tab:first-child', function() {
            var panel = jQuery(this);
            e_addons_controls_finder(panel, model, view);
        });*/

    });
})(jQuery, window);



