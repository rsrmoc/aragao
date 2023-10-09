<script setup lang="ts">
import { markRaw } from 'vue';
import { IonLabel, IonItem, IonAvatar, IonBadge, IonNavLink } from '@ionic/vue';
import ChatUser from '@/views/chats/ChatUserPage.vue';
</script>

<template>
    <ion-nav-link :component="markRaw(ChatUser)">
        <ion-item button>
            <ion-avatar slot="start">
                <img
                    src="https://static.vecteezy.com/system/resources/thumbnails/008/442/086/small/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg" />
            </ion-avatar>
    
            <ion-label>
                <h4>José Henrique - Obra Teste</h4>
                <p>Ultima mensaem enviada</p>
            </ion-label>
    
            <ion-badge slot="end">3</ion-badge>
        </ion-item>
    </ion-nav-link>
</template>