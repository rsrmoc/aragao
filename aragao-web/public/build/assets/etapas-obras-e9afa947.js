Alpine.data("etapasObra",()=>({tab:1,closeModal(t){t().inputsEtapa.nome=null,t().inputsEtapa.porc_etapa=0,t().inputsEtapa.porc_geral=0,t().inputsEtapa.dt_inicio=null,t().inputsEtapa.dt_previsao=0,t().inputsEtapa.dt_termino=0,t().inputsEtapa.dt_vencimento=!1,t().inputsEtapa.quitada=!1,t().inputsEtapa.descricao_completa=null,t().inputsEtapa.status="iniciar",t().etapaIdEdit=!1,t().modal=!1},setEdit(t,a){a().inputsEtapa.nome=t.nome,a().inputsEtapa.porc_etapa=t.porc_etapa,a().inputsEtapa.porc_geral=t.porc_geral,a().inputsEtapa.dt_inicio=t.dt_inicio,a().inputsEtapa.dt_previsao=t.dt_previsao,a().inputsEtapa.dt_termino=t.dt_termino,a().inputsEtapa.dt_vencimento=t.dt_vencimento,a().inputsEtapa.quitada=t.quitada,a().inputsEtapa.descricao_completa=t.descricao_completa,a().inputsEtapa.status=t.status,a().etapaIdEdit=t.id,a().modal=!0},excluirEtapa(t,a){this.$store.dialog.show("Excluir etapa","Realmente deseja excluir a etapa?",{cancel:{},confirm:{text:"Sim, excluir!",action:()=>a().delEtapa(t)}})}}));