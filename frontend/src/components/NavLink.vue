<template>
    <RouterLink v-if="active" :to="link" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors bg-gray-800/70 text-teal-400" @click="$emit('click', $event)">{{ linkValue }}</RouterLink>
    <RouterLink v-if="!active" :to="link" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-gray-400 hover:text-gray-100 hover:bg-gray-800/40" @click="$emit('click', $event)">{{ linkValue }}</RouterLink>
</template>

<script>
export default {
    name: 'NavLink',
    props: {
        active: {
            type: Boolean,
            required: true
        },
        link: {
            type: String,
            default: '#'
        },
        linkValue: {
            type: String,
            default: ''
        }
    }
}
</script>