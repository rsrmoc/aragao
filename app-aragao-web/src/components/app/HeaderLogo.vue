<script setup lang="ts">
import { IonHeader, IonToolbar, IonTitle, IonThumbnail } from '@ionic/vue';
</script>

<template>
    <ion-header id="appHeader" class="ion-no-border">
      <ion-toolbar>
        <ion-title id="appHeaderTitle">
          <ion-thumbnail>
            <img src="/logo_square.webp" alt="Logo Aragão Construtora" />
          </ion-thumbnail>
        </ion-title>
      </ion-toolbar>
    </ion-header>
</template>

<style lang="scss" scoped>
#appHeader {
  box-shadow: 0 2px 3px rgb(73, 73, 73, 0.2);

  &.ion-no-border.ios.header-ios.header-collapse-none.header-collapse-main.header-collapse-condense-inactive {
    box-shadow: none;

    #appHeaderTitle {
      background-color: transparent !important;
    }
  }

  #appHeaderTitle {
    background-color: white !important;
  
    ion-thumbnail {
      margin: auto;
    }
  }
}
</style>