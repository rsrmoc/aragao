<script setup lang="ts">
import {
    IonHeader, IonToolbar, IonButtons, IonBackButton, IonLabel, IonAvatar,
    IonRow, IonFooter, IonSearchbar, IonButton, IonIcon, IonContent
} from '@ionic/vue';
import { imagesOutline, sendOutline } from 'ionicons/icons';
import MessageItem from '@/components/views/chats/MessageItem.vue';
</script>

<template>
    <ion-header>
        <ion-toolbar>
            <ion-buttons slot="start">
                <ion-back-button text></ion-back-button>
            </ion-buttons>

            <ion-row style="align-items: center; gap: 12px" class="ion-margin-start">
                <ion-avatar>
                    <img
                        src="https://static.vecteezy.com/system/resources/thumbnails/008/442/086/small/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg" />
                </ion-avatar>
                <ion-label>
                    <h2>José Henrique</h2>
                    <p>Obra Teste</p>
                </ion-label>
            </ion-row>
        </ion-toolbar>
    </ion-header>

    <ion-content id="contentChat" class="ion-padding">
        <message-item></message-item>

        <message-item class="chatEnd"></message-item>

        <message-item></message-item>
        <message-item></message-item>

        <message-item class="chatEnd"></message-item>
        <message-item class="chatEnd"></message-item>
    </ion-content>

    <ion-footer>
        <ion-toolbar>
            <ion-buttons slot="start" style="margin: auto 0;">
                <ion-button>
                    <ion-icon slot="icon-only" :icon="imagesOutline"></ion-icon>
                </ion-button>
            </ion-buttons>

            <ion-searchbar id="footerChatSearch" placeholder="Mensagem"></ion-searchbar>

            <ion-buttons slot="end" style="margin: auto 0;">
                <ion-button>
                    <ion-icon slot="icon-only" :icon="sendOutline"></ion-icon>
                </ion-button>
            </ion-buttons>
        </ion-toolbar>
    </ion-footer>
</template>

<style lang="scss">
#contentChat {
    --background: url(/chat_bg.webp) !important;
}

ion-content::part(background) {
    background-size: cover;
}

#footerChatSearch {
    padding: 0;

    input {
        padding: 0 12px !important;
    }

    ion-icon {
        display: none !important;
    }
}
</style>