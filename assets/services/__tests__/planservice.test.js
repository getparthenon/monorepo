
import {planservice} from "../planservice";

import axios from "axios";
import MockAdapter from "axios-mock-adapter";
import { describe, it, expect, beforeAll, afterEach } from 'vitest'

// This sets the mock adapter on the default instance
var mock = new MockAdapter(axios);

describe("planservice", () => {
    let mock;

    beforeAll(() => {
        mock = new MockAdapter(axios);
        axios.defaults.validateStatus = function () {
            return true;
        };
    });

    afterEach(() => {
        mock.reset();
    });

    describe("Fetch Plan Info", () => {
        it("Should return response if successful", async () => {

            mock.onGet(`/api/billing/plans`).reply(200, {success: true});

            // when
            const result = await planservice.fetchPlanInfo();

            // then
            expect(mock.history.get[0].url).toEqual(`/api/billing/plans`);
            expect(result.data).toEqual({success: true});
        });

        it("Should return error", async () => {

            mock.onGet(`/api/billing/plans`).reply(400, {success: false, error: "Error message here"});

            try {
                await planservice.fetchPlanInfo();
                fail("Didn't throw error");
            } catch (error) {
                expect(mock.history.get[0].url).toEqual(`/api/billing/plans`);
                expect(error).toEqual("Error message here");

            }
        });
    });
    describe("Change Plan", () => {
        it("Should return response if successful", async () => {

            const id = 'id'
            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/subscription/`+id+'/change/' + planName + '/' + paymentSchedule+'/'+currency).reply(200, {success: true});

            // when
            const result = await planservice.changePlan(id, planName, paymentSchedule, currency)

            // then
            expect(mock.history.post[0].url).toEqual(`/api/billing/subscription/`+id+'/change/' + planName + '/' + paymentSchedule+'/'+currency);
            expect(result.data).toEqual({success: true});
        });

        it("Should return error", async () => {

            const id = 'id'
            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/subscription/`+id+'/change/' + planName + '/' + paymentSchedule+'/'+currency).reply(400, {success: false, error: "Error message here"});

            try {
                await planservice.changePlan(id, planName, paymentSchedule, currency);
                fail("Didn't throw error")
            } catch (error) {
                expect(mock.history.post[0].url).toEqual(`/api/billing/subscription/`+id+'/change/' + planName + '/' + paymentSchedule+'/'+currency);
                expect(error).toEqual("Error message here");

            }
        });
    })

    describe("Create Checkout", () => {
        it("Should return response if successful", async () => {

            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency).reply(200, {success: true});

            // when
            const result = await planservice.createCheckout(planName, paymentSchedule, currency)

            // then
            expect(mock.history.post[0].url).toEqual(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency);
            expect(result.data).toEqual({success: true});
        });

        it("Should return error", async () => {

            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency).reply(400, {success: false, error: "Error message here"});

            try {
                await planservice.createCheckout(planName, paymentSchedule, currency);
                fail("Didn't throw error")
            } catch (error) {
                expect(mock.history.post[0].url).toEqual(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency);
                expect(error).toEqual("Error message here");

            }
        });
    });
    describe("Create Per Seat Checkout", () => {
        it("Should return response if successful", async () => {

            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency, {seats: 1}).reply(200, {success: true});

            // when
            const result = await planservice.createPerSeatCheckout(planName, paymentSchedule, currency, 1)

            // then
            expect(mock.history.post[0].url).toEqual(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency);
            expect(result.data).toEqual({success: true});
        });

        it("Should return error", async () => {

            const planName = "plan-name";
            const paymentSchedule = "payment-schedule";
            const currency = "usd";

            mock.onPost(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency, {seats: 1}).reply(400, {success: false, error: "Error message here"});

            try {
                await planservice.createPerSeatCheckout(planName, paymentSchedule, currency, 1);
                fail("Didn't throw error")
            } catch (error) {
                expect(mock.history.post[0].url).toEqual(`/api/billing/plans/checkout/` + planName + '/' + paymentSchedule + '/' + currency);
                expect(error).toEqual("Error message here");

            }
        });
    });

    describe("Cancel", () => {
        it("Should return response if successful", async () => {

            const subscriptionId = 'id';
            mock.onPost(`/api/billing/subscription/`+subscriptionId+`/cancel`).reply(200, {success: true});

            // when
            const result = await planservice.cancel(subscriptionId);

            // then
            expect(mock.history.post[0].url).toEqual(`/api/billing/subscription/`+subscriptionId+`/cancel`);
            expect(result.data).toEqual({success: true});
        });

        it("Should return error", async () => {

            const subscriptionId = 'id';
            mock.onPost(`/api/billing/subscription/`+subscriptionId+`/cancel`).reply(400, {success: false, error: "Error message here"});
            try {
                await planservice.cancel(subscriptionId);
                fail("Didn't throw error");
            } catch (error) {
                expect(mock.history.post[0].url).toEqual(`/api/billing/subscription/`+subscriptionId+`/cancel`);
                expect(error).toEqual("Error message here");

            }
        });
    });
})
