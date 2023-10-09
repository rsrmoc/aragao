Alpine.data('etapasTabFuncionarios', () => ({
    closeModal($wire) {
        $wire().funcionarioEncontrado = null;
        $wire().inputsDisabled = true;
    
        $wire().inputsFuncionario.nome = null;
        $wire().inputsFuncionario.cpf = null;
        $wire().inputsFuncionario.rg = null;
        $wire().inputsFuncionario.telefone = null;
    
        $wire().inputsFuncionarioObra.funcao = null;
        $wire().inputsFuncionarioObra.conselho = null;

        $wire().modal = false;
    },
    delFuncionario(obraFuncionario, $wire) {
        this.$store.dialog.show(
            'Excluir funcionário',
            `Realmente deseja excluir o funcionário "${obraFuncionario.funcionario.nome}" com o cpf "${obraFuncionario.funcionario.cpf}"?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().delFuncionario(obraFuncionario.id)
                }
            }
        )
    }
}));