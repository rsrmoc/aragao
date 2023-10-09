<script setup>
import {
    IonHeader, IonToolbar, IonTitle, IonButtons, IonBackButton, IonContent,
    IonSegment, IonSegmentButton
} from '@ionic/vue';
</script>

<template>
    <ion-header>
        <ion-toolbar>
            <ion-buttons slot="start">
                <ion-back-button></ion-back-button>
            </ion-buttons>

            <ion-title>Obra teste</ion-title>
        </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
        <ion-segment value="info">
            <ion-segment-button value="info">Informações</ion-segment-button>
            <ion-segment-button value="etapas">Etapas</ion-segment-button>
            <ion-segment-button value="relatorios">Relátorios</ion-segment-button>
        </ion-segment>
    </ion-content>
</template>