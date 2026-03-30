<template>
    <div class="relative w-full">
        <span v-if="isDirty" class="absolute right-3 mt-[0.4rem] pointer-events-none text-[rgb(67,169,255)]" aria-label="has changed">•</span>
        <input v-bind="$attrs" v-model="trackedValue" :name="name" class="input-text">
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
            // handle "<empty string>" in firefox
            if (!this.trackedValue && !this.value) return false;

            return this.trackedValue != this.value
        },
    },
    watch: {
        isDirty(isDirty) {
            if (isDirty) this.$dirty.add(this.name);
            else this.$dirty.remove(this.name);
        }
    },
}
</script>
