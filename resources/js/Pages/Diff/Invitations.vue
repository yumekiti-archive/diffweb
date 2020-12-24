<template>
    <app-layout>
        <template #header>

        </template>
        <card-content>
            <diff-nav :diff="diff"></diff-nav>

        </card-content>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout';
import CardContent from './../../Templetes/CardContent';
import DiffNav from './DiffNav';

export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav,

    },
    props: {
        diff: {
            type: Object,
            required: true
        }
    }
}
</script>