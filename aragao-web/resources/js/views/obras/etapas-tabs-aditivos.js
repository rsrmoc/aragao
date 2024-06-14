Alpine.data('etapasTabAditivos', () => ({
    infoAditivo: null,
    modalInfoAditivo: false,
    modalImageSrc: null,
    modalImage: null,

    carregarImagens() {
        // Mapear todas as imagens para uma lista de promessas de chamadas axios
        const promises = this.infoAditivo.imagens.map(imagem => {
            return axios.get('/home/imagens/arquivo/'+imagem.id)
                .then((response) => {
                    // Assegura que a propriedade 'url' existe no 'data' antes de acessar
                    if (response.data && response.data.url) {
                        imagem.url = response.data.url;
                    }
                    return imagem;
                })
                .catch(error => {
                    console.error('Erro ao carregar a URL da imagem:', error);
                    return imagem;
                });
        });

        // Atualizar as imagens à medida que cada promessa é resolvida
        promises.forEach(promise => {
            promise.then(imagemAtualizada => {
                // Atualizar a imagem no array this.infoAditivo.imagens
                const index = this.infoAditivo.imagens.findIndex(img => img.id === imagemAtualizada.id);
                if (index !== -1) {
                    this.infoAditivo.imagens[index] = imagemAtualizada;
                }
            });
        });
    },


    setInfoAditivo(aditivo) {
        this.infoAditivo = aditivo;
        this.modalInfoAditivo = true;
        this.carregarImagens();
    },

    setModalImage(src) {
        this.modalImageSrc = src;
        this.modalImage = true;
    },

    abrirArquivo(src) {
        window.open(src, '_blank');
    },

    closeModal(wire) {
        wire().inputs.titulo = null;
        wire().inputs.descricao = null;
        wire().inputsImages = [];

        this.infoAditivo = null;

        wire().editId = null;
        wire().modal = false;
    },

    setEditModal(aditivo, wire) {
        wire().inputs.titulo = aditivo.titulo;
        wire().inputs.descricao = aditivo.descricao;

        this.infoAditivo = aditivo;

        wire().editId = aditivo.id;
        wire().modal = true;
        this.carregarImagens();
    },

    excluirAditivo(aditivo, wire) {
        this.$store.dialog.show(
            'Excluir aditivo',
            `Você realmente deseja excluir esse aditivo?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => wire().excluirAditivo(aditivo.id)
                }
            }
        )
    },

    exclurImagem(imagemId, wire) {
        this.$store.dialog.show(
            'Excluir arquivo',
            `Você realmente deseja excluir esse arquivo?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => wire().excluirImagem(imagemId)
                }
            }
        )
    }
}));
