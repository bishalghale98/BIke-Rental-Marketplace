import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import ApexCharts from 'apexcharts';

Alpine.plugin(collapse);
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.start();
