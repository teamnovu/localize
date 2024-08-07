<template>
    <div class="relative w-full">
        <div v-if="isDirty" class="absolute novu-right-3 novu-mt-[0.4rem] novu-pointer-events-none" aria-hidden>•</div>
        <div class="input-group">
            <input v-bind="$attrs" v-model="trackedValue" :id="name" :name="name" class="input-text">
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        name: String,
        value: String,
    },
    data() {
        return {
            trackedValue: this.value,
        }
    },
    computed: {
        isDirty() {
            return this.trackedValue != this.value
        }
    },
    watch: {
        isDirty(isDirty) {
            if (isDirty) this.$dirty.add(this.name);
            else this.$dirty.remove(this.name);
        }
    }
}
</script>