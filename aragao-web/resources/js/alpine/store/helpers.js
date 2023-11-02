import 'moment/dist/locale/pt-br';
import moment from 'moment';

moment.locale('pt-br');

Alpine.store('helpers', {
    formatDate(date, format = 'L') {
        return moment(date).format(format)
    },
    formatDateFromNow(date) {
        return moment(date).fromNow();
    }
})