<template>
    <Entry v-if="inputType(value)" :name="name" :value="value" :path="path" class="pl-3 pr-0" />
    <Panel v-else :heading="String(deslug(name))" class="col-span-2">
        <Card class="grid grid-cols-[auto_1fr] gap-4">
            <Group v-for="(children, key) in value" :name="String(key)" :value="children" :path="[...path, name]" :key="key" />
        </Card>
    </Panel>
</template>

<script setup lang="ts">
import { Card, Panel } from '@statamic/cms/ui'
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
