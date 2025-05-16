<template>
    <div class="min-h-screen flex justify-center p-4 bg-slate-950">
        <div class="w-full max-w-6xl bg-slate-800 rounded-xl shadow-2xl overflow-hidden border border-slate-700">
            <GameHeader title="LIMBO"/>

            <!-- Main Content Area - Horizontal Layout -->
            <div class="p-4 bg-slate-900">
                <!-- Top Section: Game Info and Stats -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <!-- Result Display -->
                    <div 
                        class="flex-1 min-w-[200px] h-16 flex justify-center items-center rounded-lg transition-all duration-300"
                        :class="{
                            'bg-black/20': !lastResult.played,
                            'bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg shadow-emerald-600/30': lastResult.win && lastResult.played,
                            'bg-gradient-to-r from-red-600 to-orange-500 shadow-lg shadow-red-600/30': !lastResult.win && lastResult.played
                        }"
                    >
                        <transition name="fade">
                            <div v-if="lastResult.played" class="flex items-center space-x-3">
                                <span class="text-2xl font-extrabold text-white">
                                    {{ lastResult.win ? 'WIN!' : 'BUST!' }}
                                </span>
                                <div class="text-3xl font-extrabold text-white">
                                    {{ lastResult.multiplierAchieved.toFixed(2) }}×
                                </div>
                            </div>
                            <div v-else class="text-xl text-white/70 tracking-wide">
                                PLACE YOUR BET
                            </div>
                        </transition>
                    </div>

                    <!-- Game Stats -->
                    <div class="flex-1 min-w-[200px] bg-slate-800/50 rounded-lg p-3">
                        <div class="grid grid-cols-4 gap-x-4">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-xs text-white/70">Games</span>
                                <span class="text-base font-semibold text-white">{{ stats.totalGames }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-xs text-white/70">Wins</span>
                                <span class="text-base font-semibold text-white">{{ stats.wins }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-xs text-white/70">Win Rate</span>
                                <span class="text-base font-semibold text-white">{{ stats.winRate }}%</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-xs text-white/70">Profit</span>
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

                <!-- Main Game Area: Side by Side Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Left: Rocket Animation -->
                    <div class="lg:col-span-2">
                        <div class="relative h-64 bg-slate-950 rounded-lg overflow-hidden border border-slate-800">
                            <div class="absolute inset-0 rocket-animation-container">
                                <!-- Stars Background -->
                                <div class="stars-container absolute inset-0">
                                    <div v-for="i in 50" :key="`star-${i}`" 
                                        class="star absolute rounded-full bg-white"
                                        :class="{ 'animate-twinkle': isAnimating, 'animate-streak': isAnimating && currentMultiplier > 5 }"
                                        :style="{
                                            left: `${Math.random() * 100}%`,
                                            top: `${Math.random() * 100}%`,
                                            width: `${Math.random() * 2 + 1}px`,
                                            height: `${Math.random() * 2 + 1}px`,
                                            animationDelay: `${Math.random() * 3}s`,
                                            opacity: Math.random() * 0.7 + 0.3
                                        }">
                                    </div>
                                </div>

                                <!-- Multiplier Planets -->
                                <div class="planets-container absolute inset-0">
                                    <div v-for="(planet, index) in planets" :key="`planet-${index}`"
                                        class="planet absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center"
                                        :class="{ 'planet-reached': isAnimating && currentMultiplier >= planet.value }"
                                        :style="{
                                            bottom: `${getMultiplierPosition(planet.value)}%`,
                                        }">
                                        <div class="planet-ring relative rounded-full flex items-center justify-center"
                                            :class="planet.ringClass">
                                            <span class="text-xs font-bold text-white z-10">{{ planet.value }}x</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rocket -->
                                <div v-if="isAnimating || lastResult.played" 
                                    class="rocket absolute left-1/2 transform -translate-x-1/2"
                                    :class="{
                                        'rocket-flying': isAnimating && !isCrashing,
                                        'rocket-crashed': isCrashing,
                                        'rocket-landed': !isAnimating && lastResult.win
                                    }"
                                    :style="{
                                        bottom: isAnimating ? `${getMultiplierPosition(currentMultiplier)}%` :
                                            lastResult.win ? `${getMultiplierPosition(lastResult.multiplierAchieved)}%` : '10%',
                                        transition: `bottom ${isAnimating ? '0.1s' : '0.5s'} ease-out`
                                    }">
                                    <!-- Rocket Body -->
                                    <div class="relative">
                                        <!-- Rocket Trail -->
                                        <div v-if="isAnimating || (lastResult.played && !lastResult.win && isCrashing)" 
                                            class="rocket-trail absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-full">
                                            <div v-for="i in 8" :key="`trail-${i}`"
                                                class="trail-particle absolute rounded-full"
                                                :class="[getTrailColorClass(currentMultiplier)]"
                                                :style="{
                                                    width: `${Math.random() * 8 + 4}px`,
                                                    height: `${Math.random() * 8 + 4}px`,
                                                    left: `${(Math.random() - 0.5) * 20}px`,
                                                    bottom: `${i * 5}px`,
                                                    opacity: 1 - (i * 0.1),
                                                    animationDelay: `${i * 0.05}s`
                                                }">
                                            </div>
                                        </div>

                                        <!-- Rocket Ship -->
                                        <div class="rocket-body relative h-10 w-7 bg-gradient-to-b from-rose-500 to-rose-600 rounded-t-full">
                                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-cyan-300 rounded-full flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                            <div class="absolute bottom-0 left-0 w-2 h-3 bg-rose-700 rounded-bl-md"></div>
                                            <div class="absolute bottom-0 right-0 w-2 h-3 bg-rose-700 rounded-br-md"></div>
                                        </div>
                                    </div>

                                    <!-- Explosion Effect -->
                                    <div v-if="isCrashing" class="explosion absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        <div v-for="i in 20" :key="`explosion-${i}`"
                                            class="explosion-particle absolute rounded-full"
                                            :style="{
                                                width: `${Math.random() * 6 + 2}px`,
                                                height: `${Math.random() * 6 + 2}px`,
                                                backgroundColor: getRandomExplosionColor(),
                                                transform: `translate(${(Math.random() - 0.5) * 100}px, ${(Math.random() - 0.5) * 100}px)`,
                                                opacity: Math.random(),
                                                animationDuration: `${Math.random() * 1 + 0.5}s`
                                            }">
                                        </div>
                                    </div>

                                    <!-- Win Celebration -->
                                    <div v-if="!isAnimating && lastResult.win" class="celebration absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full">
                                        <div v-for="i in 30" :key="`confetti-${i}`"
                                            class="confetti-particle absolute"
                                            :style="{
                                                width: `${Math.random() * 8 + 4}px`,
                                                height: `${Math.random() * 3 + 2}px`,
                                                backgroundColor: getRandomConfettiColor(),
                                                transform: `translate(${(Math.random() - 0.5) * 100}px, ${(Math.random() - 0.5) * 100}px) rotate(${Math.random() * 360}deg)`,
                                                opacity: Math.random() * 0.7 + 0.3,
                                                animationDuration: `${Math.random() * 2 + 1}s`,
                                                animationDelay: `${Math.random() * 0.5}s`
                                            }">
                                        </div>
                                    </div>
                                </div>

                                <!-- Landing Platform (for wins) -->
                                <div v-if="!isAnimating && lastResult.win" 
                                    class="landing-platform absolute left-1/2 transform -translate-x-1/2"
                                    :style="{
                                        bottom: `${getMultiplierPosition(lastResult.multiplierAchieved) - 2}%`,
                                    }">
                                    <div class="w-20 h-4 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg"></div>
                                </div>

                                <!-- Cosmic Creatures (rare appearances) -->
                                <div v-if="showCosmicCreature" 
                                    class="cosmic-creature absolute"
                                    :style="{
                                        left: `${cosmicCreaturePosition.x}%`,
                                        top: `${cosmicCreaturePosition.y}%`,
                                        transform: `scale(${cosmicCreaturePosition.scale})`
                                    }">
                                    <div class="w-12 h-8 bg-purple-500 rounded-full relative overflow-visible opacity-80">
                                        <div class="absolute top-1/2 left-1/4 transform -translate-y-1/2 w-2 h-2 bg-white rounded-full"></div>
                                        <div class="absolute top-1/3 right-1/4 transform -translate-y-1/2 w-3 h-3 bg-cyan-300 rounded-full"></div>
                                        <div class="absolute -bottom-1 left-0 w-full h-2 bg-purple-500 rounded-full flex space-x-1">
                                            <div class="w-2 h-4 bg-purple-400 rounded-full"></div>
                                            <div class="w-2 h-3 bg-purple-400 rounded-full"></div>
                                            <div class="w-2 h-5 bg-purple-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Multiplier Display -->
                                <div v-if="isAnimating" class="absolute top-4 left-1/2 transform -translate-x-1/2 text-4xl font-bold text-white z-10 multiplier-display">
                                    {{ currentMultiplier.toFixed(2) }}×
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Controls and Game History -->
                    <div class="lg:col-span-1 flex flex-col gap-4">
                        <!-- Betting Controls Card -->
                        <div class="bg-slate-800/50 rounded-lg p-4 flex-1">
                            <!-- Compact Controls Layout -->
                            <div class="space-y-4">
                                <!-- Target Multiplier -->
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="text-sm text-white/80">Target Multiplier</label>
                                        <div class="text-sm bg-black/20 px-2 py-1 rounded font-semibold text-white">
                                            Win Chance: {{ winChance.toFixed(2) }}%
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            v-model="targetMultiplier" 
                                            min="1.05" 
                                            max="999" 
                                            step="0.01"
                                            @input="validateMultiplier"
                                            class="w-full p-2 rounded-lg border-2 border-slate-700 bg-black/20 text-white text-lg font-semibold text-center"
                                        >
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-lg font-semibold text-white/80">×</span>
                                    </div>
                                    <div class="w-full mt-1">
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
                                </div>

                                <!-- Bet Amount -->
                                <div>
                                    <label class="text-sm text-white/80 block mb-1">Bet Amount</label>
                                    <div class="flex space-x-2">
                                        <div class="relative flex-1">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lg font-semibold text-white/80">$</span>
                                            <input 
                                                type="number" 
                                                v-model="betAmount" 
                                                min="1" 
                                                step="1"
                                                @input="validateBetAmount"
                                                class="w-full p-2 pl-8 rounded-lg border-2 border-slate-700 bg-black/20 text-white text-lg font-semibold text-center"
                                            >
                                        </div>
                                        <button 
                                            @click="maxBet"
                                            class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition-colors whitespace-nowrap"
                                        >
                                            MAX
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2">
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
                                    </div>
                                </div>

                                <!-- Play Button -->
                                <button 
                                    class="w-full bg-gradient-to-r from-rose-600 to-pink-600 text-white p-3 text-lg font-bold rounded-lg transition-all duration-200 shadow-md shadow-rose-600/30 tracking-wide disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                                    @click="playGame" 
                                    :disabled="!canPlay"
                                >
                                    PLAY
                                </button>
                            </div>
                        </div>

                        <!-- Game History -->
                        <div class="bg-slate-800/50 rounded-lg p-4 flex-1 max-h-[200px]">
                            <h3 class="mt-0 mb-2 text-base text-white/80 font-semibold">Game History</h3>
                            <div class="space-y-1 overflow-y-auto pr-1 max-h-[calc(100%-2rem)]">
                                <div 
                                    v-for="(game, index) in gameHistory" 
                                    :key="index" 
                                    class="grid grid-cols-3 p-2 rounded-md text-sm items-center"
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
                                <div v-if="gameHistory.length === 0" class="text-center p-3 text-white/50">
                                    No games played yet
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '@/store/authStore';
import { useGlobalBalanceStore } from '@/store/globalBalanceStore';
import GameHeader from './Global/GameHeader.vue';
import api from '@/scripts/axios';

