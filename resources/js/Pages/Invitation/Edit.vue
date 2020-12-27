<template>
    <app-layout>
        <template #header>
            <inertia-link :to="route('diffs.show', diff.id)">{{ diff.title }}</inertia-link>
            / 招待作成
        </template>

    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout';
import CardContent from '../../Templetes/CardContent';

export default {
    components: {
        AppLayout,
        CardContent
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