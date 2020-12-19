<template>
    <div>
        <!--成功したことを表すメッセージsuccessフラッシュを表示します。-->
        <div v-if="$page.flash.success && isShow">

        </div>

        <!--失敗したことを表すerrorフラッシュを表示します。-->
        <div>

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