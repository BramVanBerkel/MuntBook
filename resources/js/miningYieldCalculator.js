window.miningYieldCalculator = function() {
    return {
        hashrate: 2000,
        watts: 80,
        difficulty: parseInt(difficulty),
        kWhPrice: 0.2,
        price: parseFloat(price),

        // Number of munt mined per period
        muntPerDay: null,
        muntPerWeek: null,
        muntPerMonth: null,
        muntPerYear: null,

        // Number of euros those munt are worth
        eurosPerDay: null,
        eurosPerWeek: null,
        eurosPerMonth: null,
        eurosPerYear: null,

        // Cost associated with mining per period
        costPerDay: null,
        costPerWeek: null,
        costPerMonth: null,
        costPerYear: null,

        // Profit per period
        profitPerDay: null,
        profitPerWeek: null,
        profitPerMonth: null,
        profitPerYear: null,

        calculate() {
            let averageNetworkHash = 542 / 576;
            averageNetworkHash = averageNetworkHash * this.difficulty * 4294967296; // adjusted
            averageNetworkHash = averageNetworkHash / 150000000;

            let chance = this.hashrate / averageNetworkHash;

            this.muntPerDay = (28800 * chance);
            this.muntPerWeek = (this.muntPerDay * 7);
            this.muntPerMonth = (this.muntPerDay * 30);
            this.muntPerYear = (this.muntPerDay * 365);

            this.eurosPerDay = (this.muntPerDay * this.price);
            this.eurosPerWeek = (this.muntPerWeek * this.price);
            this.eurosPerMonth = (this.muntPerMonth * this.price);
            this.eurosPerYear = (this.muntPerYear * this.price);

            this.costPerDay = (((this.watts * 24) / 1000) * this.kWhPrice);
            this.costPerWeek = (((this.watts * 168) / 1000) * this.kWhPrice);
            this.costPerMonth = (((this.watts * 720) / 1000) * this.kWhPrice);
            this.costPerYear = (((this.watts * 8760) / 1000) * this.kWhPrice);

            this.profitPerDay = this.eurosPerDay - this.costPerDay;
            this.profitPerWeek = this.eurosPerWeek - this.costPerWeek;
            this.profitPerMonth = this.eurosPerMonth - this.costPerMonth;
            this.profitPerYear = this.eurosPerYear - this.costPerYear;
        },

        format(value) {
            return value.toFixed(2);
        },

        euroFormat(value) {
            return '€'+value.toFixed(2);
        }
    }
}
