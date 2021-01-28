<template>
    <app-layout>
        <template #header>
            <d-diff-nav :diff="diff" :member="member" v-if="member"/>

        </template>

        <d-card-content>
            <div>
                <item-user v-for="member in members.data" :key="member.id" :user="member.user">
                    
                    <div>
                        <button @click="confirmDeleteMember(member.user)">
                            <i class="fas fa-user-times"></i>
                            除名
                        </button>
                    </div>
                </item-user>
            </div>
            <confirm-delete-member-dialog 
                :user="confirmMemberDelection.member"
                :show="confirmMemberDelection.isShow"
                @close="isShow = false"
                @delete="deleteMember"
            />


        </d-card-content>
        <pagination class="mt-4" :links="members.links" />


    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout';
import CardContent from '../../Templetes/CardContent';
import DiffNav from './DiffNav';
import JetDialogModal from '@/Jetstream/DialogModal';
import JetDangerButton from '@/Jetstream/DangerButton';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
import JetInput from '@/Jetstream/Input';
import ItemUser from '../../Components/ItemUser';
import Pagination from '../../Components/Pagination';
import ConfirmDeleteMemberDialog from './ConfirmDeleteMemberDialog';

export default {
    props:{
        members: Object,
        diff: Object,
        member: Object
    },
    data(){
        return {
            confirmMemberDelection: {
                isShow: false,
                member: null

            },
            form: {
                password: ''
            }
        }
    },
    components: {
        'app-layout': AppLayout,
        'd-card-content': CardContent,
        'd-diff-nav': DiffNav,
        JetDialogModal,
        JetDangerButton,
        JetSecondaryButton,
        JetInput,
        ItemUser,
        Pagination,
        ConfirmDeleteMemberDialog

    },
    methods: {
        deleteMember(res){
            this.confirmMemberDelection.isShow = false;
            this.$inertia.post(route('diffs.members.destroy', {
                'diffId': this.diff.id,
                'userId': res.user.id
            }), 
            {
                    password: res.password
            });
        },
        confirmDeleteMember(user){
            this.confirmMemberDelection.isShow = true;
            this.confirmMemberDelection.member = user;
            this.form.password = '';
        },

        cancelMemberDelection(){
            this.confirmMemberDelection.isShow =false;
            this.form.password = '';
        }
    }
}
</script>