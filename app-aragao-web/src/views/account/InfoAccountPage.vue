<script setup lang="ts">
import {
    IonContent, IonHeader, IonToolbar, IonTitle, IonButtons, IonBackButton,
    IonItem, IonLabel
} from '@ionic/vue';
</script>

<template>
    <ion-header>
        <ion-toolbar>
            <ion-buttons slot="start">
                <ion-back-button ></ion-back-button>
            </ion-buttons>

            <ion-title>Informações da conta</ion-title>
        </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
        <ion-item lines="full">
            <div slot="start">
                <strong>Nome:</strong>
            </div>
            <ion-label>Gean Lima</ion-label>
        </ion-item>

        <ion-item lines="full">
            <div slot="start">
                <strong>Email:</strong>
            </div>
            <ion-label>geanlimasp12345@gmail.com</ion-label>
        </ion-item>

        <ion-item lines="full">
            <div slot="start">
                <strong>Conta criada:</strong>
            </div>
            <ion-label>12/11/2023</ion-label>
        </ion-item>
    </ion-content>
</template>