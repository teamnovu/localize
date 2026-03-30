<template>
    <Label :for="`${site}.${pathName}`" class="pt-2.5" v-tooltip="pathName" blink-target>
        <a :href="`#${site}.${pathName}`" @click="setAnchor">
            {{ deslug(name) }}
        </a>
    </Label>
    <div>
        <!-- main input -->
        <div class="flex gap-2">
            <TrackedInput :id="`${site}.${pathName}`" :name="formName" :value="value" :placeholder="String(value ?? '')" />
            <Button v-if="altCount" type="button" :aria-label="__('localize::general.show_alternatives')" @click="toggle" icon="globe-world-wide-web" />
        </div>

        <!-- alternatives -->
        <Transition
            name="details"
            enter-active-class="transition-[height] duration-500 ease-in-out overflow-hidden"
            enter-from-class="h-0!"
            enter-to-class="h-auto!"
            leave-active-class="transition-[height] duration-500 ease-in-out overflow-hidden"
            leave-from-class="h-auto!"
            leave-to-class="h-0!"
        >
            <div
                v-if="details"
            >
                <div class="h-4" :aria-hidden="true" />
                <div class="grid grid-cols-[auto_1fr] gap-x-8 gap-y-2 items-center">
                    <template v-for="alt of alternatives" :key="alt.handle">
                        <label
                            :for="`${alt.handle}.${pathName}`"
                            class="truncate"
                        >
                            <a :href="`#${alt.handle}.${pathName}`" @click="setAnchor">
                                {{ alt.name }}
                            </a>
                        </label>
                        <TrackedInput
                            :id="`${alt.handle}.${pathName}`"
                            :name="`${formName.replace(`translations[${site}]`, `translations[${alt.handle}]`)}`"
                            :value="alt.value"
                        />
                    </template>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { Button, Label } from '@statamic/cms/ui'
import { computed, inject, onMounted, ref } from 'vue'
import { deslug, walkObject } from '../utils'
import { siteKey, sitesKey } from '../injectionKeys'
import type { SiteConfig, TranslationScalar } from '../types/localize'
import TrackedInput from './TrackedInput.vue'

const props = defineProps<{
    name: string
    value: TranslationScalar
    path: string[]
}>()

const site = inject(siteKey)
const sites = inject(sitesKey)

if (site === undefined || sites === undefined) {
    throw new Error('localize-list: missing site / sites provide()')
}

const details = ref(false)

const formName = computed(() => {
    return 'translations' + [site.value, ...props.path, props.name].map((str) => `[${str}]`).join('')
})

const pathName = computed(() => [...props.path, props.name].join('.'))

const alternatives = computed(() => {
    return (Object.values(sites.value) as SiteConfig[])
        .filter((alt) => alt.handle !== site.value)
        .map((alt) => ({
            handle: alt.handle,
            name: alt.name,
            value: walkObject(alt.translations, props.path, props.name) ?? null,
        }))
})

const altCount = computed(() => Object.keys(sites.value).length - 1)

function toggle() {
    details.value = !details.value
}

function setAnchor(e: MouseEvent) {
    e.preventDefault()
    const anchor = e.currentTarget as HTMLAnchorElement | null
    if (!anchor?.href) return

    window.history.replaceState(window.history.state, '', anchor.href)
    const id = anchor.href.split('#')[1]
    if (!id) return

    const input = document.getElementById(id)
    input?.focus()
}

onMounted(() => {
    const hash = window.location.hash.substring(1)
    const siteFromHash = hash.substring(0, hash.indexOf('.'))
    const path = hash.substring(hash.indexOf('.') + 1)

    if (path === pathName.value) {
        if (siteFromHash === site.value) {
            details.value = true
        }

        setTimeout(() => {
            const el = document.getElementById(window.location.hash.substring(1))
            if (!el) return

            el.closest('[blink-target]')?.classList.add('blink')
            el.focus()
        }, 10)
    }
})
</script>

<style>
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

:root {
    interpolate-size: allow-keywords;
}

</style>
