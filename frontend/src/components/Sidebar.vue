<template>
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-900/50 backdrop-blur-sm border-r border-gray-700">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-8">
                <h1 class="text-xl font-bold">No Edge Casino</h1>
            </div>

            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Sidebar'
}
</script>