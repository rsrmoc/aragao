Alpine.store('dialog', {
    title: null,
    message: null,
    actions: null,

    show(title = null, message = null, actions = null) {
        this.title = title;
        this.message = message;
        this.actions = actions;
        
        document.querySelector('#appDialog').showModal();
    }
});