<template>
    <div class="chatBubbleContainer">
        <div class="chatBubble">
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sagittis purus ex.</span>
            <span class="chatBubbleTime">12:30</span>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.chatBubbleContainer {
    padding: 8px;

    &.chatEnd .chatBubble {
        margin-left: auto;
        justify-content: flex-end;
        text-align: right;
        background: #e2ffe7;
    }
    
    .chatBubble {
        width: max-content;
        max-width: 70%;
        padding: 8px 12px;
        border-radius: 5px;
        background: #ffffff;
        box-shadow: 0 3px 3px rgba(77, 77, 77, 0.3);

        .chatBubbleTime {
            display: block;
            flex-shrink: 0;
            font-size: .8rem;
            color: #bbbbbb;
        }
    }
}
</style>