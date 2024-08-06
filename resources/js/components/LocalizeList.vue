<template>
    <form @submit.prevent="save" ref="form">

        <input name="site" type="hidden" :value="site" />

        <div v-for="value, first of translations" :key="first" class="card p-6 content novu-mb-6 form-group">
            <h2 class="mb-4">{{ deslug(first) }}</h2>
            <Entry v-if="typeof value === 'string'" :name="first" :value="value" prefix="translations" />
            <div v-else>
                <div v-for="value, second of value" :key="second">
                    <Entry v-if="typeof value === 'string'" :name="second" :value="value"
                        :prefix="`translations[${first}]`" />
                    <Group v-else :name="second" :value="value" :prefix="`translations[${first}]`" />
                </div>
            </div>
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
        translations: Array,
        action: String,
    },

    data() {
        return {
            errors: {},
            saving: false,
            saveKeyBinding: null
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
                .then((response) => {
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