/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 * 
 * You can run this test quick by building static (bin/magento setup:static-content:deploy en_US -f)
 * and run in a node environnement (docker run -v `pwd`:/app node:14)
 * 
 * - npm i
 * - npm i puppeteer
 * - sudo apt-get install ca-certificates fonts-liberation libappindicator3-1 libasound2 libatk-bridge2.0-0 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libglib2.0-0 libgtk-3-0 libnspr4 libnss3 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 lsb-release wget xdg-utils
 * - cp app/code/Smile/OnestockDeliveryPromise/view/frontend/web/js/product/promise.test.js dev/tests/js/jasmine/tests/app/code/Smile/OnestockDeliveryPromise/frontend/js/product/promise.test.js
 * - ./node_modules/.bin/grunt spec:blank --file=dev/tests/js/jasmine/tests/app/code/Smile/OnestockDeliveryPromise/frontend/js/product/promise.test.js
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
                subject.timestamp=1700723972000;
                expect(subject.calculDeliveryDate(1700727572,1700731172)).toContain("today between 8:19 AM and 9:19 AM");
            });
            it('Handle next day delivery.', function () {
                subject.timestamp=1700723972000;
                expect(subject.calculDeliveryDate(1700810372,1700813972)).toContain("tomorrow between 7:19 AM and 8:19 AM");
            });
            it('Handle same day delivery.', function () {
                subject.timestamp=1700723972000;
                expect(subject.calculDeliveryDate(1700896772,1700900372)).toContain("on Saturday, November 25 between 7:19 AM and 8:19 AM");
            });
            it('Handle delivery between two date.', function () {
                subject.timestamp=1700723972000;
                var result = subject.calculDeliveryDate(1700810372,1700900372);
                expect(result).toContain("between Friday, November 24 and Saturday, November 25")
            });
        });
    });
});
