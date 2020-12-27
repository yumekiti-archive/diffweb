<template>
    <jet-dialog-modal :show="show" @close="$emit('close')">
        <template #title>
            {{ title }}
        </template>
        <template #content>
            {{ text }}
        </template>
        <template #footer>
            <button @click="$emit('close')">いいえ</button>
            <button @click="$emit('ok')">はい</button>
        </template>
    </jet-dialog-modal>
</template>
<script>
import JetDialogModal from '@/Jetstream/DialogModal';

export default {
    props: ['title', 'text', 'show'],
    components: {
        JetDialogModal
    }
}
</script>