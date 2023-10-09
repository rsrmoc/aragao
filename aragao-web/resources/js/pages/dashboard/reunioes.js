import 'moment/dist/locale/pt-br.js';
import moment from "moment";
moment.locale('pt-br');

Alpine.data('pageReunioes', () => ({
    infoReuniao: null,
    classStylesStatus: {
        agendada: 'badge-info',
        confirmada: 'badge-accent',
        adiada: 'badge-warning',
        cancelada: 'badge-error',
        concluida: 'badge-success',
        negada: 'badge-error'
    },

    closeModal() {
        let component = Livewire.first();

        component.inputs.id_obra = null;
        component.inputs.assunto = null;
        component.inputs.dt_reuniao = null;
        component.inputs.descricao = null;

        component.reuniaoIdEdit = null;
        component.modal = false;
    },
    excluir(reuniao) {
        let component = Livewire.first();

        this.$store.dialog.show(
            'Excluir reunião',
            `Realmente deseja excluir a reunião "#${reuniao.id} ${reuniao.assunto}"?`,
            {
                cancel: {},
                confirm: {
                    text: 'Sim, excluir!',
                    action: () => component.excluirReuniao(reuniao.id)
                }
            }
        )
    },
    setEdit(reuniao) {
        let component = Livewire.first();

        component.inputs.id_obra = reuniao.id_obra;
        component.inputs.assunto = reuniao.assunto;
        component.inputs.dt_reuniao = reuniao.dt_reuniao;
        component.inputs.descricao = reuniao.descricao;

        component.reuniaoIdEdit = reuniao.id;
        component.modal = true;
    },
    reuniaoConcluida() {
        let component = Livewire.first();
        component.reuniaoSituacao('concluida');
    },
    reuniaoCancelada() {
        let component = Livewire.first();
        component.reuniaoSituacao('cancelada');
    },
    setInfo(reuniao) {
        let component = Livewire.first();

        this.infoReuniao = reuniao;
        component.modalInfo = true;
    },
    formatDate(date) {
        return moment(date).format('LLL');
    },
    formatHistorico(historico) {
        let typesUsers = {
            admin: 'Administrador',
            client: 'Cliente',
            engineer: 'Engenheiro'
        }

        let status = {
            agendada: 'agendou a reunião.',
            confirmada: 'confirmou presença.',
            adiada: 'adiou a reunião.',
            cancelada: 'cancelou a reunião.',
            concluida: 'marcou a reunião como concluída.',
            negada: 'não participará da reunião.'
        };

        return `<strong>${ historico.usuario.name } (${ typesUsers[historico.usuario.type] })</strong> ${ status[historico.situacao] }`;
    }
}));