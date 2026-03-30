<template>
    <ui-input
        v-bind="$attrs"
        v-model="trackedValue"
        :name="name"
    >
        <template #append>
            <ui-button v-if="isDirty" inset variant="ghost" icon="backspace" size="sm" class="mr-1" @click="trackedValue = value" />
        </template>
    </ui-input>
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

    return trackedValue.value !== props.value
})

watch(isDirty, (dirty) => {
    if (dirty) Statamic.$dirty.add(props.name)
    else Statamic.$dirty.remove(props.name)
})
</script>
