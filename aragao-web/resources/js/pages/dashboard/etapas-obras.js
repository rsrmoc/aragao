Alpine.data('etapasObra', () => ({
    tab: 2,
    closeModal($wire) {
        $wire().inputsEtapa.nome = null;
        $wire().inputsEtapa.porc_etapa = 0;
        $wire().inputsEtapa.porc_geral = 0;
        $wire().inputsEtapa.concluida = false;

        $wire().etapaIdEdit = false;
        $wire().modal = false;
    },

    setEdit(etapa, $wire) {
        $wire().inputsEtapa.nome = etapa.nome;
        $wire().inputsEtapa.porc_etapa = etapa.porc_etapa;
        $wire().inputsEtapa.porc_geral = etapa.porc_geral;
        $wire().inputsEtapa.concluida = etapa.concluida;

        $wire().etapaIdEdit = etapa.id;
        $wire().modal = true;
    },

    excluirEtapa(etapaId, $wire) {
        this.$store.dialog.show(
            'Excluir etapa',
            'Realmente deseja excluir a etapa?',
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().delEtapa(etapaId)
                }
            }
        );
    }
}));