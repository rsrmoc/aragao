import moment from 'moment';

Alpine.store('helpers', {
    formatDate(date, format = 'L') {
        return moment(date).format(format)
    }
})