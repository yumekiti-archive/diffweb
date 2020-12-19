<template>
    <div>
        <!--成功したことを表すメッセージsuccessフラッシュを表示します。-->
        <div v-if="$page.props.flash.success && isShow" class="mb-8 flex items-center justify-between bg-green-500 rounded max-w-3xl">
            <div class="flex items-cneter">
                <i class="ml-4 mr-2 flex-shrink-0 w-4 h-4 fill-white fas fa-check"></i>
                <div class="py-4 text-white text-sm font-midium">
                    <!-- success なフラッシュメッセージを表示します。 -->
                    {{ $page.props.flash.success}}
                </div>
            </div>
            <!-- フラッシュメッセージを非表示にします。 -->
            <button type="button" class="group mr-2 p-2" @click="show = false">
                <i class="block w-2 h-2 fill-green-800 group-hover:fill-white fas fa-times"></i>
            </button>
        </div>

        <!--失敗したことを表すerrorフラッシュを表示します。-->
        <div v-if="$page.flash.error || (Object.keys($page.errors).length > 0) && isShow" class="mb-8 flex items-cetener justify-between bg-red-500 rounded max-w-3xl">
            <div class="flex items-center">
                <i class="ml-4 mr-2 flex-shrink-0 w-4 h-4 fill-white fas fa-exclamation-circle"></i>
                <div v-if="$page.flash.error" class="py-4 text-white text-sm font-medium">{{ $page.flash.error }}</div>
                <div v-else class="py-4 text-white text-sm font-medium">
                    <span v-if="Object.keys($page.errors).length === 1">エラー</span>
                    <span v-else>There are {{ Object.keys($page.errors).length }}件のエラー</span>
                </div>
            </div>
            <!-- フラッシュメッセージを非表示にします。 -->
            <button type="button" class="group mr-2 p-2" @click="show = false">
                <i class="block w-2 h-2 fill-green-800 group-hover:fill-white fas fa-times"></i>
            </button>
        </div>
    </div>
</template>
<script>
/**
フラッシュメッセージの表示を担当します。
 */
export default {
    data(){
        return {
            isShow: true
        }
    },
    watch: {
        '$page.flash': {
            handler(){
                this.isShow = true;
            },
            deep: true
        }
    }
}
</script>