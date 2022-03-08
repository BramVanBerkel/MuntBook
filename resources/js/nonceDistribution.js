import Chart from 'chart.js/auto';

window.nonceDistribution = function() {
    return {
        async init() {
            const request = await fetch('/api/nonce-distribution');
            const nonceData = await request.json();

            new Chart('nonce-distribution-chart', {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Prenonce',
                        data: nonceData.preNonceData,
                        backgroundColor: 'rgb(220, 57, 18)',
                    }, {
                        label: 'Postnonce',
                        data: nonceData.postNonceData,
                        backgroundColor: 'rgb(51, 102, 204)',
                    }],
                },
                options: {
                    scales: {
                        x: {
                            bounds: 'data',
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: item => 'Block ' + item.label + ' ' + item.dataset.label + ': ' + item.formattedValue
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 2
                        }
                    }
                }
            });
        }
    }
}
