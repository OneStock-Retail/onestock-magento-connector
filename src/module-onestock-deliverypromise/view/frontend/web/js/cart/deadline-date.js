/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\OnestockDeliveryPromise
 * @author    Romain ITIFO <romain.itofo@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

define([
    'ko',
    'uiComponent',
], function (ko, Component) {
    'use strict';


        return Component.extend({

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
