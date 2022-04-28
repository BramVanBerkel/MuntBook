window.witnessYieldCalculator = function() {
    return {
        networkWeight: networkWeight,
        networkWeightAdjusted: networkWeightAdjusted,
        amount: 20000,
        days: 730,

        yieldPerYear: null,
        yieldPerYearPercentage: null,
        totalYield: null,
        totalYieldPercentage: null,


        calculate() {
            let weight = Math.round((this.amount + (this.amount * this.amount / 100000)) * (1 + (576 * this.days / 210240)));
            if (weight > (this.networkWeight / 100)) weight = (this.networkWeight / 100);

            let interest = (109500000 * (576 / (1 / (weight / this.networkWeightAdjusted) + 100))) / this.amount;
            let interestPeriod = (this.days * (interest / 365));

            this.yieldPerYear = Math.round(interest * this.amount / 10000);
            this.totalYield = Math.round(interestPeriod * this.amount / 10000);

            this.yieldPerYearPercentage = (interest / 100).toFixed(2)+'%';
            this.totalYieldPercentage = (interestPeriod / 100).toFixed(2)+'%';
        }
    }
}
