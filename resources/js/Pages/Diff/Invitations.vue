<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        <card-content>
            <item-user v-for="user in users.data" :key="user.id" :user="user">
                <button @click="confirmCancelInvitation(user)">
                    <i class="fas fa-minus"></i>
                        取り下げ
                </button>
            </item-user>
        </card-content>
        <pagination class="mt-4" :links="users.links"/>
        <jet-dialog-modal :show="isShow" @close="isShow = false">
            <template #title>
                招待を取り下げます
            </template>
            <template #content>
                <span v-if="targetUser">
                    {{ targetUser.user_name}}
                    への招待を取り下げますか？
                </span>
            </template>
            <template #footer>
                <button @click="isShow = false">キャンセル</button>
                <button @click="cancelInvitation(targetUser)">取り下げます</button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout';
import CardContent from './../../Templetes/CardContent';
import DiffNav from './DiffNav';
import ItemUser from '../../Components/ItemUser';
import Pagination from '../../Components/Pagination';
import JetDialogModal from '@/Jetstream/DialogModal';

export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav,
        ItemUser,
        Pagination,
        JetDialogModal

    },
    props: {
        diff: {
            type: Object,
            required: true
        },
        users: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            targetUser: null,
            isShow: false
        }
    },
    methods: {
        cancelInvitation(user){
            this.isShow = false;
            this.$inertia.delete(route('diffs.invitations.cancel', {
                'diffId': this.diff.id,
                'userId': user.id
            }), );
        },
        confirmCancelInvitation(user){
            this.targetUser = user;
            this.isShow = true;
        }
    }
}
</script>