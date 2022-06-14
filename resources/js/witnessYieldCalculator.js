window.witnessYieldCalculator = function () {
    return {
        networkWeight: networkWeight,
        networkWeightAdjusted: networkWeightAdjusted,
        witnessReward: witnessReward,
        amount: 20000,
        days: 730,

        yieldPerYear: null,
        yieldPerYearPercentage: null,
        totalYield: null,
        totalYieldPercentage: null,


        calculate() {
            this.amount = this.clamp(this.amount, 5000, 1000000);
            this.days = this.clamp(this.days, 30, 1095);

            // 150 seconds per block, 86.400 seconds per day
            let blocksPerDay = 86_400 / 150;
            let daysPerYear = 365;
            let blocksPerYear = blocksPerDay * daysPerYear;
            let cooldownPeriod = 100;
            let quantityModifier = 100_000;
            let lockLengthInBlocks = blocksPerDay * this.days;

            // calculate the weight
            let weight = (this.amount + ((this.amount * this.amount) / quantityModifier)) * (1 + (lockLengthInBlocks / blocksPerYear));

            // weight can be no more than 1% of the total network weight
            if (weight > (this.networkWeight / 100)) weight = (this.networkWeight / 100);

            //calculate the interest based on the weight
            let interestPercentage = (((blocksPerDay / (1 / (weight / this.networkWeightAdjusted) + cooldownPeriod)) * daysPerYear) * this.witnessReward) / this.amount;

            let yieldPerYear = interestPercentage * this.amount;
            let totalYield = (yieldPerYear / daysPerYear) * this.days;

            this.yieldPerYear = (yieldPerYear).toFixed(2);
            this.totalYield = (totalYield).toFixed(2);

            this.yieldPerYearPercentage = ((yieldPerYear / this.amount) * 100).toFixed(2) + '%';
            this.totalYieldPercentage = ((totalYield / this.amount) * 100).toFixed(2) + '%';
        },

        clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }
    }
}
