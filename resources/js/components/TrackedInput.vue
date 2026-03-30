<template>
    <div class="relative w-full">
        <span v-if="isDirty" class="absolute right-3 mt-[0.4rem] pointer-events-none text-[rgb(67,169,255)]" aria-label="has changed">•</span>
        <input v-bind="$attrs" v-model="trackedValue" :name="name" class="input-text">
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import type { TranslationScalar } from '../types/localize'

defineOptions({ inheritAttrs: false })

const props = defineProps<{
    name: string
    value: TranslationScalar
}>()


const trackedValue = ref(props.value)

const isDirty = computed(() => {
    // handle "<empty string>" in firefox
    if (!trackedValue.value && !props.value) return false

    return trackedValue.value != props.value
})

watch(isDirty, (dirty) => {
    if (dirty) Statamic.$dirty.add(props.name)
    else Statamic.$dirty.remove(props.name)
})
</script>
