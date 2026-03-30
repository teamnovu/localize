<template>
    <form @submit.prevent="save" ref="form" class="space-y-4">
        <Header :title="__('localize::general.title')">
            <Button :text="__('Save')" variant="primary" @click="save" :loading="isSaving" />
        </Header>
        <Alert :text="__('localize::general.intro')" />

        <Panel v-for="(value, first) in objects" :key="first" :heading="first === '__rootNodes' ? __('localize::general.strings') : deslug(first)" class="max-w-4xl mx-auto">
            <Card class="grid grid-cols-[auto_1fr] gap-4">
                <template v-for="(secondValue, second) in value">
                    <Entry v-if="inputType(secondValue)" :name="String(second)" :value="secondValue" :path="first === '__rootNodes' ? [] : [first]" />
                    <Group v-else :name="String(second)" :value="secondValue" :path="first === '__rootNodes' ? [] : [first]" parent />
                </template>
            </Card>
        </Panel>

        <Alert v-if="Object.values(translations).length === 0" :text="__('localize::general.no_content')" variant="warning" />
    </form>
</template>

<script setup lang="ts">
import { Alert, Button, Card, Header, Panel } from '@statamic/cms/ui'
import { computed, onBeforeUnmount, onMounted, provide, ref, toRef } from 'vue'
import Entry from './Entry.vue'
import Group from './Group.vue'
import { deslug, inputType, readXsrfToken, sanitizeSitesMap } from '../utils'
import { siteKey, sitesKey } from '../injectionKeys'
import {
    isSaveSuccess,
    isValidationError,
    type SitesMap,
    type TranslationScalar,
    type TranslationTree,
} from '../types/localize'

const props = defineProps<{
    site: string
    sites: SitesMap
    action: string
}>()

const form = ref<HTMLFormElement | null>(null)
const trackedSites = ref<SitesMap>(props.sites)
const saveKeyBinding = ref<{ destroy: () => void } | null>(null)

function createEmptyTranslationKeys(obj1: TranslationTree, obj2: TranslationTree): TranslationTree {
    for (const key in obj2) {
        if (Object.prototype.hasOwnProperty.call(obj2, key) && !Object.prototype.hasOwnProperty.call(obj1, key)) {
            const v = obj2[key]
            if (v !== null && typeof v === 'object') {
                const next = (obj1[key] as TranslationTree | undefined) ?? {}
                obj1[key] = createEmptyTranslationKeys(next, v as TranslationTree)
            } else {
                obj1[key] = ''
            }
        }
    }
    return obj1
}

const translations = computed(() => {
    const otherSites = Object.keys(trackedSites.value).filter((x) => x !== props.site)
    let result = trackedSites.value[props.site].translations
    otherSites.forEach((s) => {
        result = createEmptyTranslationKeys(result, trackedSites.value[s].translations)
    })
    return result
})

const objects = computed(() => {
    const result = Object.entries(translations.value).reduce<Record<string, TranslationTree>>((acc, [key, value]) => {
        if (!inputType(value)) acc[key] = value as TranslationTree
        return acc
    }, {})

    const looseStrings = Object.entries(translations.value).reduce<Record<string, TranslationScalar>>((acc, [key, value]) => {
        if (inputType(value)) acc[key] = value
        return acc
    }, {})

    if (Object.keys(looseStrings).length) {
        result['__rootNodes'] = looseStrings
    }

    return result
})

provide(siteKey, toRef(props, 'site'))
provide(sitesKey, trackedSites)

const isSaving = ref(false)
async function save() {
    const formEl = form.value
    if (!formEl) return

    const headers: Record<string, string> = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
    const xsrf = readXsrfToken()
    if (xsrf) {
        headers['X-XSRF-TOKEN'] = xsrf
    }

    let response: Response
    try {
        isSaving.value = true
        response = await fetch(props.action, {
            method: 'POST',
            body: new FormData(formEl),
            credentials: 'same-origin',
            headers,
        })
    } catch (e: unknown) {
        const message = e instanceof Error ? e.message : 'Something went wrong'
        Statamic.$toast.error(message)
        isSaving.value = false
        return
    }

    let data: unknown = null
    const ct = response.headers.get('content-type') ?? ''
    if (ct.includes('application/json')) {
        try {
            const text = await response.text()
            const parsed: unknown = JSON.parse(text)
            if (isSaveSuccess(parsed)) {
                parsed.sites = sanitizeSitesMap(parsed.sites)
            }
            data = parsed
        } catch {
            data = null
        }
    }
    isSaving.value = false

    if (!response.ok) {
        if (response.status === 422 && isValidationError(data)) {
            console.error(data.errors)
            Statamic.$toast.error(data.message)
        } else if (isValidationError(data)) {
            Statamic.$toast.error(data.message)
        } else {
            Statamic.$toast.error('Something went wrong')
        }
        return
    }

    if (!isSaveSuccess(data)) {
        Statamic.$toast.error('Something went wrong')
        return
    }

    Statamic.$toast.success(data.status)
    trackedSites.value = Object.assign(trackedSites.value, data.sites)
}

onMounted(() => {
    saveKeyBinding.value = Statamic.$keys.bindGlobal(
        ['mod+s', 'mod+return'],
        (e) => {
            e.preventDefault()
            void save()
        },
    )
})

onBeforeUnmount(() => {
    saveKeyBinding.value?.destroy()
})
</script>
