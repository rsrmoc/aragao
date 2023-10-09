<script setup lang="ts">
import {
    IonLabel, IonButton, IonIcon, IonList, IonItem, IonChip,
    IonModal, IonHeader, IonToolbar, IonTitle, IonContent, IonButtons
} from '@ionic/vue';
import { todayOutline } from 'ionicons/icons';
import { ref } from 'vue';

const modal = ref();

function cancelModal() {
    modal.value.$el.dismiss(null, 'cancel');
}
</script>

<template>
    <div>
        <ion-label>
            <h2>Reuniões</h2>
            <p>Agende suas reuniões com facilidade</p>
        </ion-label>

        <div style="height: 16px;"></div>

        <ion-button id="openModal" fill="clear" size="small">
            <ion-icon slot="start" :icon="todayOutline"></ion-icon>
            <span>Agendar nova reunião</span>
        </ion-button>

        <div style="height: 16px;"></div>

        <ion-list>
            <ion-item lines="full">
                <ion-label>
                    <h2>Assunto</h2>
                    <p>Uma breve descrião sobre a reunião</p>
                </ion-label>

                <ion-chip slot="end">12 de outubro às 18:30</ion-chip>
            </ion-item>
        </ion-list>

        <ion-modal ref="modal" trigger="openModal">
            <ion-header>
                <ion-toolbar>
                    <ion-buttons slot="start">
                        <ion-button @click="cancelModal">Cancelar</ion-button>
                    </ion-buttons>

                    <ion-title>Agendar reunião</ion-title>

                    <ion-buttons slot="end">
                        <ion-button>Agendar</ion-button>
                    </ion-buttons>
                </ion-toolbar>
            </ion-header>

            <ion-content class="ion-padding">

            </ion-content>
        </ion-modal>
    </div>
</template>