<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                
                    <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                        <label class="text-gray-600 font-light">タイトル</label>
                        <input type='text' placeholder="Enter your input here" class="w-full mt-2 mb-6 px-6 py-3 border rounded-lg text-lg text-gray-700 focus:outline-none" v-bind="title"/>
                        <p>二つのテキストの差分を表示します。</p>
                        <div class="flex">
                            <div class="w-full w-1/2 pr-2">
                                <textarea v-model="form.sourceText" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none"></textarea>
                                <text-result :isAdded="true" :result="compared"></text-result>
                            </div>
                            <div class="w-full w-1/2 pl-2">
                                <textarea v-model="form.comparedText" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none"></textarea>
                                <text-result :isRemoved="true" :result="compared"></text-result>
                            </div>

                        </div>
                        <div class="mt-6">
                            <div>
                                <div v-if="locked">{{ diff.lockd_user.user_name }}によってロックされています。</div>
                            </div>
                            <button type="button" class="bg-blue-600 text-gray-200 rounded hover:bg-blue-500 px-4 py-2 focus:outline-none" @click="trySave">保存</button>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
const Diff = require('diff');
import AppLayout from '@/Layouts/AppLayout';
import TextDiffResult from './TextDiffResult';

export default {
    props: {
        diff: {
            type: Object,
            required: false,
            default: null
        },
        me: {
            type: Object,
            required: true,
        }
    },

    data(){
        return {
            form: {
                sourceText: '',
                comparedText: '',
                title: ''
            },
        }
    },
    components: {
        'app-layout': AppLayout,
        'text-result':TextDiffResult
    },

    computed: {
        compared(){
            if(this.form.sourceText !=null && this.form.comparedText != null){
                let diffs = Diff.diffChars(this.form.sourceText, this.form.comparedText);
                console.log(diffs);
                return diffs;
            }
        },
        locked(){
            return this.diff != null && this.diff.locked_user != null && this.diff.locked_user.id !== this.me.id;
        }
    },

    created(){
        if(this.diff != null ){
            this.form.sourceText = this.diff.source_text;
            this.form.comparedText = this.diff.compared_text;
            this.form.title = this.diff.title;
        }
    },

    methods: {
        trySave(){
            if(this.locked){
                if(this.diff == null){
                    this.$inertia.post(this.route('diffs.create'), this.form);
                }else{
                    this.$inertia.post(this.route('diffs.update', this.diff.id), this.form);
                }
            }
        }
    }
    
}
</script>
<style scoped>


.area1{
    background-color: #e2e2e2;
}
.area2{
    background-color: #c4c4c4;
}


</style>