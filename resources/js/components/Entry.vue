<template>
    <div class="form-group flex items-center gap-3 px-0 py-1">
        <div class="field-inner truncate novu-w-[15rem]">
            <label :for="name" class="publish-field-label" :title="deslug(name)">
                {{ deslug(name) }}
            </label>
        </div>
        <div class="input-group">
            <input :id="name" :name="`${prefix}[${name}]`" :value="value" class="input-text" :placeholder="value">
        </div>
    </div>
</template>

<script>
import { deslug } from '../utils'
export default {
    props: {
        name: String,
        value: String,
        prefix: String,
    },
    methods: {
        deslug
    },
}
</script>