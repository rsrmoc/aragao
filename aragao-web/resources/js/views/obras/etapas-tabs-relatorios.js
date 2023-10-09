Alpine.data('etapasTabRelatorios', () => ({
    excluir(idRelatorio, $wire) {
        this.$store.dialog.show(
            'Excluir relatório',
            'Realmente deseja excluir o relatório?',
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().excluirRelatorio(idRelatorio)
                }
            }
        )
    }
}));