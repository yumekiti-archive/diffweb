<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        <div class="p-2 mb-4 bg-white rounded-lg">
            <input class="relative w-full  py-2 px-1 rounded-r focus:shadow-outline" autocomplete="off" type="text" name="search" placeholder="ユーザーを検索..." v-model="form.search" />

        </div>
        <card-content>
            <item-user v-for="user in users.data" :key="user.id" :user="user">
                <button v-if="user.is_invited" @click="confirmCancelInvitation(user)">
                    <i class="fas fa-minus"></i>
                        取り下げ
                </button>
                <button v-else-if="user.is_member" @click="confirmDeleteMember(user)">
                    <i class="fas fa-user-times"></i>
                    除名

                </button>
                <button v-else @click="confirmInvite(user)">
                    <i class="fas fa-plus" />
                    招待
                </button>
            </item-user>
        </card-content>
        <pagination class="mt-4" :links="users.links"/>
        
        <simple-dialog 
            v-if="deleteInvitation.user"
            @close="deleteInvitation.isShow = false"
            :show="deleteInvitation.isShow"
            :text="deleteInvitation.user.user_name + 'への招待を取り下げますか？'"
            title="招待の取り下げの確認"
            @ok="cancelInvitation(deleteInvitation.user)"
        />
        <confirm-delete-member-dialog 
            :user="member.user"
            :show="member.isShow"
            @close="member.isShow = false"
            @delete="deleteMember"
        />
         <simple-dialog 
            v-if="invite.user"
            @close="invite.isShow = false"
            :show="invite.isShow"
            :text="invite.user.user_name + 'への招待をしますか？'"
            title="招待の作成の確認"
            @ok="doInvite(targetUser)"
        />
        
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout';
import CardContent from './../../Templetes/CardContent';
import DiffNav from './DiffNav';
import ItemUser from '../../Components/ItemUser';
import Pagination from '../../Components/Pagination';
import SimpleDialog from '../../Components/SimpleDialog';
import ConfirmDeleteMemberDialog from './ConfirmDeleteMemberDialog';
import throttle from 'lodash/throttle'


export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav,
        ItemUser,
        Pagination,
        SimpleDialog,
        ConfirmDeleteMemberDialog

    },
    props: {
        diff: {
            type: Object,
            required: true
        },
        users: {
            type: Object,
            required: true
        },
        user_name: {
            type: String,
            required: false
        },
    },
    data(){
        return {
            deleteInvitation: {
                user: null,
                isShow: false
            },
            invite: {
                user: null,
                isShow: false
            },
            member: {
                user: null,
                isShow: false,

            },
            form: {
                search: this.user_name
            },
        }
    },
    watch: {
        'form.search': throttle(function(){
            this.$inertia.replace(route('diffs.invitations', {
                'diffId': this.diff.id,
                'user_name': this.form.search

            }));
        }, 150)
    },
    methods: {
        cancelInvitation(user){
            this.deleteInvitation.isShow = false;
            this.$inertia.delete(route('diffs.invitations.cancel', {
                'diffId': this.diff.id,
                'userId': user.id
            }), );
        },
        confirmCancelInvitation(user){
            this.deleteInvitation.user = user;
            this.deleteInvitation.isShow = true;
        },

        deleteMember(res){
            this.member.isShow = false;
            this.$inertia.post(route('diffs.members.destroy', {
                'diffId': this.diff.id,
                'userId': res.user.id
            }), 
            {
                password: res.password
            });
        },
        confirmDeleteMember(user){
            this.member.user = user;
            this.member.isShow = true;
        },

        confirmInvite(user){
            console.log('confirm invite');
            this.invite.isShow = true;
            this.invite.user = user;
        },

        doInvite(user){
            this.invite.isShow = false;
            this.$inertia.post(route('diffs.invitations.create', { 'diffId' : this.diff.id, userId: this.invite.user.id}), { });

        }

    }
}
</script>