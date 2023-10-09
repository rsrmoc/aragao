<script setup lang="ts">
import { markRaw } from 'vue';
import {
    IonPage, IonContent, IonAvatar, IonRow, IonButton, IonIcon,
    IonList, IonItem, IonLabel, IonNavLink
} from '@ionic/vue';
import HeaderLogo from '@/components/app/HeaderLogo.vue';
import { cameraOutline, logOutOutline, personCircleOutline, lockClosedOutline } from 'ionicons/icons';
import InfoAccountPage from './InfoAccountPage.vue';
import PasswordPage from './PasswordPage.vue';
</script>

<template>
    <ion-page>
        <header-logo />

        <ion-content class="ion-padding">
            <ion-row style="justify-content: center;">
                <div style="text-align: center;">
                    <ion-avatar style="width: 120px; height: 120px;">
                        <img src="https://ionicframework.com/docs/img/demos/avatar.svg" />
                    </ion-avatar>
                    <ion-button size="small" fill="outline">
                        <ion-icon :icon="cameraOutline" />
                    </ion-button>
                </div>
            </ion-row>

            <h1 style="text-align: center;">Gean Lima</h1>

            <div style="margin-top: 28px;"></div>

            <ion-list>
                <ion-nav-link :component="markRaw(InfoAccountPage)">
                    <ion-item button>
                        <ion-icon slot="start" :icon="personCircleOutline" />
                        <ion-label>Informações da conta</ion-label>
                    </ion-item>
                </ion-nav-link>

                <ion-nav-link :component="markRaw(PasswordPage)">
                    <ion-item button>
                        <ion-icon slot="start" :icon="lockClosedOutline" />
                        <ion-label>Alterar senha</ion-label>
                    </ion-item>
                </ion-nav-link>

                <ion-item button>
                    <ion-icon slot="start" :icon="logOutOutline" />
                    <ion-label>Sair</ion-label>
                </ion-item>
            </ion-list>
        </ion-content>
    </ion-page>
</template>