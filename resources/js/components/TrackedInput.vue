<template>
    <div class="relative w-full">
        <div v-if="dirty" class="absolute novu-right-3 novu-mt-[0.4rem] novu-pointer-events-none">•</div>
        <div class="input-group">
            <input v-bind="$attrs" class="input-text" v-model="value">
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    data() {
        return {
            value: this.$attrs.value ?? '',
        }
    },
    computed: {
        dirty() {
            return this.value != this.$attrs.value
        }
    },
}
</script>