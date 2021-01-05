<template>
<app-layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Diff List
            <div class="text-base text-xl text-right mr-10">
                <inertia-link href="/create">新規作成</inertia-link>
            </div>
        </h2>
    </template>

    <card-content>
        <table>
            <thead>
                <tr class="text-center text-base">
                    <th>タイトル</th>
                    <th>作成日</th>
                    <th>更新日</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-left text-lg" height="60" v-for="diff in diffs.data" :key="diff.id">
                    <td width="800">
                            <inertia-link :href="`/diffs/${diff.id}`">{{diff.title}}</inertia-link>
                    </td>
                    <td class="text-center" width="200">{{diff.created_at | moment}}</td>
                    <td class="text-center" width="200">{{diff.updated_at | moment}}</td>
                </tr>
            </tbody>
        </table>
    </card-content>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import ItemUser from '../../Components/ItemUser';
import moment from '../../../../node_modules/moment';
import CardContent from '../../Templetes/CardContent';

export default {
    filters: {
        moment(date) {
            return moment(date).format('YYYY年MM月DD日');
        },
    },
    props: ['diffs'],
    components: {
        AppLayout,
        ItemUser,
        CardContent,
    },
}
</script>
