<template>
    <form @submit.prevent="save" ref="form">

        <header class="mb-8">
            <button class="novu-float-right btn-primary">{{ __('Save') }}</button>
            <h1>{{ __('localize::general.title') }}</h1>
            <p v-html="__('localize::general.intro')"></p>
        </header>

        <div v-if="Object.keys(strings).length" class="card p-6 content novu-mb-6 form-group">
            <Entry v-for="value, first of strings" :key="first" :name="first" :value="value" :path="[]" class="px-0" />
        </div>

        <div v-for="value, first of objects" :key="first" class="card p-6 content novu-mb-6 form-group">
            <h2 class="mb-3">{{ deslug(first) }}</h2>
            <template v-for="secondValue, second of value">
                <Entry v-if="inputType(secondValue)" :name="second" :value="secondValue" :path="[first]" class="px-0" />
                <Group v-else :name="second" :value="secondValue" :path="[first]" parent />
            </template>
        </div>

        <div v-if="Object.values(translations).length === 0" class="card p-6 content">
            <p>{{ __('localize::general.no_content') }}</p>
        </div>

    </form>
</template>

<script>
import Entry from './Entry.vue'
import Group from './Group.vue'
import { deslug, inputType } from '../utils'
export default {

    components: {
        Entry,
        Group,
    },

    props: {
        site: String,
        sites: Object,
        action: String,
    },

    data() {
        return {
            trackedSites: this.sites,
            saveKeyBinding: null
        }
    },

    computed: {
        translations() {
            return this.trackedSites[this.site].translations
        },
        strings() {
            return Object.entries(this.translations).reduce((acc, [key, value]) => {
                if (inputType(value)) acc[key] = value
                return acc
            }, {})
        },
        objects() {
            return Object.entries(this.translations).reduce((acc, [key, value]) => {
                if (!inputType(value)) acc[key] = value
                return acc
            }, {})
        }
    },

    provide() {
        return {
            site: this.site,
            sites: this.trackedSites,
        }
    },

    mounted() {
        this.saveKeyBinding = this.$keys.bindGlobal(
            ['mod+s', 'mod+return'],
            (e) => {
                e.preventDefault()
                this.save()
            }
        )
    },

    methods: {
        deslug,
        inputType,
        save() {
            this.$axios({
                method: "POST",
                url: this.action,
                data: this.$refs.form,
            })
                .then((response) => {
                    this.$toast.success(response.data.status)
                    this.trackedSites = Object.assign(this.trackedSites, response.data.sites)
                })
                .catch((error) => this.handleAxiosError(error))
        },
        handleAxiosError(e) {
            if (e.response && e.response.status === 422) {
                const { message, errors } = e.response.data
                console.error(errors);
                this.$toast.error(message)
            } else if (e.response) {
                this.$toast.error(e.response.data.message)
            } else {
                this.$toast.error(e || 'Something went wrong')
            }
        },
    },

    destroyed() {
        this.saveKeyBinding.destroy()
    },
}
</script>