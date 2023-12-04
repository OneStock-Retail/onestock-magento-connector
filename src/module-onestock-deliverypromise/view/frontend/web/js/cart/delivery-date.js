/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\OnestockDeliveryPromise
 * @author    Romain ITOFO <romain.itofo@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */


define([
    'uiComponent',
    'ko',
    'mage/translate'
], function (Component, ko, $t) {
    'use strict';

    return Component.extend({

        timestampNow: Date.now(),

        /**
         *
         * @param start
         * @param end
         * @returns {string}
         */
        calculDeliveryDate: function (start, end) {
            var locale = window.LOCALE || 'en-US';
            const timestampEtaStart = start * 1000;
            const timestampEtaEnd = end * 1000;
            const dateNow = new Date(this.timestampNow);
            const dateTomorrow =  new Date(this.timestampNow + 24*60*60*1000)
            const dateEtaStart = new Date(timestampEtaStart);
            const dateEtaEnd = new Date(timestampEtaEnd);
            var result = "";
            if( (timestampEtaStart - this.timestampNow < 24*60*60*1000) && (timestampEtaEnd - this.timestampNow  < 24*60*60*1000) &&  dateNow.getDay() === dateEtaStart.getDay() && dateEtaStart.getDay()  === dateEtaEnd.getDay()) {
                result = $t('today between ');
            } else if( (timestampEtaStart - this.timestampNow < 24*60*60*1000*2) && ( timestampEtaEnd - this.timestampNow < 24*60*60*1000*2) && dateTomorrow.getDay() === dateEtaStart.getDay() && dateEtaStart.getDay()  === dateEtaEnd.getDay()) {
                result = $t('tomorrow between ');
            } else if(( timestampEtaStart - timestampEtaEnd ) < 24*60*60*1000 && ( dateEtaStart.getDay() === dateEtaEnd.getDay())) {
                result = $t('on ')
                + dateEtaStart.toLocaleDateString(locale, {
                        weekday: 'long',
                        month: 'long',
                        day: 'numeric'
                    })
                + $t(' between ');
            } else {
                return $t(' between ') + dateEtaStart.toLocaleDateString(locale, {
                    weekday: 'long',
                        month: 'long',
                        day: 'numeric'
                }) + $t(' and ') + dateEtaEnd.toLocaleDateString(locale, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric'
                });
            }
            result += dateEtaStart.toLocaleTimeString(locale, {
                minute: 'numeric',
                hour: 'numeric'
            }) + $t(' and ');
            result += dateEtaEnd.toLocaleTimeString(locale, {
                minute: 'numeric',
                hour: 'numeric'
            });


            return result;
        },

        /**
         *
         * @param timestampCutOff
         * @returns {string}
         * @constructor
         */
        CalculDeadLineText: function (timestampCutOff) {

            var dateCutOff = new Date(timestampCutOff * 1000);
            var dateNow = new Date(this.timestampNow);
            var textToDisplay = "";

            var diff = {}							// Initialisation du retour
            var tmp = dateCutOff - dateNow;
            tmp = Math.floor(tmp / 1000);             // Nombre de secondes entre les 2 dates
            diff.sec = tmp % 60;					// Extraction du nombre de secondes

            tmp = Math.floor((tmp - diff.sec) / 60);	// Nombre de minutes (partie entière)
            diff.min = tmp % 60;					// Extraction du nombre de minutes

            tmp = Math.floor((tmp - diff.min) / 60);	// Nombre d'heures (entières)
            diff.hour = tmp % 24;					// Extraction du nombre d'heures

            if (diff.hour) {
                textToDisplay +=  diff.hour + "h" + diff.min + "m";
                return textToDisplay;
            }
            if (diff.min) {
                textToDisplay +=  diff.min + "m ";
                return textToDisplay;
            }
        }
    });
});
