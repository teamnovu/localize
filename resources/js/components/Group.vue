<template>
    <Entry v-if="typeof value === 'string'" :name="name" :value="value" :path="path" class="pl-3 novu-pr-0" />
    <fieldset v-else class="border dark:border-dark-900 rounded shadow-sm !novu-p-3 novu-mt-5 section">
        <legend class="novu-translate-y-[-75%] absolute">
            <Compoennt :is="parent ? 'h3' : 'h4'" class="bg-white inline-block novu-px-3">
                {{ deslug(name) }}
            </Compoennt>
        </legend>

        <Group :name="key" :value="children" :path="[...path, name]" v-for="children, key of value" :key="key" />

    </fieldset>
</template>

<script>
import Entry from './Entry.vue'
import { deslug } from '../utils'
export default {
    name: "Group",
    components: {
        Entry,
    },
    props: {
        parent: Boolean,
        name: String,
        value: Object,
        path: Array,
    },
    methods: {
        deslug
    },
}
</script>