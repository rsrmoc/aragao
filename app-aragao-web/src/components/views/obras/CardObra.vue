<script setup lang="ts">
import { markRaw } from 'vue';
import {
    IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent,
    IonItem, IonLabel, IonThumbnail, IonChip, IonAvatar, IonNavLink
} from '@ionic/vue';
import ObraInfoPage from '../../../views/obras/ObrasInfoPage.vue';
</script>

<template>
    <ion-nav-link router-direction="forward" :component="markRaw(ObraInfoPage)">
        <ion-card button class="cardObra">
            <ion-card-header>
                <ion-card-title>Obra Teste</ion-card-title>
                <ion-card-subtitle>Início da obra: 11/11/2021</ion-card-subtitle>
            </ion-card-header>

            <ion-card-content>
                <ion-item lines="none">
                    <ion-thumbnail slot="start">
                        <img src="https://ajuda.globaltec.com.br/wp-content/uploads/2019/05/shutterstock_227521099-C%C3%B3pia.jpg"
                            alt="Imagem da obra">
                    </ion-thumbnail>
                    <ion-label>
                        <p>Ultima etapa</p>
                        <h4>Descrição da ultima etapa registrada</h4>
                    </ion-label>
                </ion-item>
            </ion-card-content>

            <ion-chip class="containerResponsavel">
                <ion-avatar>
                    <img src="https://ajuda.globaltec.com.br/wp-content/uploads/2019/05/shutterstock_227521099-C%C3%B3pia.jpg"
                        alt="Imagem da obra">
                </ion-avatar>

                <ion-label>
                    <h5>João Henrique</h5>
                    <p>Responsável</p>
                </ion-label>
            </ion-chip>

            <div class="containerStatus">
                <ion-chip color="warning">Em Andamento</ion-chip>
            </div>
        </ion-card>
    </ion-nav-link>
</template>

<style lang="scss" scoped>
.cardObra {
    position: relative;
    box-shadow: 0 2px 4px rgba(82, 82, 82, 0.3);
    border-bottom: 4px solid #225A82;

    .containerStatus {
        position: absolute;
        top: 10px;
        right: 10px;

        ion-chip {
            font-size: .8rem;
        }
    }

    ion-thumbnail {
        --border-radius: 6px;
    }

    .containerResponsavel {
        background: white;

        p {
            font-size: .6rem;
        }
    }
}
</style>