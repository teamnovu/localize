<template>

        <div class="input-group">
            <IconLink class="novu-h-full" />
        </button>
        <div class="input-group relative novu-flex novu-gap-2">
            <input v-bind="$attrs" v-model.trim="trackedValue" :id="name" :name="name" class="input-text">
        </div>

    </div>
</template>

<script>
import IconLink from './IconLink.vue'
export default {
    inheritAttrs: false,
    props: {
        name: String,
        value: String,
    },
    components: {
        IconLink
    },
    data() {
        return {
            trackedValue: this.value,
        }
    },
    computed: {
        isDirty() {
            // handle "<empty string>" in firefox
            if (!this.trackedValue && !this.value) return false;

            return this.trackedValue != this.value
        },
        linkId() {
            return `${this.name }`.replaceAll('[', '-').replace(/[^a-zA-Z0-9-]/g, '')
        },
       
    },
    watch: {
        isDirty(isDirty) {
            if (isDirty) this.$dirty.add(this.name);
            else this.$dirty.remove(this.name);
        }
    },
    methods: {
        copyToClipboard() {
            const currentUrl = location.protocol + '//' + location.host + location.pathname
            
            navigator.clipboard.writeText(currentUrl +'#' + this.linkId).then(function() {
                // console.log('Async: Copying to clipboard was successful!');
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });

        }
    }
}
</script>