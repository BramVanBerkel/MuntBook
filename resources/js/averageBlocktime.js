import Chart from 'chart.js/auto';
import 'chartjs-adapter-luxon';

async function getAverageBlocktimes() {
    const request = await fetch('/api/average-blocktime');
    return await request.json();
}

getAverageBlocktimes().then(averageBlocktimes => {
    console.log(averageBlocktimes);
    new Chart('average-blocktime', {
        type: 'line',
        data: {
            datasets: [
                {
                    label: 'Average blocktimes',
                    data: averageBlocktimes.averageBlockTimes.map(averageBlocktime => ({
                        'x': averageBlocktime.date,
                        'y': averageBlocktime.seconds,
                    })),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.5,
                    yAxisID: 'averageBlocktimes',
                },
                {
                    label: 'Blocks per day',
                    data: averageBlocktimes.blocksPerDay.map(day => ({
                        'x': day.date,
                        'y': day.blocks,
                    })),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.5,
                    yAxisID: 'blocksPerDay'
                }
            ],
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    type: "time"
                },
                averageBlocktimes: {
                    position: 'left',
                    min: 140,
                    max: 160,
                },
                blocksPerDay: {
                    position: 'right',
                    min: 400,
                }
            }
        }
    });
})
