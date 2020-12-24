<template>
    <div class="flex items-center justify-between border-t py-2 pl-4 pr-4">
                    
        <div>{{ user.user_name }}</div>
        <div>
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    }
}
</script>