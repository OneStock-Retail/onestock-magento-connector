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
        defaults: {
            tomorrow: $t('tomorrow between '),
            today: $t('today between '),
            andDate: $t(' and '),
            onDate: $t('on '),
        },

        /**
         *
         * @param start
         * @param end
         * @returns {string}
         */
        calculDeliveryDate: function (start, end) {
            var locale = window.LOCALE || 'en-US';
            var result= "";
            const timestampNow = Date.now();
            const timestampEtaStart = start * 1000;
            const timestampEtaEnd = end * 1000;
            const dateNow = new Date(timestampNow);
            const dateEtaStart = new Date(timestampEtaStart);
            const dateEtaEnd = new Date(timestampEtaEnd);
            const dateNowDayAfter = new Date(timestampNow + 86400000);
            const dateEtaEndDayAfter = new Date(timestampEtaEnd + 86400000);


            if(( timestampEtaStart - timestampNow ) < 86400000 && ( dateNow.getDay() === dateEtaEnd.getDay())) {
                result += this.today;

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 86400000 && ( dateNow.getDay() !== dateEtaEnd.getDay())) {
                result += this.tomorrow;

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 17280000 && ( dateNowDayAfter.getDay() !== dateEtaEndDayAfter.getDay()))  {
                result += this.tomorrow;
                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            result += this.onDate
            var day = function() {
                const options = {
                    weekday: 'long',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }

            var month = function() {
                const options = {
                    month: 'long',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }

            var year = function() {
                const options = {
                    year: '2-digit',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }
            result += day() + ", " + month() + " " + year() + " between "
            result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
            result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
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
        }
    });
});
