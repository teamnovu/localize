<template>
    <Entry v-if="typeof value === 'string'" :name="name" :value="value" :prefix="prefix" />
    <div v-else class="border dark:border-dark-900 rounded shadow-sm !novu-p-3 my-6">
        <div class="novu-translate-y-[-75%] absolute">
            <h3 class="bg-white inline-block novu-px-3">
                {{ deslug(name) }}
            </h3>
        </div>
        <div v-for="children, key of value" :key="key">
            <Group :name="key" :value="children" :prefix="`${prefix}[${name}]`" />
        </div>
    </div>
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
        name: String,
        value: Object,
        prefix: String,
    },
    methods: {
        deslug
    },
}
</script>