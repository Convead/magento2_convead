/**
 * Copyright © Convead. All rights reserved.
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    'use strict';

    $.widget('convead.tracker', {
        options: {
            apiKey: {},
            loaded: false,
            viewProductConfig: {}
        },

        _create: function () {
            var self = this,
                visitorInfo = customerData.get('visitor-info');

            visitorInfo.subscribe(function (visitorInfoUpdated) {
                self.initialize(visitorInfoUpdated);
            }, this);

            customerData.reload('visitor-info');
        },

        initialize: function (data) {
            var self = this;
            if (!self.options.loaded) {
                self.options.loaded = true;

                (function(w,c){w[c]=w[c]||function(){(w[c].q=w[c].q||[]).push(arguments)};})(window,'convead');

                var visitorInfo = data.visitor || {};
                visitorInfo['app_key'] = self.options.apiKey;
                window.ConveadSettings = visitorInfo;

                var ts = (+new Date()/86400000|0)*86400;
                require([
                    'https://tracker.convead.io/widgets/' + ts + '/widget-' + self.options.apiKey + '.js?empty:'
                ], function () {
                    self.onTrackerLoaded();
                });
            }
        },

        onTrackerLoaded: function () {
            var options = this.options;
            if (window.convead && !($.isEmptyObject(options.viewProductConfig))) {
                window.convead('event', 'view_product', options.viewProductConfig);
            }
        }
    });

    return $.convead.tracker;
});
