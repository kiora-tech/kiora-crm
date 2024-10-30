import { Controller } from '@hotwired/stimulus';
import ApexCharts from 'apexcharts';

export default class extends Controller {
    static targets = ['chart'];

    connect() {
        if (!this.hasChartTarget) {
            console.error("Chart target is not defined.");
            return;
        }

        const stats = JSON.parse(this.element.dataset.stats);

        const options = {
            series: [{
                name: stats.invitedLabel,
                data: [stats.invitedCount]
            }, {
                name: stats.usedLabel,
                data: [stats.usedCount]
            }, {
                name: stats.createdLabel,
                data: [stats.createdCount]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Statistics'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            }
        };

        const chart = new ApexCharts(this.chartTarget, options);
        chart.render();
    }
}
