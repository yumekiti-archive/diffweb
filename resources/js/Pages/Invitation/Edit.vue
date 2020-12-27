<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        <card-content>
            <item-user v-for="user in users.data" :key="user.id" :user="user">
                <button>
                    <i class="fas fa-user-plus" />
                    招待
                </button>
            </item-user>
        </card-content>
        <pagination :links="users.links" class="mt-4" />
    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout';
import CardContent from '../../Templetes/CardContent';
import DiffNav from '../Diff/DiffNav';
import Pagination from '../../Components/Pagination';
import ItemUser from '../../Components/ItemUser';

export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav,
        Pagination,
        ItemUser
    },
    props: {
        diff: {
            type: Object,
            required: true,

        },
        users: {
            type: Object,
            required: true
        }
    },
    methods: {
        confirmInvite(user){

        },

        invite(user){
            
        }
    }    
}
</script>