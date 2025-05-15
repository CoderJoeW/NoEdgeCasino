<template>
    <div class="bg-gradient-to-r from-rose-600 to-pink-600 p-5 flex justify-between items-center">
        <h1 class="text-2xl font-extrabold tracking-wider text-white m-0 drop-shadow-md">{{ title }}</h1>
        <div class="bg-black/30 px-4 py-2 rounded-full font-semibold text-white">
            <span>Global Pool: ${{ globalBalance.balances.NoEdgeCash.value.toLocaleString() }}</span>
        </div>
    </div>
</template>

<script>
import { useGlobalBalanceStore } from '@/store/globalBalanceStore';

export default {
    name: 'GameHeader',
    props: {
        title: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            globalBalance: useGlobalBalanceStore(),
        };
    },
    async mounted() {
        this.globalBalance.loadGlobalPoolData();
    }
}
</script>