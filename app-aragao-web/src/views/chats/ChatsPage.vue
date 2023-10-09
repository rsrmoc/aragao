<script setup lang="ts">
import { IonPage, IonContent, IonSegment, IonSegmentButton } from '@ionic/vue';
import HeaderLogo from '@/components/app/HeaderLogo.vue';
import ChatSegmentContent from './ChatSegmentContent.vue';
import MeetSegmentContent from './MeetSegmentContent.vue';
import { ref } from 'vue';

const currentTab = ref('chat');

function changeTab() {
    currentTab.value = currentTab.value === 'chat'? 'meet' : 'chat'
}
</script>

<template>
    <ion-page>
        <header-logo></header-logo>

        <ion-content class="ion-padding">
            <ion-segment :value="currentTab" @ion-change="changeTab">
                <ion-segment-button value="chat">Bate-papo</ion-segment-button>
                <ion-segment-button value="meet">Reuniões</ion-segment-button>
            </ion-segment>

            <div style="height: 28px"></div>

            <div>
                <chat-segment-content v-if="currentTab == 'chat'"></chat-segment-content>
                <meet-segment-content v-if="currentTab == 'meet'"></meet-segment-content>
            </div>
        </ion-content>
    </ion-page>
</template>