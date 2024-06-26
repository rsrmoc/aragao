Alpine.data('obrasPage', () => ({
    closeModal($wire) {
        $wire().inputsAdd.nome = null;
        $wire().inputsAdd.dt_inicio = null;
        $wire().inputsAdd.dt_termino = null;
        $wire().inputsAdd.dt_previsao_termino = null;
        $wire().inputsAdd.endereco_rua = null;
        $wire().inputsAdd.endereco_bairro = null;
        $wire().inputsAdd.endereco_numero = null;
        $wire().inputsAdd.endereco_cidade = null;
        $wire().inputsAdd.endereco_uf = null;
        $wire().inputsAdd.endereco_cep = null;
        $wire().inputsAdd.tipo_recurso = null;
        $wire().inputsAdd.descricao_completa = null;
        $wire().inputsAdd.valor = null;

        $wire().obraIdEdit = null;
        $wire().modal = false;
    },

    deleteObra(obra, $wire) {
        this.$store.dialog.show(
            `Excluir obra`,
            `Você realmente deseja excluir a obra "${obra.nome}"?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().delObra(obra.id)
                }
            }
        )
    },

    setEditModal(obra, $wire) {
        $wire().inputsAdd.nome = obra.nome;
        $wire().inputsAdd.dt_inicio = obra.dt_inicio;
        $wire().inputsAdd.dt_termino = obra.dt_termino;
        $wire().inputsAdd.dt_previsao_termino = obra.dt_previsao_termino;
        $wire().inputsAdd.endereco_rua = obra.endereco_rua;
        $wire().inputsAdd.endereco_bairro = obra.endereco_bairro;
        $wire().inputsAdd.endereco_numero = obra.endereco_numero;
        $wire().inputsAdd.endereco_cidade = obra.endereco_cidade;
        $wire().inputsAdd.endereco_uf = obra.endereco_uf;
        $wire().inputsAdd.endereco_cep = obra.endereco_cep;
        $wire().inputsAdd.tipo_recurso = obra.tipo_recurso;
        $wire().inputsAdd.descricao_completa = obra.descricao_completa;
        $wire().inputsAdd.valor = Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(obra.valor);

        $wire().obraIdEdit = obra.id;
        $wire().modal = true;
    }
}));