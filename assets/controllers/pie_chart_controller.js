import { Controller } from '@hotwired/stimulus';
import ApexCharts from 'apexcharts';

export default class extends Controller {
    connect() {
        const values = this.element.dataset.pieChartValues;
        const labels = this.element.dataset.pieChartLabels;

        if (values && labels) {
            const valueArray = values.split(',').map(Number);
            const labelArray = labels.split(',');

            const options = {
                series: valueArray,
                chart: {
                    type: 'pie',
                    height: 350,
                },
                labels: labelArray,
                legend: {
                    show: false
                }
            };

            new ApexCharts(this.element.querySelector('.chart-container'), options).render();
        } else {
            console.error('Pie chart data attributes are missing or incorrect.');
        }
    }
}
