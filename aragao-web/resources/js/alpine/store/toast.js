Alpine.store('toast', {
    toasts: [],

    init() {
        Livewire.on('toast-event', (data) => this.add(data[0], data[1]));
    },

    add(message, type) {
        let toast = {
            message: message,
            type: type,
            hash: (Math.random() * 9999)
        };

        this.toasts.unshift(toast);
        setTimeout(() => {
            let index = this.toasts.findIndex((t) => t.hash == toast.hash);
            this.toasts.splice(index, 1);
        }, 10000);
    },

    remove(index) {
        this.toasts.splice(index, 1);
    }
})