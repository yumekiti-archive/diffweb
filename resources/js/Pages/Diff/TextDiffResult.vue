<template>
    <div class="break-all">
        <span 
            v-for="(part,  index) in result" :key="part.value + index"
        >
                                    

            <span v-if="part.added && isAdded" class="added"> {{ part.value }} </span>          
            <span v-else-if="part.removed && isRemoved" class="removed">
                {{ part.value }}
            </span>                             
            <span v-else>
                {{ part.value}}
            </span>
        </span>
    </div>
</template>
<script>
export default {
    props: {
        isAdded: {
            type: Boolean,
            required: false,
            default: false
        },
        isRemoved: {
            type: Boolean,
            required: false,
            default: false
        },
        result: {
            required: true
        }
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
</style>