<template>
    <div class="flex-1 min-w-[200px] bg-slate-800/50 rounded-lg p-3">
        <div class="grid grid-cols-4 gap-x-4">
            <div class="flex flex-col items-center justify-center">
                <span class="text-xs text-white/70">Games</span>
                <span class="text-base font-semibold text-white">{{ sessionStats.totalGames }}</span>
            </div>
            <div class="flex flex-col items-center justify-center">
                <span class="text-xs text-white/70">Wins</span>
                <span class="text-base font-semibold text-white">{{ sessionStats.wins }}</span>
            </div>
            <div class="flex flex-col items-center justify-center">
                <span class="text-xs text-white/70">Win Rate</span>
                <span class="text-base font-semibold text-white">{{ winRate }}%</span>
            </div>
            <div class="flex flex-col items-center justify-center">
                <span class="text-xs text-white/70">Profit</span>
                <span 
                    class="text-base font-semibold"
                    :class="{
                        'text-emerald-400': sessionStats.profit > 0,
                        'text-red-400': sessionStats.profit < 0,
                        'text-white': sessionStats.profit === 0
                    }"
                >
                    {{ sessionStats.profit > 0 ? '+' : '' }}${{ sessionStats.profit.toFixed(2) }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GameStats',
    props: {
        sessionStats: {
            type: Object,
            required: true
        }
    },
    computed: {
        winRate() {
            if (this.sessionStats.wins === 0 || this.sessionStats.totalGames === 0) {
                return 0;
            }

            return ((this.sessionStats.wins / this.sessionStats.totalGames) * 100).toFixed(2);
        },
    }
}
</script>