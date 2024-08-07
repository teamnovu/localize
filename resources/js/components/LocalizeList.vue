<template>
    <form @submit.prevent="save" ref="form">

        <header class="mb-8">
            <button class="novu-float-right btn-primary">{{ __('Save') }}</button>
            <h1>Localize</h1>
            <p>
                Texts may contain special characters, that will be replaced on the website.<br>
                These can be <code>{name}</code> or <code>:count</code> for example.
            </p>
        </header>

        <div v-for="value, first of translation " :key="first" class="card p-6 content novu-mb-6 form-group">
            <h2 class="mb-4">{{ deslug(first) }}</h2>
            <Entry v-if="typeof value === 'string'" :name="first" :value="value" :path="[]" class="px-0" />
            <template v-else>
                <template v-for="value, second of value ">
                    <Entry v-if="typeof value === 'string'" :name="second" :value="value" :path="[first]"
                        class=" px-0" />
                    <Group v-else :name="second" :value="value" :path="[first]" parent />
                </template>
            </template>
        </div>

        <div v-if="Object.values(translation).length === 0" class="card p-6 content">
            <p>No translations found</p>
        </div>

    </form>
</template>

<script>
import Entry from './Entry.vue'
import Group from './Group.vue'
import { deslug } from '../utils'
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
            errors: {},
            saving: false,
            saveKeyBinding: null
        }
    },

    computed: {
        translation() {
            return JSON.parse(this.sites[this.site].translations)
        }
    },

    provide() {
        const alternatives = ({ ...this.sites })
        delete alternatives[this.site]
        Object.values(alternatives).forEach((alt) => alt.translations = JSON.parse(alt.translations))

        return {
            site: this.site,
            sites: this.sites,
            alternatives
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

        save() {
            this.saving = true
            this.clearErrors()

            this.$axios({
                method: "POST",
                url: this.action,
                data: this.$refs.form,
            })
                .then(() => {
                    this.saving = false
                    this.$toast.success(__('Saved'))
                })
                .catch((error) => this.handleAxiosError(error))
        },

        clearErrors() {
            this.error = null
            this.errors = {}
        },

        handleAxiosError(e) {
            this.saving = false

            if (e.response && e.response.status === 422) {
                const { message, errors } = e.response.data
                this.error = message
                this.errors = errors
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