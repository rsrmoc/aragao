Alpine.data('etapasTabUsuarios', () => ({
    delUser(obraUsuario, $wire) {
        this.$store.dialog.show(
            'Desatribuir',
            `Quer mesmo excluir o "${obraUsuario.usuario.name}"?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().delUser(obraUsuario.id)
                }
            }
        )
    }
}));