<script setup lang="ts">
import {
    IonPage, IonContent, IonItem, IonInput, IonIcon, IonButton, IonImg,
    IonRow, IonCol
} from '@ionic/vue';
import { atOutline, lockClosedOutline } from 'ionicons/icons';
</script>

<template>
    <ion-page>
        <ion-content class="ion-padding">
            <ion-row style="height: 100%; align-items: center;">
                <ion-col>
                    <ion-img src="/big_logo.webp" style="width: 200px; margin: auto;" />
    
                    <div style="height: 16px;"></div>
        
                    <ion-item lines="full">
                        <ion-icon slot="start" :icon="atOutline" />
                        <ion-input label="Email" label-placement="floating" type="email" />
                    </ion-item>
        
                    <div style="height: 16px;"></div>
        
                    <ion-item lines="full">
                        <ion-icon slot="start" :icon="lockClosedOutline" />
                        <ion-input label="Senha" label-placement="floating" type="password" />
                    </ion-item>
                    
                    <div style="height: 28px;"></div>
        
                    <ion-button expand="block">Entrar</ion-button>
        
                    <div style="height: 12px;"></div>
        
                    <ion-button fill="clear">Esqueceu a senha?</ion-button>
                </ion-col>
            </ion-row>
        </ion-content>
    </ion-page>
</template>