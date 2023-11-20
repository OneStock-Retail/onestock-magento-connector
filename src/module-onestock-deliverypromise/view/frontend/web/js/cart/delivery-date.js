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
], function (Component, ko) {
    'use strict';


    return Component.extend({
        /**
         *
         * @param start
         * @param end
         * @returns {string}
         */
        calculDeliveryDate: function (start, end) {
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
                result += "today between ";

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + " and ";
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 86400000 && ( dateNow.getDay() !== dateEtaEnd.getDay())) {
                result += "tomorrow between ";

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + " and ";
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 17280000 && ( dateNowDayAfter.getDay() !== dateEtaEndDayAfter.getDay()))  {
                result += "tomorrow between ";
                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + " and ";
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            result += "on ";
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
            result += day() + ", " + month() + " " + year() + " between "
            result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + " and ";
            result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
            return result;
        },
    });

});
