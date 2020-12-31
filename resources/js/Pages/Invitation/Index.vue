<template>
<app-layout>
    <template #header>
        招待一覧
    </template>
    <card-content>
        <div 
            v-for="invitation in invitations.data" :key="invitation.id"
            class=" border-b py-2 pl-4 pr-4"
        >
            <div>
                {{ invitation.author.user_name }}
                からの招待
            </div>

            <div 
                class="flex items-center justify-between"
            >
                <div>
                    {{ invitation.diff.title }}

                </div>
                
                <div>
                    <button @click="reject(invitation)" class="mr-2">
                        辞退
                    </button>
                    <button @click="accept(invitation)">
                        承諾
                    </button>
                </div>
            </div>
        </div>

    </card-content>
    <pagination-links :links="invitations.links" />
</app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout';
import CardContent from './../../Templetes/CardContent';
import PaginationLinks from './../../Components/Pagination';


export default {
    layout: AppLayout,
    components: {
        CardContent,
        PaginationLinks
    },
    props: {
        invitations: {
            type: Object,
            required: true
        },
        
    },
    methods: {
        accept(invitation){
            this.$inertia.post(route('invitations.accept', invitation.id));
        },
        reject(invitation){
            this.$inertia.post(route('invitations.reject', invitation.id));
        }
    }
}
</script>