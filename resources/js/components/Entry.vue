<template>
    <div class="form-group flex gap-3 py-1 [.section+&]:novu-mt-6 novu-flex-wrap" blink-target>
        <!-- label -->
        <div class="field-inner truncate w-full md:novu-w-[15rem] mt-2">
            <label :for="`${site}.${pathName}`" class="publish-field-label" v-tooltip="pathName">
                <a :href="`#${site}.${pathName}`" @click="setAnchor">
                    {{ deslug(name) }}
                </a>
            </label>
        </div>

        <div class="flex gap-4 flex-col novu-grow">
            <!-- main input -->
            <div class="flex gap-2">
                <TrackedInput :id="`${site}.${pathName}`" :name="formName" :value="value" :placeholder="value" />
                <button v-if="altCount" class="btn !novu-px-3 novu-w-[2.5rem]" type="button" @click="expanded">
                    <svg-icon name="translate" />
                </button>
            </div>

            <!-- alternatives -->
            <div
                v-if="details"
                class="novu-transition-all novu-overflow-hidden novu-m-[0_-2px_-2px_0]"
                :style="{
                    height: grow ? altCount * (38 + 8) + 2 + 'px' : 0
                }"
            >
                <div class="pt-2 flex gap-2 flex-col novu-pr-[2px]">
                    <div v-for="alt of alternatives" :key="alt.handle" class="flex gap-4 novu-items-center" blink-target>
                        <div class="field-inner truncate novu-w-[8rem]">
                            <label
                                :for="`${alt.handle}.${pathName}`"
                                class="publish-field-label"
                            >
                                <a :href="`#${alt.handle}.${pathName}`" @click="setAnchor">
                                    {{ alt.name }}
                                </a>
                            </label>
                        </div>
                        <TrackedInput
                            :id="`${alt.handle}.${pathName}`"
                            :name="`${formName.replace(`translations[${site}]`, `translations[${alt.handle}]`)}`"
                            :value="alt.value"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { deslug, walkObject } from '../utils'
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
        pathName() {
            return [...this.path, this.name].join('.')
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
            return Object.keys(this.sites).length - 1
        },
    },
    methods: {
        deslug,
        expanded() {
            this.details = true
            setTimeout(() => { this.grow = !this.grow }, 10)
        },
        setAnchor(e) {
            e.preventDefault()
            window.history.replaceState(window.history.state, '', e.target.href)
            const input = document.getElementById(e.target.href.split('#')[1])
            input?.focus()
        }
    },
    mounted() {
        const hash = window.location.hash.substring(1)
        const site = hash.substring(0, hash.indexOf('.'))
        const path = hash.substring(hash.indexOf('.') + 1)

        if (path === this.pathName) {
            if(site !== this.site) {
                this.details = true
                this.grow = true
            }

            setTimeout(() => {
                const el = document.getElementById(window.location.hash.substring(1))
                if (!el) return

                el.closest('[blink-target]').classList.add('blink')
                el.focus()
            }, 10)
        }
    }
}
</script>

<style>
html {
    scroll-padding-top: 20vh;
}

.blink {
    animation-name: blink;
    animation-duration: 0.8s;
    animation-iteration-count: 3;
}
@keyframes blink {
    50% {
      opacity: 0.5;
    }
    0%,
    100% {
      opacity: 1;
    }
}
</style>