<script setup lang="ts">
import { IonLabel, IonList } from '@ionic/vue';
import ChatItem from '@/components/views/chats/ChatItem.vue';
</script>

<template>
    <div>
        <ion-label>
            <h2>Bate-papo</h2>
            <p>Converse diretamente com o responsável da obra</p>
        </ion-label>
    
        <div style="height: 28px"></div>
    
        <ion-list>
            <chat-item></chat-item>
        </ion-list>
    </div>
</template>