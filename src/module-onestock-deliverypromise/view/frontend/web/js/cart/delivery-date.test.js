/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\OnestockDeliveryPromise
 * @author    Pascal Noisette <Pascal.Noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

/*jshint browser:true jquery:true*/
/*global alert*/

define(['squire'], function (Squire) {

    "use strict";
    
    var subject;
    beforeEach(function (done) {
        var injector = new Squire();
        injector.require(['Smile_OnestockDeliveryPromise/js/cart/delivery-date'], function (Constr) {
            subject = new Constr();
            done();
        });
    });
    afterEach(function () {
        try {
            injector.clean();
            injector.remove();
        } catch (e) {}
    });

    describe('Smile_OnestockDeliveryPromise/js/cart/delivery-date', function () {
        describe('"calculDeliveryDate" method', function () {
            it('Handle today day delivery.', function () {
                subject.timestamp = 1700723972000;
                expect(subject.calculDeliveryDate(1700727572, 1700731172)).toContain("today between 8:19 AM and 9:19 AM");
            });
            it('Handle next day delivery.', function () {
                subject.timestamp = 1700723972000;
                expect(subject.calculDeliveryDate(1700810372, 1700813972)).toContain("tomorrow between 7:19 AM and 8:19 AM");
            });
            it('Handle same day delivery.', function () {
                subject.timestamp = 1700723972000;
                expect(subject.calculDeliveryDate(1700896772, 1700900372)).toContain("on Saturday, November 25 between 7:19 AM and 8:19 AM");
            });
            it('Handle delivery between two date.', function () {
                subject.timestamp = 1700723972000;
                var result = subject.calculDeliveryDate(1700810372, 1700900372);
                expect(result).toContain("between Friday, November 24 and Saturday, November 25")
            });
        });
    });
});
