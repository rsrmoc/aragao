Alpine.data('etapasTabEvolucoes', () => ({
    infoEvolucao: null,
    modalInfoEvolucao: false,
    modalImageSrc: null,
    modalImage: null,

    carregarImagens() {
        document.querySelector('div.app-loading').classList.remove('hidden');
        let imagens = this.infoEvolucao.imagens;
        this.infoEvolucao.imagens = [];

        // Mapear todas as imagens para uma lista de promessas de chamadas axios
        const promises = imagens.map(imagem => {
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
                // Atualizar a imagem no array imagens
                const index = imagens.findIndex(img => img.id === imagemAtualizada.id);
                if (index !== -1) {
                    imagens[index] = imagemAtualizada;
                }
            });
        });

        // Aguardar todas as promessas serem resolvidas
        this.infoEvolucao.imagens = imagens;
        Promise.all(promises).then(() => {
            document.querySelector('div.app-loading').classList.add('hidden');
        });
    },
    

    setInfoEvolucao(evolucao) {
        this.infoEvolucao = evolucao;
        this.modalInfoEvolucao = true;
        this.carregarImagens();
    },

    setModalImage(src) {
        this.modalImageSrc = src;
        this.modalImage = true;
    },

    closeModal(wire) {
        wire().inputs.id_etapa = null;
        wire().inputs.dt_evolucao = null;
        wire().inputs.descricao = null;
        wire().inputsImages = [];

        this.infoEvolucao = null;

        wire().editId = null;
        wire().modal = false;
    },

    setEditModal(evolucao, wire) {
        wire().inputs.id_etapa = evolucao.id_etapa;
        wire().inputs.dt_evolucao = evolucao.dt_evolucao;
        wire().inputs.descricao = evolucao.descricao;

        this.infoEvolucao = evolucao;

        wire().editId = evolucao.id;
        wire().modal = true;
        this.carregarImagens();
    },

    exclurEvolucao(evolucao, wire) {
        this.$store.dialog.show(
            'Excluir evolução',
            `Você realmente deseja excluir essa evolução?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => wire().excluirEvolucao(evolucao.id)
                }
            }
        )
    },

    exclurImagem(imagemId, wire) {
        this.$store.dialog.show(
            'Excluir imagem',
            `Você realmente deseja excluir essa imagem?`,
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