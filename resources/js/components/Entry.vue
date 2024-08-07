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
                <button v-if="count" class="btn !novu-px-3 novu-w-[2.5rem]" type="button" tabindex="-1"
                    @click="expanded">
                    <svg-icon name="translate"></svg-icon>
                </button>
            </div>

            <!-- alternatives -->
            <div v-if="details" class="novu-transition-all novu-overflow-hidden" :style="{
                height: grow ? count * 41 + 8 + 'px' : 0
            }">
                <div class="pt-2 flex gap-2 flex-col ">
                    <div v-for="alt of alternatives" class="flex gap-4 novu-items-center">
                        <div class="field-inner truncate novu-w-[8rem]">
                            <label class="publish-field-label">
                                {{ alt.name }}
                            </label>
                        </div>
                        <TrackedInput :id="name" :name="`${formName.replace(`[${site}]`, `[${alt.handle}]`)}`"
                            :value="getAltTranslation(alt)" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { deslug } from '../utils'
import TrackedInput from './TrackedInput.vue'
export default {
    components: {
        TrackedInput,
    },
    props: {
        name: String,
        value: String,
        path: Array,
    },
    inject: ['site', 'alternatives'],
    data() {
        return {
            details: false,
            grow: false,
        }
    },
    computed: {
        formName() {
            return `translations[${this.site}]${this.path.map(s => `[${s}]`).join('')}[${this.name}]`
        },
        count() {
            return Object.keys(this.alternatives).length
        }
    },
    methods: {
        deslug,
        getAltTranslation(alt) {
            let translation = alt.translations
            for (let step of this.path) {
                translation = translation[step]
                if (!translation) return ''
            }
            return translation[this.name]
        },
        expanded() {
            this.details = true
            setTimeout(() => {
                this.grow = !this.grow
            }, 10)
        }
    }
}
</script>