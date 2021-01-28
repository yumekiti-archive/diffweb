<template>
    <div>
        <select @change="onChange" :value="value">
            <option v-for="select in selection" :key="select.id" :value="select.value">{{ select.name }}</option>
        </select>
    </div>
</template>
<script>
let selection = [
            {id: 0, name: '管理者', value:0},
            {id: 1, name: '読み書き可能', value:1},
            {id: 2, name:'読み込みのみ', value:2}
        ];

export default {
    props: {
        value: {
            type: Number
        }
    },
    data() {
        return { selection: [...selection]};
    },
    methods: {
        onChange(e) {
            this.$emit('change', e.target.value);
        }
    }
}

</script>