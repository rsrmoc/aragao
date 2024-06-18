Alpine.data('tablesUsers', () => ({
    closeModal($wire) {
        $wire().userName = null;
        $wire().userEmail = null;
        $wire().userPhoneNumber = null;
        $wire().userPassword = null;
        $wire().userEngineerLocation = false;
        $wire().userEngineerAdmin = false

        $wire().set('userIdEdit', null);
        $wire().modalAdd = false;
    },
    deleteUser(id, type, name, $wire) {
        this.$store.dialog.show(
            `Excluir ${type}`,
            `Você realmente deseja excluir o ${type} "${name}"?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => $wire().delUser(id)
                }
            }
        )
    },
    setFormEdit(user, $wire) {
        $wire().userName = user.name;
        $wire().userEmail = user.email;
        $wire().userPhoneNumber = user.phone_number;
        $wire().userEngineerLocation = user.engineer_location;
        $wire().userEngineerAdmin = user.engineer_admin;

        $wire().set('userIdEdit', user.id);
        $wire().modalAdd = true;
    }
}));
