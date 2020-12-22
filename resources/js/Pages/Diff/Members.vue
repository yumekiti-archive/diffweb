<template>
    <app-layout>
        <template #header>

        </template>
        <d-card-content>
            <d-diff-nav :diff="diff" />
            <div>
                <div v-for="member in users.data" :key="member.id" class="flex items-center justify-between border-t py-2 pl-4 pr-4">
                    
                    <div>{{ member.user_name }}</div>
                    <div>
                        <button @click="confirmDeleteMember(member)">
                            <i class="fas fa-user-times"></i>
                            除名
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-3 -mb-1 flex flex-wrap" v-for="(link, key) in users.links" :key="key">

                <!-- 適当UIですまんなんとかして-->
                <div v-if="link.url !== null">
                    <inertia-link :href="link.url">{{ link.label }}</inertia-link>
                </div>
                
            </div>
            <jet-dialog-modal :show="confirmMemberDelection.isShow && confirmMemberDelection.member" @close="confirmMemberDelection.isShow = false">
                <template #title>
                    メンバー<span v-if="confirmMemberDelection.member">{{ confirmMemberDelection.member.user_name }}</span>を除名します。
                </template>
                <template #content>
                    本当にメンバー{{ confirmMemberDelection.member.user_name }}を除名しますか？。
                    除名する場合はパスワードを入力してください。
                    <div>
                        <jet-input type="password" class="mt-1 block w-3/4" placeholder="パスワード"
                            ref="password" v-model="confirmMemberDelection.password" />
                    </div>
                </template>
                <template #footer>
                    <jet-secondary-button @click.native="cancelMemberDelection">キャンセル</jet-secondary-button>
                    <jet-danger-button class="ml-2" @click.native="deleteMember(confirmMemberDelection.member)">
                        除名
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </d-card-content>

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
export default {
    props:{
        users: Object,
        diff: Object
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
        JetInput

    },
    methods: {
        deleteMember(userId){
            this.confirmMemberDelection.isShow = false;
            this.$inertia.post(route('diffs.members.destroy', {
                'diffId': this.diff.id,
                'userId': userId
            }), 
            {
                    password: this.confirmMemberDelection.password,
            });
        },
        confirmDeleteMember(user){
            this.confirmMemberDelection.isShow = true;
            this.confirmMemberDelection.member = user;
        },

        cancelMemberDelection(){
            this.confirmMemberDelection.isShow =false;
        }
    }
}
</script>