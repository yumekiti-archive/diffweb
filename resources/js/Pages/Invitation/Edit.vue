<template>
    <app-layout>
        <template #header>
            <diff-nav :diff="diff"></diff-nav>

        </template>
        {{ users}}
    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout';
import CardContent from '../../Templetes/CardContent';
import DiffNav from '../Diff/DiffNav';

export default {
    components: {
        AppLayout,
        CardContent,
        DiffNav
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
    }    
}
</script>