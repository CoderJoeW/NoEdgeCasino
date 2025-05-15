<template>
    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-950">
        <div class="w-full max-w-6xl bg-slate-800 rounded-xl shadow-2xl overflow-hidden border border-slate-700">
            <GameHeader title="LIMBO"/>

            <!-- Main Content Area - Horizontal Layout -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3">
                <!-- Left Column: Game Area -->
                <div class="p-5 bg-slate-900 lg:col-span-2">
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Result Display -->
                        <div 
                            class="h-32 flex justify-center items-center rounded-lg transition-all duration-300"
                            :class="{
                                'bg-black/20': !lastResult.played,
                                'bg-gradient-to-br from-emerald-600 to-teal-500 shadow-lg shadow-emerald-600/30': lastResult.win && lastResult.played,
                                'bg-gradient-to-br from-red-600 to-orange-500 shadow-lg shadow-red-600/30': !lastResult.win && lastResult.played
                            }"
                        >
                            <transition name="fade">
                                <div v-if="lastResult.played" class="text-center">
                                    <span 
                                        class="block text-2xl font-extrabold mb-1 text-white"
                                    >
                                        {{ lastResult.win ? 'WIN!' : 'BUST!' }}
                                    </span>
                                    <div class="text-3xl font-extrabold text-white">
                                        {{ lastResult.multiplierAchieved.toFixed(2) }}x
                                    </div>
                                </div>
                                <div v-else class="text-xl text-white/70 tracking-wide">
                                    PLACE YOUR BET
                                </div>
                            </transition>
                        </div>

                        <!-- Game Stats -->
                        <div class="bg-slate-800/50 rounded-lg p-4 flex flex-col justify-center">
                            <div class="grid grid-cols-2 gap-y-4">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-xs text-white/70 mb-1">Games</span>
                                    <span class="text-base font-semibold text-white">{{ stats.totalGames }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-xs text-white/70 mb-1">Wins</span>
                                    <span class="text-base font-semibold text-white">{{ stats.wins }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-xs text-white/70 mb-1">Win Rate</span>
                                    <span class="text-base font-semibold text-white">{{ stats.winRate }}%</span>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-xs text-white/70 mb-1">Profit</span>
                                    <span 
                                        class="text-base font-semibold"
                                        :class="{
                                            'text-emerald-400': stats.profit > 0,
                                            'text-red-400': stats.profit < 0,
                                            'text-white': stats.profit === 0
                                        }"
                                    >
                                        {{ stats.profit > 0 ? '+' : '' }}${{ stats.profit.toFixed(2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="mt-5 grid md:grid-cols-2 gap-5">
                        <!-- Multiplier Control -->
                        <div class="space-y-2">
                            <label class="text-sm text-white/80">Target Multiplier</label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    v-model="targetMultiplier" 
                                    min="1.05" 
                                    max="999" 
                                    step="0.01"
                                    @input="validateMultiplier"
                                    class="w-full p-3 rounded-lg border-2 border-slate-700 bg-black/20 text-white text-lg font-semibold text-center"
                                >
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-lg font-semibold text-white/80">×</span>
                            </div>
                            <div class="w-full">
                                <input 
                                    type="range" 
                                    v-model="targetMultiplier" 
                                    min="1.05" 
                                    max="999" 
                                    step="0.01"
                                    @input="validateMultiplier"
                                    class="w-full h-2 bg-slate-700 rounded-full appearance-none cursor-pointer"
                                >
                            </div>
                            <div class="text-sm text-center bg-black/20 p-2 rounded-lg font-semibold text-white">
                                Win Chance: {{ winChance.toFixed(2) }}%
                            </div>
                        </div>

                        <!-- Bet Amount Control -->
                        <div class="space-y-2">
                            <label class="text-sm text-white/80">Bet Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lg font-semibold text-white/80">$</span>
                                <input 
                                    type="number" 
                                    v-model="betAmount" 
                                    min="1" 
                                    step="1"
                                    @input="validateBetAmount"
                                    class="w-full p-3 pl-8 rounded-lg border-2 border-slate-700 bg-black/20 text-white text-lg font-semibold text-center"
                                >
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <button 
                                    @click="halfBet"
                                    class="p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition-colors"
                                >
                                    ½
                                </button>
                                <button 
                                    @click="doubleBet"
                                    class="p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition-colors"
                                >
                                    2×
                                </button>
                                <button 
                                    @click="maxBet"
                                    class="p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition-colors"
                                >
                                    MAX
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Play Button -->
                    <button 
                        class="w-full mt-5 bg-gradient-to-r from-rose-600 to-pink-600 text-white p-4 text-lg font-bold rounded-lg transition-all duration-200 shadow-md shadow-rose-600/30 tracking-wide disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                        @click="playGame" 
                        :disabled="!canPlay"
                    >
                        PLAY
                    </button>
                </div>

                <!-- Right Column: Game History -->
                <div class="p-5 bg-slate-800">
                    <h3 class="mt-0 mb-3 text-base text-white/80 font-semibold">Game History</h3>
                    <div class="space-y-2 h-[calc(100%-2rem)] overflow-y-auto pr-1">
                        <div 
                            v-for="(game, index) in gameHistory" 
                            :key="index" 
                            class="grid grid-cols-3 p-2.5 rounded-md text-sm items-center"
                            :class="{
                                'bg-black/20': !game.win,
                                'bg-emerald-900/30 border-l-4 border-emerald-500': game.win,
                                'border-l-4 border-red-500': !game.win
                            }"
                        >
                            <span>{{ game.targetMultiplier.toFixed(2) }}×</span>
                            <span>{{ game.multiplierAchieved.toFixed(2) }}×</span>
                            <span 
                                :class="{
                                    'text-emerald-400 font-semibold': game.win,
                                    'text-red-400 font-semibold': !game.win
                                }"
                            >
                                {{ game.win ? '+' : '-' }}${{ game.betAmount.toFixed(2) }}
                            </span>
                        </div>
                        <div v-if="gameHistory.length === 0" class="text-center p-5 text-white/50">
                            No games played yet
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import GameHeader from './Global/GameHeader.vue';

export default {
    name: 'Limbo',
    components: {
        GameHeader,
    },
    data() {
        return {
            // Game state
            balance: 1000,
            targetMultiplier: 2.00,
            betAmount: 10,
            gameHistory: [],
            lastResult: { played: false, win: false, multiplierAchieved: 0 },
            isAnimating: false
        };
    },

    computed: {
        winChance() {
            // At 2X, chance should be 50%
            // Formula: (1/multiplier) * 100 * houseEdgeFactor
            // For 50% at 2X, houseEdgeFactor = 1
            return (1 / this.targetMultiplier) * 100;
        },

        canPlay() {
            return !this.isAnimating &&
                this.betAmount > 0 &&
                this.betAmount <= this.balance &&
                this.targetMultiplier >= 1.05 &&
                this.targetMultiplier <= 999;
        },

        stats() {
            const totalGames = this.gameHistory.length;
            const wins = this.gameHistory.filter(game => game.win).length;
            const winRate = totalGames > 0 ? ((wins / totalGames) * 100).toFixed(1) : '0.0';
            const profit = this.gameHistory.reduce((total, game) => {
                return total + (game.win ? game.betAmount * (game.targetMultiplier - 1) : -game.betAmount);
            }, 0);

            return { totalGames, wins, winRate, profit };
        }
    },

    methods: {
        validateMultiplier() {
            if (this.targetMultiplier < 1.05) {
                this.targetMultiplier = 1.05;
            } else if (this.targetMultiplier > 999) {
                this.targetMultiplier = 999;
            }
        },

        validateBetAmount() {
            if (this.betAmount < 0) {
                this.betAmount = 0;
            } else if (this.betAmount > this.balance) {
                this.betAmount = this.balance;
            }
        },

        halfBet() {
            this.betAmount = Math.floor(this.betAmount / 2);
            if (this.betAmount < 1) this.betAmount = 1;
        },

        doubleBet() {
            this.betAmount = Math.min(this.betAmount * 2, this.balance);
        },

        maxBet() {
            this.betAmount = this.balance;
        },

        playGame() {
            if (!this.canPlay) return;

            this.isAnimating = true;

            // Generate a random multiplier between 1.00 and a very high number
            // The higher the number, the less likely to win with high multipliers
            const randomMultiplier = 1 / Math.random();

            // Determine if player won
            const won = randomMultiplier >= this.targetMultiplier;

            // Update last result
            this.lastResult = {
                played: true,
                win: won,
                multiplierAchieved: randomMultiplier
            };

            // Update balance
            if (won) {
                this.balance += this.betAmount * (this.targetMultiplier - 1);
            } else {
                this.balance -= this.betAmount;
            }

            // Add to history
            this.gameHistory.unshift({
                targetMultiplier: this.targetMultiplier,
                multiplierAchieved: randomMultiplier,
                betAmount: this.betAmount,
                win: won
            });

            // Limit history to 10 items
            if (this.gameHistory.length > 10) {
                this.gameHistory.pop();
            }

            // Reset animation state after delay
            setTimeout(() => {
                this.isAnimating = false;
            }, 1500);
        }
    },

    watch: {
        targetMultiplier() {
            this.validateMultiplier();
        },

        betAmount() {
            this.validateBetAmount();
        }
    },

    mounted() {
        // Initialize
        this.validateMultiplier();
        this.validateBetAmount();
    }
};
</script>

<style>
/* Custom range input styling - minimal CSS needed since we're using Tailwind */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    @apply w-5 h-5 rounded-full bg-rose-600 cursor-pointer;
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>