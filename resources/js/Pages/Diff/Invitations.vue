<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        <card-content>
            <item-user v-for="invitation in invitations.data" :key="invitation.id" :user="invitation.invited_partner_user">
                <button @click="confirmCancelInvitation(invitation)">
                    <i class="fas fa-minus"></i>
                        取り下げ
                </button>
            </item-user>
        </card-content>
        <pagination class="mt-4" :links="invitations.links"/>
        <jet-dialog-modal :show="isShow" @close="isShow = false">
            <template #title>
                招待を取り下げます
            </template>
            <template #content>
                <span v-if="targetInvitation">
                    {{ targetInvitation.invited_partner_user.user_name}}
                    への招待を取り下げますか？
                </span>
            </template>
            <template #footer>
                <button @click="isShow = false">キャンセル</button>
                <button @click="cancelInvitation(targetInvitation)">取り下げます</button>
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
        invitations: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            targetInvitation: null,
            isShow: false
        }
    },
    methods: {
        cancelInvitation(invitation){
            this.isShow = false;
            this.$inertia.delete(route('diffs.invitations.cancel', {
                'diffId': invitation.diff_id,
                'invitationId': invitation.id
            }), );
        },
        confirmCancelInvitation(invitation){
            this.targetInvitation = invitation;
            this.isShow = true;
        }
    }
}
</script>