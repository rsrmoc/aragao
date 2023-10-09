<script setup lang="ts">
import {
  IonPage, IonTabs, IonTabBar, IonTabButton, IonLabel, IonIcon, IonRouterOutlet
} from '@ionic/vue';
import { constructOutline, personCircleOutline, chatboxEllipsesOutline } from 'ionicons/icons';
</script>

<template>
  <ion-page>
    <ion-tabs>
      <ion-router-outlet></ion-router-outlet>

      <ion-tab-bar>
        <ion-tab-button tab="obras" href="/home/obras">
          <ion-icon :icon="constructOutline"></ion-icon>
          <ion-label>Obras</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="chats" href="/home/chats">
          <ion-icon :icon="chatboxEllipsesOutline"></ion-icon>
          <ion-label>Chat</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="conta" href="/home/account">
          <ion-icon :icon="personCircleOutline" />
          <ion-label>Conta</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>