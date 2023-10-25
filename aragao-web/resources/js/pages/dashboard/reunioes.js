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
        negada: 'badge-error',
        conteudo_pendente: 'badge-warning',
    },

    closeModal() {
        let component = Livewire.first();

        component.inputs.id_obra = null;
        component.inputs.assunto = null;
        component.inputs.dt_reuniao = null;
        component.inputs.descricao = null;

        this.infoReuniao = null;

        component.reuniaoIdEdit = null;
        component.modal = false;
    },

    closeConteudo() {
        let component = Livewire.first();

        component.modalConteudo = false;
        component.inputConteudoReuniao = null;
    },

    closeInfo() {
        let component = Livewire.first();

        component.modalInfo = false;
        component.reuniaoIdEdit = null;

        this.infoReuniao = null;
    },

    setEditConteudoReuniao() {
        let component = Livewire.first();

        component.reuniaoIdEdit = this.infoReuniao.id;
        component.inputConteudoReuniao = this.infoReuniao.conteudo;
        component.modalConteudo = true;
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

        this.infoReuniao = reuniao;

        component.reuniaoIdEdit = reuniao.id;
        component.modal = true;
    },
    reuniaoConcluida() {
        let component = Livewire.first();

        component.confirmarConteudo(this.infoReuniao.id);
    },
    reuniaoCancelada() {
        let component = Livewire.first();

        this.$store.dialog.show(
            'Cancelar reunião',
            'Você realmente deseja cancelar essa reunião?\nEssa ação não pode ser desfeita.',
            {
                cancel: {},
                confirm: {
                    text: 'Sim, cancelar!',
                    action: () => component.reuniaoSituacao('cancelada')
                }
            }
        );
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
            engineer: 'Profissional'
        }

        let status = {
            agendada: 'agendou a reunião.',
            confirmada: 'confirmou presença.',
            adiada: 'adiou a reunião.',
            cancelada: 'cancelou a reunião.',
            concluida: 'marcou a reunião como concluída.',
            negada: 'não participará da reunião.',
            conteudo_pendente: 'informou o conteúdo da reunião.',
            conteudo_confirmado: 'confirmou o conteúdo da reunião.'
        };

        return `<strong>${ historico.usuario.name } (${ typesUsers[historico.usuario.type] })</strong> ${ status[historico.situacao] }`;
    }
}));