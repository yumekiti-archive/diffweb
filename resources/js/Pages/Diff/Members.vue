<template>
    <app-layout>
        <template #header>

        </template>
        <d-card-content>
            <d-diff-nav :diff="diff" />
            <div>
                <div v-for="member in users.data" :key="member.id" class="flex items-center justify-between rounded">
                    
                    <div>{{ member.user_name }}</div>
                    <div>
                        <button>除名</button>
                    </div>
                </div>
            </div>
            <div class="mt-3 -mb-1 flex flex-wrap" v-for="(link, key) in users.links" :key="key">

                <!-- 適当UIですまんなんとかして-->
                <div v-if="link.url !== null">
                    <inertia-link :href="link.url">{{ link.label }}</inertia-link>
                </div>
                
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