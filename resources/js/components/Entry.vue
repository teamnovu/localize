<template>
    <div class="form-group flex gap-3 py-1 [.section+&]:novu-mt-6 novu-flex-wrap">
        <!-- label -->
        <div class="field-inner truncate w-full md:novu-w-[15rem] mt-2">
            <label :for="formName" class="publish-field-label" v-tooltip="deslug(name)">
                {{ deslug(name) }}
            </label>
        </div>

        <div class="flex gap-4 flex-col novu-grow">
            <!-- main input -->
            <div class="flex gap-2">
                <TrackedInput :id="formName" :name="formName" :value="value" :placeholder="value" />
                <button v-if="altCount" class="btn !novu-px-3 novu-w-[2.5rem]" type="button" tabindex="-1"
                    @click="expanded">
                    <svg-icon name="translate"></svg-icon>
                </button>
            </div>

            <!-- alternatives -->
            <div v-if="details" class="novu-transition-all novu-overflow-hidden" :style="{
                height: grow ? altCount * 38 + 8 + 2 + 'px' : 0
            }">
                <div class="pt-2 flex gap-2 flex-col ">
                    <div v-for="alt of alternatives" :key="alt.handle" class="flex gap-4 novu-items-center">
                        <div class="field-inner truncate novu-w-[8rem]">
                            <label class="publish-field-label">
                                {{ alt.name }}
                            </label>
                        </div>
                        <TrackedInput :id="name"
                            :name="`${formName.replace(`translations[${site}]`, `translations[${alt.handle}]`)}`"
                            :value="alt.value" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { deslug } from '../utils'
import TrackedInput from './TrackedInput.vue'
function walkObject(object, path, name) {
    let sub = object
    for (let step of path) {
        sub = sub[step]
        if (!sub) return undefined
    }
    return sub[name]
}
export default {
    components: {
        TrackedInput,
    },
    props: {
        name: String,
        value: String,
        path: Array,
    },
    inject: ['site', 'sites'],
    data() {
        return {
            details: false,
            grow: false,
        }
    },
    computed: {
        formName() {
            return 'translations' + [this.site, ...this.path, this.name].map(s => `[${s}]`).join('')
        },
        alternatives() {
            return Object.values(this.sites)
                .filter(alt => alt.handle != this.site)
                .map((alt) => ({
                    handle: alt.handle,
                    name: alt.name,
                    value: walkObject(alt.translations, this.path, this.name)
                }))
        },
        altCount() {
            return Object.values(this.sites).length - 1
        },
    },
    methods: {
        deslug,
        expanded() {
            this.details = true
            setTimeout(() => { this.grow = !this.grow }, 10)
        }
    }
}
</script>