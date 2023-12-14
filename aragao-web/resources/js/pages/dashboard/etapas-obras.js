Alpine.data('etapasObra', () => ({
    tab: 1,
    closeModal($wire) {
        $wire().inputsEtapa.nome = null;
        $wire().inputsEtapa.porc_etapa = 0;
        $wire().inputsEtapa.porc_geral = 0;
        $wire().inputsEtapa.concluida = false;
        $wire().inputsEtapa.dt_inicio = null;
        $wire().inputsEtapa.dt_previsao = 0;
        $wire().inputsEtapa.dt_termino = 0;
        $wire().inputsEtapa.dt_vencimento = false;
        $wire().inputsEtapa.quitada = false;
        $wire().inputsEtapa.descricao_completa = false;

        $wire().etapaIdEdit = false;
        $wire().modal = false;
    },

    setEdit(etapa, $wire) {
        $wire().inputsEtapa.nome = etapa.nome;
        $wire().inputsEtapa.porc_etapa = etapa.porc_etapa;
        $wire().inputsEtapa.porc_geral = etapa.porc_geral;
        $wire().inputsEtapa.concluida = etapa.concluida;
        $wire().inputsEtapa.dt_inicio = etapa.dt_inicio;
        $wire().inputsEtapa.dt_previsao = etapa.dt_previsao;
        $wire().inputsEtapa.dt_termino = etapa.dt_termino;
        $wire().inputsEtapa.dt_vencimento = etapa.dt_vencimento;
        $wire().inputsEtapa.quitada = etapa.quitada;
        $wire().inputsEtapa.descricao_completa = etapa.descricao_completa;

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