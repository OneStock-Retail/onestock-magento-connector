/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiComponent',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data',
], function (ko, _, Component, shippingService, priceUtils, quote, selectShippingMethodAction, checkoutData) {
    'use strict';


    return function (Component) {
        return Component.extend({
            carbonFootprintArray: [],


            CalculDeadLineText: function (timestampCutOff) {

                var dateCutOff = new Date(timestampCutOff * 1000);
                var timestampNow = Date.now();
                var dateNow = new Date(timestampNow);
                var textToDisplay = "";

                var diff = {}							// Initialisation du retour
                var tmp = dateCutOff - dateNow;
                tmp = Math.floor(tmp / 1000);             // Nombre de secondes entre les 2 dates
                diff.sec = tmp % 60;					// Extraction du nombre de secondes

                tmp = Math.floor((tmp - diff.sec) / 60);	// Nombre de minutes (partie entière)
                diff.min = tmp % 60;					// Extraction du nombre de minutes

                tmp = Math.floor((tmp - diff.min) / 60);	// Nombre d'heures (entières)
                diff.hour = tmp % 24;					// Extraction du nombre d'heures

                tmp = Math.floor((tmp - diff.hour) / 24);	// Nombre de jours restants
                diff.day = tmp;

                if (diff.day) {
                    textToDisplay += diff.day + "d " + diff.hour + "h " +  diff.min + "m";
                    return textToDisplay;
                }
                if (diff.hour) {
                    textToDisplay +=  diff.hour + "h " + diff.min + "m";
                    return textToDisplay;
                }
                if (diff.min) {
                    textToDisplay +=  diff.min + "m ";
                    return textToDisplay;
                }

            },

            calculDeliveryDate: function (Start, End) {
                var timestampNow = Date.now();
                var timestampEtaStart = Start * 1000;
                var timestampEtaEnd = End * 1000;
                var dateNow = new Date(timestampNow)
                var dateEtaStart = new Date(timestampEtaStart );
                var dateEtaEnd = new Date(timestampEtaEnd);
                var dateNowDayAfter = new Date(timestampNow + 86400000)
                var dateEtaEndDayAfter = new Date(timestampEtaEnd + 86400000);
                var textToDisplay = "";

                if(( timestampEtaEnd - timestampNow ) < 86400000 && ( dateNow.getDay() === dateEtaEnd.getDay())) {
                    textToDisplay += "today between ";

                    textToDisplay += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + "and ";
                    textToDisplay += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                    return textToDisplay;
                }
                if(( timestampEtaEnd - timestampNow ) < 86400000 && ( dateNow.getDay() !== dateEtaEnd.getDay())) {
                    textToDisplay += "tomorrow between ";

                    textToDisplay += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + "and ";
                    textToDisplay += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                    return textToDisplay;
                }
                if(( timestampEtaEnd - timestampNow ) < 17280000 && ( dateNowDayAfter.getDay() !== dateEtaEndDayAfter.getDay()))  {
                    textToDisplay += "tomorrow between ";
                    textToDisplay += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + "and ";
                    textToDisplay += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                    return textToDisplay;
                }
                textToDisplay += "on ";
                var day = function() {
                    const options = {
                        weekday: 'long',
                    };
                    return  dateEtaStart.toLocaleDateString("en-US", options);
                }

                var month = function() {
                    const options = {
                        month: 'long',
                    };
                    return  dateEtaStart.toLocaleDateString("en-US", options);
                }

                var year = function() {
                    const options = {
                        year: '2-digit',
                    };
                    return  dateEtaStart.toLocaleDateString("en-US", options);
                }
                textToDisplay += day() + ", " + month() + " " + year() + " between"
                textToDisplay += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + "and ";
                textToDisplay += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return textToDisplay;
            },
        });
    }
});
