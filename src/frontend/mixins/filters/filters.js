import Vue from 'vue'
import moment from 'moment'

Vue.filter('formatDate', function (value) {
	if (value) {
		return moment(String(value)).format('MM/DD/YYYY hh:mm')
	}
});

// Vue.mixin({
// 	methods: {
// 		getMonth: function () {
// 			let date = new Date(), y = date.getFullYear(), m = date.getMonth();
// 			let firstDay = new Date(y, m, 1);
// 			let lastDay = new Date(y, m + 1, 0);
// 			let currentMonth = new Date().getMonth();
// 			let currentYear = new Date().getFullYear();

// 			return [
// 				currentYear + '-' + currentMonth + '-' + firstDay,
// 				currentYear + '-' + currentMonth + '-' + lastDay,
// 			];
// 		}
// 	}
// })