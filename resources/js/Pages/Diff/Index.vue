<template>
<app-layout>
    <template #header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Diff List
                
            </h2>
            <div class="text-base text-right text-white justify-center rounded-full hover:bg-blue-500 bg-blue-600 p-2">
                <inertia-link href="/create" >新規作成</inertia-link>
            </div>
        </div>
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
                <tr class="text-left text-lg border-t" height="60" v-for="diff in diffs.data" :key="diff.id">
                    <td width="800 border-r">
                            <inertia-link :href="`/diffs/${diff.id}`">{{diff.title}}</inertia-link>
                    </td>
                    <td class="text-center border-r" width="200">{{diff.created_at | moment}}</td>
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
