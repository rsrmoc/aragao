Alpine.data('etapasTabEvolucoes', () => ({
    infoEvolucao: null,
    modalInfoEvolucao: false,
    modalImageSrc: null,
    modalImage: null,


    setInfoEvolucao(evolucao) {
        this.infoEvolucao = evolucao;
        this.modalInfoEvolucao = true;

        console.log(evolucao);
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