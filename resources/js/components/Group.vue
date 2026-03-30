<template>
    <Entry v-if="inputType(value)" :name="name" :value="value" :path="path" class="pl-3 pr-0" />
    <fieldset v-else class="border dark:border-dark-900 rounded shadow-sm p-2! mt-5 section">
        <legend class="translate-y-[-65%] absolute">
            <component :is="parent ? 'h3' : 'h4'" class="bg-white dark:bg-dark-600 inline-block px-3">
                {{ deslug(name) }}
            </component>
        </legend>

        <Group :name="key" :value="children" :path="[...path, name]" v-for="(children, key) in value" :key="key" />

    </fieldset>
</template>

<script setup lang="ts">
import Entry from './Entry.vue'
import Group from './Group.vue'
import { deslug, inputType } from '../utils'

defineProps({
    parent: Boolean,
    name: String,
    value: Object,
    path: Array,
})
</script>
