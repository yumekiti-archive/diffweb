<template>
    <app-layout>
        <template #header>
            <d-diff-nav :diff="diff" :member="member" v-if="member"/>

        </template>

        <div>
            <div>
                <div 
                    v-for="(member,index) in members.data" :key="member.id"
                    class="bg-white py-4 px-6 mb-0.5"
                    :class="{ 'rounded-t': index == 0, 'rounded-b': index == members.data.length - 1}"
                >
                    <div class="text-lg">{{ member.user.user_name }}</div>
                    <div class="flex justify-end">
                        <button class="mr-4 text-gray-800 hover:text-gray-600" @click="showAuthorityDialog(member)">
                            {{ selection[member.authority].name }}
                            
                        </button>
                        <button class="text-gray-800 hover:text-gray-600" @click="confirmDeleteMember(member.user)">
                            除名
                        </button>
                    </div>
                </div>
            
                
            </div>
        </div>
        <confirm-delete-member-dialog 
                :user="confirmMemberDelection.member"
                :show="confirmMemberDelection.isShow"
                @close="isShow = false"
                @delete="deleteMember"
            />
        <jet-dialog-modal
            :show="authorityDialog.show"
            @close="authorityDialog.show = false"
        >
            <template #title>
                権限の変更の確認
            </template>
            <template #content>
                <p v-if="authorityDialog.member">
                    ユーザー{{ authorityDialog.member.user.user_name}}の権限を{{ selection[authorityDialog.before].name }}から<br>
                    <authority-selection v-model="authorityDialog.selected" />
                    へ変更します。


                </p>
            </template>
            <template #footer>
                <button @click="authorityDialog.show = false" class="mr-4">キャンセル</button>
                <button>変更する</button>
            </template>
        </jet-dialog-modal>
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
import AuthoritySelection, { selection } from '../../Components/AuthoritySelection';

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
            authorityDialog: {
                show: false,
                member: null,
                before: null,
                selected: null
            },

            form: {
                password: ''
            },
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
        ConfirmDeleteMemberDialog,
        AuthoritySelection

    },
    computed: {
        selection() {
            return selection();
        }
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
        },

        showAuthorityDialog(member) {
            this.authorityDialog.member = member;
            this.authorityDialog.show = true;
            this.authorityDialog.before = member.authority;
            this.authorityDialog.selected = member.authority;
        },
        changeAuthority() {
            
        }
    }
}
</script>