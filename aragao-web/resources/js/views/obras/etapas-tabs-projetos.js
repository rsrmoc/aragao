Alpine.data('etapasTabProjetos', () => ({
    infoProjeto: null,
    modalInfoProjeto: false,
    modalImageSrc: null,
    modalImage: null,

    carregarImagens() {
        // Mapear todas as imagens para uma lista de promessas de chamadas axios
        const promises = this.infoProjeto.imagens.map(imagem => {
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
                // Atualizar a imagem no array this.infoProjeto.imagens
                const index = this.infoProjeto.imagens.findIndex(img => img.id === imagemAtualizada.id);
                if (index !== -1) {
                    this.infoProjeto.imagens[index] = imagemAtualizada;
                }
            });
        });
    },
    

    setInfoProjeto(projeto) {
        this.infoProjeto = projeto;
        this.modalInfoProjeto = true;
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

        this.infoProjeto = null;

        wire().editId = null;
        wire().modal = false;
    },

    setEditModal(projeto, wire) {
        wire().inputs.titulo = projeto.titulo;
        wire().inputs.descricao = projeto.descricao;

        this.infoProjeto = projeto;

        wire().editId = projeto.id;
        wire().modal = true;
        this.carregarImagens();
    },

    excluirProjeto(projeto, wire) {
        this.$store.dialog.show(
            'Excluir projeto',
            `Você realmente deseja excluir esse projeto?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => wire().excluirProjeto(projeto.id)
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