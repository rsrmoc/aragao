Alpine.data('pageChat', () => ({
    chats: [],
    chatSelected: null,
    activeChat: false,
    messages: [],
    modalUsuarios: false,

    typeUsuarios: {
        admin: 'Admin',
        engineer: 'Profissional',
        client: 'Cliente'
    },

    modalImageSrc: null,
    modalImage: null,

    init() {
        this.getChats();
    },

    orderChat() {
        this.chats.sort((a, b) => {
            let dtA = new Date(a?.last_message?.created_at);
            let dtB = new Date(b?.last_message?.created_at);

            return dtB - dtA;
        });
    },

    scrollChat() {
        let messages = document.querySelector('#container-messages');
        setTimeout(() => messages.scroll(0, messages.scrollHeight), 50);
    },

    getChats() {
        let component = Livewire.first();

        component.getChats().then((chats) => {
            if (!chats) return;

            this.chats = chats;
            this.orderChat();
        })
    },

    initChat(chat) {
        this.closeChat();

        let component = Livewire.first();
        component.idChatMessange = chat.id;

        component.messagesFromChat()
            .then(messages => {
                this.messages = messages;
                
                this.scrollChat();
            });

        chat.unviewed_messages_count = 0;
        this.chatSelected = chat;
        this.activeChat = true;
    },
    closeChat() {
        let component = Livewire.first();
        component.idChatMessange = null;
        component.novoChat = false;
        component.novoChatUser = null;

        this.chatSelected = null;
        this.activeChat = false;
        this.messages = [];

        document.querySelector('#images-chat').value = '';
    },

    inputSendMessage(evt) {
        if (evt.key === "Enter" && evt.keyCode === 13) this.sendMessage();
    },
    async sendMessage() {
        let component = Livewire.first();

        if ((component.inputMessage === null || component.inputMessage?.trim() === '') &&
            component.imagesChat.length == 0) return;

        component.messageStore()
            .then((message) => {
                if (!message) return;

                component.inputMessage = null;

                if (Array.isArray([message])) this.messages = this.messages.concat(message);
                else this.messages.push(message);

                this.chatSelected.last_message = this.messages[this.messages.length - 1];
                this.orderChat();
                this.scrollChat();

                document.querySelector('#images-chat').value = '';
            });
    },

    initials(name) {
        if (!name) return;

        let names = name.split(' ');

        return names[0][0]+(names[1] ? names[1][0]: '');
    },

    initChatUser(usuario) {
        this.closeChat();

        let chat = this.chats.find((chat) => chat?.usuario?.id == usuario?.id);
        if (chat) {
            this.initChat(chat);
            this.modalUsuarios = false;
            return;
        }

        let component = Livewire.first();

        component.novoChat = true;
        component.novoChatUser = usuario.id;

        this.activeChat = true;
        this.chatSelected = {
            tipo: 'private',
            usuario
        };

        this.modalUsuarios = false;
    },

    eventNovoChatSelected(evt) {
        this.chatSelected = Object.assign({}, evt.detail[0]);
        this.chats.unshift(Object.assign({}, evt.detail[0]));
        this.orderChat();

        let component = Livewire.first();
        component.idChatMessange = this.chatSelected.id;
    },

    closeModalImagesChat() {
        let component = Livewire.first();
        component.resetImagesChat();

        document.querySelector('#images-chat').value = '';
    },

    setModalImage(src) {
        this.modalImageSrc = src;
        this.modalImage = true;
    },
}));