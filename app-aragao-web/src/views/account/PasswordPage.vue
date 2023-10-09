<script setup lang="ts">
import {
    IonContent, IonHeader, IonTitle, IonButtons, IonBackButton, IonToolbar,
    IonInput, IonItem, IonButton
} from '@ionic/vue';
</script>

<template>
    <ion-header>
        <ion-toolbar>
            <ion-buttons slot="start">
                <ion-back-button></ion-back-button>
            </ion-buttons>
            <ion-title>Alterar senha</ion-title>
            <ion-buttons slot="end">
                <ion-button>Salvar</ion-button>
            </ion-buttons>
        </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
        <ion-item>
            <ion-input type="password" label="Senha atual" label-placement="floating"></ion-input>
        </ion-item>

        <ion-item>
            <ion-input type="password" label="Nova senha" label-placement="floating"></ion-input>
        </ion-item>

        <ion-item>
            <ion-input type="password" label="Confirme a nova senha" label-placement="floating"></ion-input>
        </ion-item>
    </ion-content>
</template>