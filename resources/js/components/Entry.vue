<template>
    <div class="form-group flex gap-3 py-1 [.section+&]:mt-6 flex-wrap" blink-target>
        <!-- label -->
        <div class="field-inner truncate w-full md:w-60 mt-2">
            <label :for="`${site}.${pathName}`" class="publish-field-label" v-tooltip="pathName">
                <a :href="`#${site}.${pathName}`" @click="setAnchor">
                    {{ deslug(name) }}
                </a>
            </label>
        </div>

        <div class="flex gap-4 flex-col grow">
            <!-- main input -->
            <div class="flex gap-2">
                <TrackedInput :id="`${site}.${pathName}`" :name="formName" :value="value" :placeholder="value" />
                <button v-if="altCount" class="btn px-3! w-10" type="button" @click="expanded">
                    <svg-icon name="translate" />
                </button>
            </div>

            <!-- alternatives -->
            <div
                v-if="details"
                class="transition-all overflow-hidden m-[0_-2px_-2px_0]"
                :style="{
                    height: grow ? altCount * (38 + 8) + 2 + 'px' : 0
                }"
            >
                <div class="pt-2 flex gap-2 flex-col pr-[2px]">
                    <div v-for="alt of alternatives" :key="alt.handle" class="flex gap-4 items-center" blink-target>
                        <div class="field-inner truncate w-32">
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

<script setup lang="ts">
import { computed, inject, onMounted, ref, unref } from 'vue'
import { deslug, walkObject } from '../utils'
import TrackedInput from './TrackedInput.vue'

const props = defineProps({
    name: String,
    value: String,
    path: Array,
})

const site = inject('site')
const sites = inject('sites')

const details = ref(false)
const grow = ref(false)

const formName = computed(() => {
    return 'translations' + [site.value, ...props.path, props.name].map((str) => `[${str}]`).join('')
})

const pathName = computed(() => [...props.path, props.name].join('.'))

const alternatives = computed(() => {
    const sitesVal = unref(sites)
    return Object.values(sitesVal)
        .filter((alt) => alt.handle != site.value)
        .map((alt) => ({
            handle: alt.handle,
            name: alt.name,
            value: walkObject(alt.translations, props.path, props.name),
        }))
})

const altCount = computed(() => Object.keys(unref(sites)).length - 1)

function expanded() {
    details.value = true
    setTimeout(() => {
        grow.value = !grow.value
    }, 10)
}

function setAnchor(e) {
    e.preventDefault()
    window.history.replaceState(window.history.state, '', e.target.href)
    const input = document.getElementById(e.target.href.split('#')[1])
    input?.focus()
}

onMounted(() => {
    const hash = window.location.hash.substring(1)
    const siteFromHash = hash.substring(0, hash.indexOf('.'))
    const path = hash.substring(hash.indexOf('.') + 1)

    if (path === pathName.value) {
        if (siteFromHash !== site.value) {
            details.value = true
            grow.value = true
        }

        setTimeout(() => {
            const el = document.getElementById(window.location.hash.substring(1))
            if (!el) return

            el.closest('[blink-target]').classList.add('blink')
            el.focus()
        }, 10)
    }
})
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
