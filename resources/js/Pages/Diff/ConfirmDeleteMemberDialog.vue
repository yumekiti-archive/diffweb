<template>
    <jet-dialog-modal :show="show && user" @close="$emit('close')">
        <template #title>
            メンバー<span v-if="user">{{ user.user_name }}</span>を除名します。
        </template>
        <template #content>
            <p v-if="user">
            本当にメンバー{{ user.user_name }}を除名しますか？。
            除名する場合はパスワードを入力してください。
            </p>
        <div>
        <jet-input type="password" class="mt-1 block w-3/4" placeholder="パスワード"
            ref="password" v-model="password" />
            </div>
        </template>
        <template #footer>
            <jet-secondary-button @click.native="$emit('close')">キャンセル</jet-secondary-button>
            <jet-danger-button class="ml-2" @click.native="$emit('delete', { user, password})">
                除名
            </jet-danger-button>
        </template>
    </jet-dialog-modal>
</template>
<script>
import JetDialogModal from '@/Jetstream/DialogModal';
import JetDangerButton from '@/Jetstream/DangerButton';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
import JetInput from '@/Jetstream/Input';

export default {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        user: {
            type: Object,
        }
    },
    components: {
        JetDialogModal,
        JetDangerButton,
        JetSecondaryButton,
        JetInput
    },
    data(){
        return {
            password: ''
        }
    }
}
</script>