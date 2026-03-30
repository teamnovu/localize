<template>
    <Entry v-if="inputType(value)" :name="name" :value="value" :path="path" class="pl-3 pr-0" />
    <ui-panel v-else :heading="deslug(name)" class="col-span-2">
        <ui-card class="grid grid-cols-[auto_1fr] gap-4">
            <Group :name="key" :value="children" :path="[...path, name]" v-for="(children, key) in value" :key="key" />
        </ui-card>
    </ui-panel>
</template>

<script setup lang="ts">
import Entry from './Entry.vue'
import Group from './Group.vue'
import { deslug, inputType } from '../utils'
import type { TranslationScalar, TranslationTree } from '../types/localize'

defineProps<{
    parent?: boolean
    name: string
    value: TranslationTree | TranslationScalar
    path: string[]
}>()
</script>
