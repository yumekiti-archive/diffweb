<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        <card-content>

        </card-content>
        <pagination class="mt-4" :links="invitations.links" />
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout';
import CardContent from './../../Templetes/CardContent';
import DiffNav from './DiffNav';
import ItemUser from '../../Components/ItemUser';
import Pagination from '../../Components/Pagination';

export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav,
        ItemUser,
        Pagination

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
    }
}
</script>