import Chart from 'chart.js/auto';
import 'chartjs-adapter-luxon';

async function getAverageHashrate() {
    const request = await fetch('/api/average-hashrate');
    return await request.json();
}

getAverageHashrate().then(averageHashrate => {
    new Chart('average-hashrate', {
        type: 'line',
        data: {
            datasets: [
                {
                    label: 'Average hashrate',
                    data: averageHashrate.map(date => ({
                        'x': date.date,
                        'y': date.hashrate,
                    })),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.5,
                    yAxisID: 'averageHashrate',
                },
                {
                    label: 'Average difficulty',
                    data: averageHashrate.map(date => ({
                        'x': date.date,
                        'y': date.difficulty,
                    })),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.5,
                    yAxisID: 'averageDifficulty'
                }
            ],
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            let date = new Date(context[0].parsed.x);

                            return date.toDateString();
                        },
                        label: function(context) {
                            let value = new Intl.NumberFormat().format(context.parsed.y);
                            let label = (context.dataset.yAxisID === 'averageDifficulty') ? 'Difficulty: ' : 'Hashrate: ';

                            return label + value;
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: "time"
                },
                averageHashrate: {
                    position: 'left',
                },
                averageDifficulty: {
                    position: 'right',
                }
            }
        }
    });
})