export default {
    name: 'Limbo',
    components: {
        GameHeader,
    },
    data() {
        return {
            auth: useAuthStore(),
            globalBalance: useGlobalBalanceStore(),
            // Game state
            targetMultiplier: 2.00,
            betAmount: 10,
            gameHistory: [],
            lastResult: { played: false, win: false, multiplierAchieved: 0 },
            isAnimating: false,

            // Rocket animation state
            currentMultiplier: 1.00,
            animationInterval: null,
            isCrashing: false,
            crashTimeout: null,

            // Planets (multiplier milestones)
            planets: [
                { value: 2, ringClass: 'bg-blue-500' },
                { value: 5, ringClass: 'bg-green-500' },
                { value: 10, ringClass: 'bg-yellow-500' },
                { value: 25, ringClass: 'bg-orange-500' },
                { value: 50, ringClass: 'bg-red-500' },
                { value: 100, ringClass: 'bg-purple-500' }
            ],

            // Cosmic creatures
            showCosmicCreature: false,
            cosmicCreaturePosition: { x: 0, y: 0, scale: 1 },
            cosmicCreatureTimeout: null
        };
    },

    computed: {
        winChance() {
            return (1 / this.targetMultiplier) * 100;
        },

        canPlay() {
            return !this.isAnimating &&
                this.betAmount > 0 &&
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
            }
        },

        halfBet() {
            this.betAmount = Math.floor(this.betAmount / 2);
            if (this.betAmount < 1) this.betAmount = 1;
        },

        doubleBet() {
            this.betAmount = Math.min(this.betAmount * 2, this.auth.user.no_edge_cash);
        },

        maxBet() {
            this.betAmount = this.auth.user.no_edge_cash;
        },

        // Animation methods
        startRocketAnimation() {
            this.isAnimating = true;
            this.isCrashing = false;
            this.currentMultiplier = 1.00;

            // Clear any existing intervals/timeouts
            this.clearAnimationTimers();

            // Start the animation
            this.animationInterval = setInterval(() => {
                // Increase the multiplier at a rate that feels good
                // Slower at the beginning, faster as it goes up
                const increment = this.currentMultiplier < 2 ? 0.01 :
                    this.currentMultiplier < 5 ? 0.03 :
                        this.currentMultiplier < 10 ? 0.05 : 0.1;

                this.currentMultiplier += increment;

                // Check for cosmic creature appearance (rare)
                if (this.currentMultiplier > 10 && Math.random() < 0.005 && !this.showCosmicCreature) {
                    this.showCosmicCreature = true;
                    this.cosmicCreaturePosition = {
                        x: Math.random() * 80 + 10,
                        y: Math.random() * 60 + 20,
                        scale: Math.random() * 0.5 + 0.8
                    };

                    // Hide the creature after a few seconds
                    this.cosmicCreatureTimeout = setTimeout(() => {
                        this.showCosmicCreature = false;
                    }, 3000);
                }

                // Play sound effects when passing milestone multipliers
                this.planets.forEach(planet => {
                    if (this.currentMultiplier >= planet.value && this.currentMultiplier - increment < planet.value) {
                        // Would play sound here if we had audio
                        console.log(`Passed ${planet.value}x multiplier!`);
                    }
                });

            }, 50);
        },

        stopRocketAnimation(win, multiplierAchieved) {
            // Clear the animation interval
            clearInterval(this.animationInterval);

            if (!win) {
                // Crash animation
                this.isCrashing = true;
                this.crashTimeout = setTimeout(() => {
                    this.isAnimating = false;
                }, 1500);
            } else {
                // Win animation - just stop at the current height
                setTimeout(() => {
                    this.isAnimating = false;
                }, 1000);
            }
        },

        clearAnimationTimers() {
            if (this.animationInterval) clearInterval(this.animationInterval);
            if (this.crashTimeout) clearTimeout(this.crashTimeout);
            if (this.cosmicCreatureTimeout) clearTimeout(this.cosmicCreatureTimeout);
        },

        // Helper methods for the animation
        getMultiplierPosition(multiplier) {
            // Convert multiplier to a position percentage (0-100)
            // Using a logarithmic scale to make it look better
            // 1x = 0%, 2x = 20%, 5x = 40%, 10x = 60%, 100x = 90%, 1000x = 100%
            const logBase = 10;
            const minMultiplier = 1;
            const maxMultiplier = 1000;

            const logMin = Math.log(minMultiplier) / Math.log(logBase);
            const logMax = Math.log(maxMultiplier) / Math.log(logBase);
            const logValue = Math.log(Math.max(multiplier, minMultiplier)) / Math.log(logBase);

            return ((logValue - logMin) / (logMax - logMin)) * 100;
        },

        getTrailColorClass(multiplier) {
            // Return different color classes based on the multiplier
            if (multiplier < 2) return 'bg-blue-500';
            if (multiplier < 5) return 'bg-green-500';
            if (multiplier < 10) return 'bg-yellow-500';
            if (multiplier < 50) return 'bg-orange-500';
            return 'bg-red-500';
        },

        getRandomExplosionColor() {
            const colors = [
                '#f97316', // orange-500
                '#eab308', // yellow-500
                '#ef4444', // red-500
                '#ec4899', // pink-500
                '#ffffff'  // white
            ];
            return colors[Math.floor(Math.random() * colors.length)];
        },

        getRandomConfettiColor() {
            const colors = [
                '#f59e0b', // amber-500
                '#10b981', // emerald-500
                '#3b82f6', // blue-500
                '#ec4899', // pink-500
                '#a855f7', // purple-500
                '#f97316', // orange-500
                '#eab308'  // yellow-500
            ];
            return colors[Math.floor(Math.random() * colors.length)];
        },

        async playGame() {
            if (!this.canPlay) return;

            // Start the rocket animation
            this.startRocketAnimation();

            try {
                await api.get('/sanctum/csrf-cookie');
                const response = await api.post('/api/limbo/play', {
                    betAmount: this.betAmount,
                    targetMultiplier: this.targetMultiplier
                });

                if (response.status !== 200) {
                    // Handle error (show notification or similar)
                    this.stopRocketAnimation(false, 1);
                    return;
                }

                // Wait for animation to reach the result multiplier
                const checkInterval = setInterval(() => {
                    if (this.currentMultiplier >= response.data.multiplierAchieved) {
                        clearInterval(checkInterval);

                        // Stop the animation with the result
                        this.stopRocketAnimation(response.data.win, response.data.multiplierAchieved);

                        // Update the game state
                        this.lastResult = {
                            played: true,
                            win: response.data.win,
                            multiplierAchieved: response.data.multiplierAchieved
                        };

                        this.gameHistory.unshift({
                            targetMultiplier: this.targetMultiplier,
                            multiplierAchieved: response.data.multiplierAchieved,
                            betAmount: this.betAmount,
                            win: response.data.win
                        });

                        if (this.gameHistory.length > 10) {
                            this.gameHistory.pop();
                        }

                        // Update user data
                        this.auth.fetchUser();
                        this.globalBalance.loadGlobalPoolData();
                    }
                }, 50);

            } catch (e) {
                // Handle network error
                this.stopRocketAnimation(false, 1);
            }
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
    },

    beforeUnmount() {
        // Clean up any running animations
        this.clearAnimationTimers();
    }
};
</script>

