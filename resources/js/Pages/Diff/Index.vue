<template>
  <app-layout>
    <template #header>
      <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        <inertia-link href="/create">新規作成</inertia-link>
      </h1>
    </template>
    <div class="font-semibold text-xl text-gray-800 leading-tight text-center">
      diff一覧
    </div>
  </app-layout>
</template>

<script>

  import AppLayout from '@/Layouts/AppLayout'

  export default {
    components: {
    AppLayout,
    },
    props: ['me']
  }

</script>
