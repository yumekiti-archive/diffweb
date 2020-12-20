<template>
    <app-layout>
        <template #header>

        </template>
        <d-card-content>
            <d-diff-nav :diff="diff" />
            <div>
                <div v-for="member in users.data" :key="member.id">
                    {{ member.user_name }}
                </div>
            </div>
            <div>

            </div>
        </d-card-content>
    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout';
import CardContent from '../../Templetes/CardContent';
import DiffNav from './DiffNav';
export default {
    props:{
        users: Object,
        diff: Object
    },
    components: {
        'app-layout': AppLayout,
        'd-card-content': CardContent,
        'd-diff-nav': DiffNav,
    }
}
</script>