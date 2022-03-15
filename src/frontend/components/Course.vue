<template>
    <div class="group__data">
        {{ (this.items.length > 0 ) ? this.items : '' }}
    </div>
</template>

<script>
export default {
    props: {
        items: {
            type: Array,
            required: true
        }
    }
}
</script>
