<template>
    <div class="flex flex-wrap">
        <template v-for="(link, key) in links">
            <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400" :class="{ 'ml-auto': link.label === 'Next' }">{{ link.label }}</div>
            <inertia-link v-else :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-indigo-400 focus:border-indigo-500 focus:text-white" :class="{ 'bg-indigo-400': link.active, 'ml-auto': link.label === 'Next' }" :href="link.url">{{ link.label }}</inertia-link>
        </template>
    </div>
</template>
<script>
export default {
    props: {
        links: {
            type: Array,
            required: true
        }
    }
}
</script>