<style>
/* Custom range input styling */
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

/* Rocket Animation Styles */
.rocket-animation-container {
    perspective: 1000px;
    overflow: hidden;
}

/* Stars */
.star {
    transition: all 0.3s ease;
}

@keyframes twinkle {

    0%,
    100% {
        opacity: 0.3;
    }

    50% {
        opacity: 1;
    }
}

.animate-twinkle {
    animation: twinkle 2s infinite;
}

@keyframes streak {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(200px);
        opacity: 0;
    }
}

.animate-streak {
    animation: streak 1s linear infinite;
}

/* Planets */
.planet-ring {
    width: 24px;
    height: 24px;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.planet-reached .planet-ring {
    opacity: 1;
    box-shadow: 0 0 10px 2px currentColor;
    animation: pulse 1s ease-out;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }

    50% {
        transform: scale(1.5);
        opacity: 1;
    }

    100% {
        transform: scale(1);
        opacity: 0.7;
    }
}

/* Rocket */
.rocket {
    transition: transform 0.2s ease;
}

.rocket-flying {
    animation: slight-wobble 0.5s ease infinite;
}

.rocket-crashed {
    animation: shake 0.2s ease infinite;
}

.rocket-landed {
    animation: bounce 1s ease;
}

@keyframes slight-wobble {

    0%,
    100% {
        transform: translateX(-50%) rotate(-1deg);
    }

    50% {
        transform: translateX(-50%) rotate(1deg);
    }
}

@keyframes shake {

    0%,
    100% {
        transform: translateX(-50%) rotate(-5deg);
    }

    50% {
        transform: translateX(-50%) rotate(5deg);
    }
}

@keyframes bounce {

    0%,
    100% {
        transform: translateX(-50%) translateY(0);
    }

    50% {
        transform: translateX(-50%) translateY(-10px);
    }
}

/* Trail particles */
.trail-particle {
    animation: fade-out 0.5s linear infinite;
}

@keyframes fade-out {
    0% {
        opacity: 1;
    }

    100% {
        opacity: 0;
    }
}

/* Explosion */
.explosion-particle {
    animation: explode 1s ease-out forwards;
}

@keyframes explode {
    0% {
        transform: translate(0, 0);
        opacity: 1;
    }

    100% {
        transform: translate(var(--tx, 100px), var(--ty, 100px));
        opacity: 0;
    }
}

/* Confetti */
.confetti-particle {
    animation: fall-rotate 2s ease-in-out forwards;
}

@keyframes fall-rotate {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
    }

    100% {
        transform: translateY(100px) rotate(360deg);
        opacity: 0;
    }
}

/* Multiplier display */
.multiplier-display {
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
}
</style>