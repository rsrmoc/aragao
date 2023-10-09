<script setup lang="ts">
import { IonPage, IonContent, IonHeader, IonToolbar, IonThumbnail } from '@ionic/vue';
import HeaderLogo from '../../components/app/HeaderLogo.vue';
import CardObra from '../../components/views/obras/CardObra.vue';
</script>

<template>
    <ion-page>
        <header-logo></header-logo>

        <ion-content class="ion-padding" :fullscreen="true">
            <ion-header id="headerCondenced" collapse="condense">
                <ion-toolbar>
                    <ion-thumbnail id="headerCondense">
                        <img src="/big_logo.webp" alt="Big Logo Aragão Construtora" />
                    </ion-thumbnail>
                </ion-toolbar>
            </ion-header>

            <h4>Suas obras</h4>
            
            <card-obra v-for="i in new Array(5).fill(null)"></card-obra>
        </ion-content>
    </ion-page>
</template>

<style lang="scss" scoped>
#headerCondenced {
    ion-toolbar {
        --background: transparent !important;
    }

    ion-thumbnail {
        --size: 140px !important;
        margin: auto;
    }
}
</style>