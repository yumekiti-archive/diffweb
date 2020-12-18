<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <p class="px-2">二つのテキストの差分を表示します。</p>
                        <div class="flex">
                            <div class="w-full p-2 w-1/2">
                                <textarea v-model="form.sourceText" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none"></textarea>
                                <div class="break-all">
                                    <span 
                                        v-for="(part,  index) in compared" :key="part.value + index"
                                    >
                                        <span v-if="part.added" class="added">

                                        </span>
                                        
                                        <span v-else-if="part.removed" class="removed">
                                            {{ part.value }}
                                        </span>
                                        
                                        <span v-else>
                                            {{ part.value}}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full p-2 w-1/2">
                                <textarea v-model="form.comparedText" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none"></textarea>
                                <div class="break-all">
                                    <span 
                                        v-for="(part,  index) in compared" :key="part.value + index"
                                    >
                                        <span v-if="part.added" class="added">
                                            {{ part.value }}

                                        </span>
                                        
                                        <span v-else-if="part.removed" class="removed">
                                        </span>
                                        
                                        <span v-else>
                                            {{ part.value}}
                                        </span>
                                    </span>
                                </div>
                            </div>

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

export default {
    props: {
        diff: {
            type: Object,
            required: false,
            default: null
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
        'app-layout': AppLayout
    },

    computed: {
        compared(){
            if(this.form.sourceText !=null && this.form.comparedText != null){
                let diffs = Diff.diffChars(this.form.sourceText, this.form.comparedText);
                console.log(diffs);
                return diffs;
            }
        }
    },

    created(){
        /*if(diff != null ){
            this.form.sourceText = this.diff.source_text;
            this.form.comparedText = this.diff.compared_text;
            this.form.title = this.diff.title;
        }*/
    }
}
</script>
<style scoped>
.removed{
    color: #ff7575;
}
.added{
    color: #90ff99
}

.area1{
    background-color: #e2e2e2;
}
.area2{
    background-color: #c4c4c4;
}


</style>