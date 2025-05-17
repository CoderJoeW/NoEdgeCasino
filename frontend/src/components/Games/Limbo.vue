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
                </div>

                <!-- Main Game Area: Side by Side Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Left: Enhanced Rocket Animation -->
                    <div class="lg:col-span-2">
                        <div class="relative h-[calc(100vh-300px)] min-h-[400px] max-h-[600px] rounded-lg overflow-hidden border border-slate-700 shadow-inner">
                            <!-- Simplified Space Background -->
                            <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-indigo-950/10 to-slate-950 overflow-hidden">
                                <!-- Subtle Nebula -->
                                <div class="absolute w-full h-full opacity-5">
                                    <div class="absolute top-1/4 left-1/4 w-1/2 h-1/2 rounded-full bg-purple-500/20 blur-3xl"></div>
                                    <div class="absolute top-1/3 right-1/4 w-1/3 h-1/3 rounded-full bg-blue-500/20 blur-3xl"></div>
                                </div>
                                
                                <!-- Static Stars -->
                                <div class="absolute inset-0">
                                    <div v-for="i in 80" :key="`star-${i}`" 
                                        class="absolute rounded-full bg-white"
                                        :style="{
                                            left: `${Math.random() * 100}%`,
                                            top: `${Math.random() * 100}%`,
                                            width: `${Math.random() * 2 + 1}px`,
                                            height: `${Math.random() * 2 + 1}px`,
                                            opacity: Math.random() * 0.5 + 0.3
                                        }">
                                    </div>
                                    
                                    <!-- Larger Stars with Subtle Glow -->
                                    <div v-for="i in 15" :key="`glow-star-${i}`" 
                                        class="absolute rounded-full bg-white animate-pulse-subtle"
                                        :style="{
                                            left: `${Math.random() * 100}%`,
                                            top: `${Math.random() * 100}%`,
                                            width: `${Math.random() * 2.5 + 1.5}px`,
                                            height: `${Math.random() * 2.5 + 1.5}px`,
                                            boxShadow: `0 0 ${Math.random() * 3 + 2}px rgba(255, 255, 255, 0.5)`,
                                            animationDuration: `${Math.random() * 3 + 3}s`,
                                            animationDelay: `${Math.random() * 2}s`
                                        }">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced Multiplier Track -->
                            <div class="absolute left-1/2 bottom-0 top-0 w-0.5 bg-white/20 transform -translate-x-1/2"></div>
                            
                            <!-- Improved Multiplier Markers -->
                            <div class="absolute inset-0">
                                <div v-for="(marker, index) in multiplierMarkers" :key="`marker-${index}`"
                                    class="absolute left-0 right-0 h-0.5 transition-all duration-300"
                                    :class="{
                                        'bg-white/20': true,
                                        'bg-white/40': isAnimating && currentMultiplier >= marker.value
                                    }"
                                    :style="{
                                        bottom: `${getMultiplierPosition(marker.value)}%`,
                                    }">
                                    <!-- Enhanced Marker Dot -->
                                    <div class="absolute -left-2 -top-2 w-4 h-4 rounded-full transition-all duration-300 flex items-center justify-center"
                                        :class="[
                                            { 'bg-slate-800 border-2': true },
                                            { 'border-white/40': !(isAnimating && currentMultiplier >= marker.value) },
                                            { 'border-white': isAnimating && currentMultiplier >= marker.value },
                                            { 'shadow-glow': isAnimating && currentMultiplier >= marker.value }
                                        ]"
                                        :style="{
                                            '--glow-color': 'rgba(255, 255, 255, 0.5)'
                                        }">
                                        <div class="w-2 h-2 rounded-full transition-all duration-300"
                                            :class="[
                                                marker.bgClass,
                                                { 'opacity-50': !(isAnimating && currentMultiplier >= marker.value) },
                                                { 'opacity-100': isAnimating && currentMultiplier >= marker.value }
                                            ]">
                                        </div>
                                    </div>
                                    
                                    <!-- Enhanced Marker Label -->
                                    <div class="absolute left-4 -top-3 px-1.5 py-0.5 rounded transition-all duration-300 text-sm"
                                        :class="{
                                            'bg-slate-800/50 text-white/60': !(isAnimating && currentMultiplier >= marker.value),
                                            'bg-slate-800/80 text-white font-medium': isAnimating && currentMultiplier >= marker.value
                                        }">
                                        {{ marker.value }}×
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fixed Rocket -->
                            <div 
                                class="absolute left-1/2 transform -translate-x-1/2 transition-all duration-100 ease-linear z-10"
                                :class="{
                                    'rocket-flying': isAnimating && !isCrashing,
                                    'rocket-crashed': isCrashing,
                                    'rocket-landed': !isAnimating && lastResult.win && lastResult.played
                                }"
                                :style="{
                                    bottom: `${rocketPosition}%`,
                                    opacity: isAnimating || lastResult.played ? 1 : 0
                                }">
                                
                                <!-- Rocket Body with Fixed Design -->
                                <div class="relative">
                                    <!-- Improved Rocket Flame -->
                                    <div v-if="isAnimating || (lastResult.played && !lastResult.win && isCrashing)" 
                                        class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 w-8 overflow-hidden">
                                        <!-- Outer Glow -->
                                        <div class="absolute -inset-2 bg-orange-500/30 rounded-full filter blur-md"></div>
                                        
                                        <!-- Main Flame -->
                                        <div class="relative w-8 h-16">
                                            <div class="absolute inset-0 bg-gradient-to-t from-transparent via-yellow-500 to-red-500 rounded-b-full animate-flame"></div>
                                            
                                            <!-- Inner Flame -->
                                            <div class="absolute inset-2 bg-gradient-to-t from-transparent via-white to-yellow-300 rounded-b-full animate-inner-flame"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Fixed Rocket Ship -->
                                    <svg width="40" height="60" viewBox="0 0 40 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Rocket Body -->
                                        <path d="M20 0C12 0 8 8 8 20V40H32V20C32 8 28 0 20 0Z" fill="#E11D48"/>
                                        
                                        <!-- Rocket Window -->
                                        <circle cx="20" cy="20" r="6" fill="#7DD3FC"/>
                                        <circle cx="20" cy="20" r="4" fill="#0EA5E9"/>
                                        <circle cx="18" cy="18" r="1.5" fill="white"/>
                                        
                                        <!-- Rocket Fins -->
                                        <path d="M8 40L0 50V60H8V40Z" fill="#BE123C"/>
                                        <path d="M32 40L40 50V60H32V40Z" fill="#BE123C"/>
                                        
                                        <!-- Rocket Bottom -->
                                        <rect x="8" y="40" width="24" height="10" fill="#BE123C"/>
                                        <path d="M12 50H28C28 55 24 58 20 58C16 58 12 55 12 50Z" fill="#9F1239"/>
                                        <circle cx="20" cy="54" r="2" fill="#7F1D1D"/>
                                    </svg>
                                </div>
                                
                                <!-- Fixed Explosion Effect -->
                                <div v-if="isCrashing" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40">
                                    <!-- Central Flash -->
                                    <div class="absolute inset-0 bg-white rounded-full animate-explosion-flash"></div>
                                    
                                    <!-- Shockwave Ring -->
                                    <div class="absolute inset-0 border-2 border-white/80 rounded-full animate-shockwave"></div>
                                    
                                    <!-- Explosion Particles -->
                                    <div v-for="i in 24" :key="`explosion-${i}`"
                                        class="absolute w-2 h-2 rounded-full"
                                        :style="{
                                            backgroundColor: getRandomExplosionColor(),
                                            left: '50%',
                                            top: '50%',
                                            transform: `translate(-50%, -50%)`,
                                            opacity: 1,
                                            '--angle': `${i * 15}deg`,
                                            '--distance': `${Math.random() * 30 + 30}px`,
                                            '--duration': `${Math.random() * 0.5 + 0.7}s`,
                                            animation: 'explosion-particle var(--duration) ease-out forwards'
                                        }">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fixed UFO with Smoother Animation -->
                            <div v-if="showCosmicCreature" 
                                class="absolute transition-all duration-1000 ease-in-out z-20"
                                :style="{
                                    left: `${cosmicCreaturePosition.x}%`,
                                    top: `${cosmicCreaturePosition.y}%`,
                                    transform: `translateY(${cosmicCreatureFloat}px)`,
                                }">
                                <!-- UFO with Improved Design -->
                                <div class="relative">
                                    <!-- UFO Glow -->
                                    <div class="absolute -inset-2 bg-cyan-400/20 rounded-full filter blur-md"></div>
                                    
                                    <!-- UFO Body -->
                                    <svg width="80" height="40" viewBox="0 0 80 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- UFO Body -->
                                        <ellipse cx="40" cy="20" rx="30" ry="10" fill="#94A3B8" />
                                        <ellipse cx="40" cy="20" rx="20" ry="6" fill="#CBD5E1" />
                                        
                                        <!-- UFO Dome -->
                                        <ellipse cx="40" cy="15" rx="15" ry="10" fill="#7DD3FC" fill-opacity="0.7" />
                                        
                                        <!-- UFO Lights -->
                                        <circle cx="25" cy="22" r="2" fill="#FBBF24" class="animate-ufo-light" style="animation-delay: 0s" />
                                        <circle cx="35" cy="24" r="2" fill="#FBBF24" class="animate-ufo-light" style="animation-delay: 0.2s" />
                                        <circle cx="45" cy="24" r="2" fill="#FBBF24" class="animate-ufo-light" style="animation-delay: 0.4s" />
                                        <circle cx="55" cy="22" r="2" fill="#FBBF24" class="animate-ufo-light" style="animation-delay: 0.6s" />
                                        
                                        <!-- UFO Beam -->
                                        <path v-if="cosmicCreaturePosition.showBeam" d="M40 25L50 40H30L40 25Z" fill="url(#beam-gradient)" />
                                        
                                        <!-- Gradients -->
                                        <defs>
                                            <linearGradient id="beam-gradient" x1="40" y1="25" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                                                <stop offset="0" stop-color="#7DD3FC" stop-opacity="0.8"/>
                                                <stop offset="1" stop-color="#7DD3FC" stop-opacity="0"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Enhanced Multiplier Display -->
                            <div v-if="isAnimating || (lastResult.played && !isAnimating)" 
                                class="absolute top-4 left-1/2 transform -translate-x-1/2 text-5xl font-bold z-30 multiplier-display transition-all duration-300"
                                :class="{
                                    'text-white': isAnimating && !isCrashing,
                                    'text-red-500': isCrashing,
                                    'text-green-500': !isAnimating && lastResult.win && lastResult.played,
                                    'text-red-500': !isAnimating && !lastResult.win && lastResult.played
                                }">
                                <div class="relative">
                                    {{ displayMultiplier.toFixed(2) }}×
                                    <div v-if="isAnimating && !isCrashing" class="absolute -inset-4 bg-white/5 rounded-full filter blur-xl animate-pulse-slow"></div>
                                </div>
                            </div>
                            
                            <!-- Target Multiplier Indicator -->
                            <div v-if="isAnimating" 
                                class="absolute left-0 right-0 h-0.5 bg-amber-500/70 z-5 transition-all duration-300"
                                :style="{
                                    bottom: `${getMultiplierPosition(targetMultiplier)}%`,
                                }">
                                <div class="absolute right-4 -top-3 px-2 py-0.5 bg-amber-500/20 text-amber-300 text-sm rounded">
                                    Target: {{ targetMultiplier.toFixed(2) }}×
                                </div>
                                <div class="absolute -right-1 -top-1.5 w-3 h-3 bg-amber-500 rounded-full"></div>
                            </div>

                            <!-- Final Multiplier Indicator (shown after game ends) -->
                            <div v-if="lastResult.played && !isAnimating" 
                                class="absolute left-0 right-0 h-0.5 z-5 transition-all duration-300"
                                :class="{
                                    'bg-amber-500/70': lastResult.win,
                                    'bg-red-500/70': !lastResult.win
                                }"
                                :style="{
                                    bottom: `${getMultiplierPosition(lastResult.multiplierAchieved)}%`,
                                }">
                                <div class="absolute right-4 -top-3 px-2 py-0.5 text-sm rounded"
                                    :class="{
                                        'bg-amber-500/20 text-amber-300': lastResult.win,
                                        'bg-red-500/20 text-red-300': !lastResult.win
                                    }">
                                    Final: {{ lastResult.multiplierAchieved.toFixed(2) }}×
                                </div>
                                <div class="absolute -right-1 -top-1.5 w-3 h-3 rounded-full"
                                    :class="{
                                        'bg-amber-500': lastResult.win,
                                        'bg-red-500': !lastResult.win
                                    }">
                                </div>
                            </div>
                            
                            <!-- Very Subtle Parallax (only when rocket is moving) -->
                            <div v-if="isAnimating" class="absolute inset-0 pointer-events-none overflow-hidden opacity-30">
                                <div class="absolute inset-0" :style="{ transform: `translateY(${rocketPosition * 0.02}px)` }"></div>
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
                                    :disabled="!canPlay || isPlacingBet"
                                >
                                    <span v-if="!isPlacingBet">Place Bet</span>
                                    <span v-else class="flex items-center justify-center">
                                        <svg class="animate-spin h-6 w-6 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                        Bet in progress...
                                    </span>
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

        <!-- Profit Tracker Popup -->
        <div 
            v-if="showProfitTracker" 
            class="fixed top-20 right-4 w-96 bg-slate-800 rounded-xl shadow-2xl border border-slate-700 z-50"
            :style="{ top: profitTrackerPosition.y + 'px', left: profitTrackerPosition.x + 'px' }"
            @mousedown="startDrag"
        >
            <!-- Header with drag handle -->
            <div class="flex items-center justify-between p-3 bg-slate-700 rounded-t-xl cursor-move" ref="profitTrackerHeader">
                <h3 class="text-white font-semibold">Profit Tracker</h3>
                <div class="flex space-x-2">
                    <button @click="showProfitTracker = false" class="text-white/70 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Profit/Loss Display -->
            <div class="p-4 border-b border-slate-700">
                <div class="flex justify-between items-center">
                    <span class="text-white/80">Session Profit/Loss:</span>
                    <span 
                        class="text-2xl font-bold"
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

            <!-- Profit Chart -->
            <div class="p-4 h-48 relative">
                <svg class="w-full h-full" viewBox="0 0 300 100" preserveAspectRatio="none">
                    <!-- Chart grid lines -->
                    <line x1="0" y1="50" x2="300" y2="50" stroke="#475569" stroke-width="1" stroke-dasharray="2,2" />
                    <line v-for="i in 5" :key="`grid-${i}`" 
                        :x1="i * 60" :y1="0" :x2="i * 60" :y2="100" 
                        stroke="#475569" stroke-width="1" stroke-dasharray="2,2" />
                    
                    <!-- Zero line -->
                    <line x1="0" y1="50" x2="300" y2="50" stroke="#94A3B8" stroke-width="1" />
                    
                    <!-- Profit line -->
                    <polyline 
                        :points="profitChartPoints" 
                        fill="none" 
                        stroke="url(#profit-gradient)" 
                        stroke-width="2" 
                    />
                    
                    <!-- Gradient definition -->
                    <defs>
                        <linearGradient id="profit-gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#10B981" />
                            <stop offset="50%" stop-color="#FFFFFF" />
                            <stop offset="100%" stop-color="#EF4444" />
                        </linearGradient>
                    </defs>
                </svg>
                
                <!-- Chart labels -->
                <div class="absolute top-0 left-0 text-xs text-white/60">+${{ profitChartMax.toFixed(2) }}</div>
                <div class="absolute bottom-0 left-0 text-xs text-white/60">-${{ Math.abs(profitChartMin).toFixed(2) }}</div>
                <div class="absolute top-1/2 left-0 transform -translate-y-1/2 text-xs text-white/60">$0.00</div>
            </div>

            <!-- Session Stats -->
            <div class="p-4 grid grid-cols-2 gap-4 border-t border-slate-700">
                <div class="flex flex-col">
                    <span class="text-xs text-white/60">Total Wagered</span>
                    <span class="text-base font-semibold text-white">${{ sessionStats.totalWagered.toFixed(2) }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-white/60">Total Games</span>
                    <span class="text-base font-semibold text-white">{{ sessionStats.totalGames }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-white/60">Wins</span>
                    <span class="text-base font-semibold text-emerald-400">{{ sessionStats.wins }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-white/60">Losses</span>
                    <span class="text-base font-semibold text-red-400">{{ sessionStats.losses }}</span>
                </div>
            </div>

            <!-- Reset Button -->
            <div class="p-4 border-t border-slate-700">
                <button 
                    @click="resetSessionStats"
                    class="w-full p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition-colors"
                >
                    Reset Session Stats
                </button>
            </div>
        </div>

        <!-- Turbo Mode Toggle Button -->
        <button 
            @click="toggleTurboMode = !toggleTurboMode"
            :class="[
                'fixed bottom-18 right-4 p-3 rounded-full shadow-lg transition-colors',
                toggleTurboMode ? 'bg-green-600 ring-4 ring-green-400 shadow-green-400/70 animate-turbo-glow' : 'bg-slate-700 hover:bg-slate-600 text-white'
            ]"
            style="z-index: 60;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="toggleTurboMode ? '#22ff00' : 'none'" :stroke="toggleTurboMode ? '#22ff00' : 'currentColor'" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" :fill="toggleTurboMode ? '#22ff00' : 'none'" :stroke="toggleTurboMode ? '#22ff00' : 'currentColor'"/>
            </svg>
        </button>

        <!-- Profit Tracker Toggle Button -->
        <button 
            @click="showProfitTracker = !showProfitTracker"
            class="fixed bottom-4 right-4 p-3 bg-slate-700 hover:bg-slate-600 text-white rounded-full shadow-lg transition-colors"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
        </button>
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
            displayMultiplier: 1.00,
            rocketPosition: 0,
            animationInterval: null,
            multiplierUpdateInterval: null,
            isCrashing: false,
            crashTimeout: null,

            // Multiplier markers with enhanced colors
            multiplierMarkers: [
                { value: 1.5, bgClass: 'bg-blue-500' },
                { value: 2, bgClass: 'bg-blue-500' },
                { value: 3, bgClass: 'bg-blue-500' },
                { value: 5, bgClass: 'bg-green-500' },
                { value: 10, bgClass: 'bg-yellow-500' },
                { value: 25, bgClass: 'bg-orange-500' },
                { value: 50, bgClass: 'bg-red-500' },
                { value: 100, bgClass: 'bg-purple-500' },
                { value: 200, bgClass: 'bg-fuchsia-500' },
                { value: 500, bgClass: 'bg-pink-500' }
            ],

            // Cosmic creature
            showCosmicCreature: false,
            cosmicCreaturePosition: { x: 0, y: 0, showBeam: false },
            cosmicCreatureTimeout: null,
            cosmicCreatureFloat: 0,
            cosmicCreatureFloatInterval: null,

            showProfitTracker: false,
            toggleTurboMode: false,
            isPlacingBet: false,
            profitTrackerPosition: { x: 20, y: 20 },
            isDragging: false,
            dragOffset: { x: 0, y: 0 },
            sessionHistory: [],
            sessionStats: {
                profit: 0,
                totalWagered: 0,
                totalGames: 0,
                wins: 0,
                losses: 0
            }
        };
    },

    computed: {
        winChance() {
            return (1 / this.targetMultiplier) * 100;
        },

        winRate() {
            if (this.sessionStats.wins === 0 || this.sessionStats.totalGames === 0) {
                return 0;
            }

            return ((this.sessionStats.wins / this.sessionStats.totalGames) * 100).toFixed(2);
        },

        canPlay() {
            return !this.isAnimating &&
                this.betAmount > 0 &&
                this.targetMultiplier >= 1.05 &&
                this.targetMultiplier <= 999;
        },

        // Profit chart data
        profitChartPoints() {
            if (this.sessionHistory.length === 0) {
                return "0,50 300,50"; // Flat line at zero
            }

            const maxEntries = 30; // Show up to 30 most recent games
            const entries = this.sessionHistory.slice(-maxEntries);
            const step = 300 / Math.max(entries.length - 1, 1);

            return entries.map((profit, index) => {
                const x = index * step;
                // Map profit to y coordinate (50 is the center/zero line)
                const range = Math.max(Math.abs(this.profitChartMin), Math.abs(this.profitChartMax));
                const y = 50 - (profit / range * 50);
                return `${x},${y}`;
            }).join(' ');
        },

        profitChartMax() {
            if (this.sessionHistory.length === 0) return 10;
            return Math.max(10, ...this.sessionHistory);
        },

        profitChartMin() {
            if (this.sessionHistory.length === 0) return -10;
            return Math.min(-10, ...this.sessionHistory);
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
            this.displayMultiplier = 1.00;
            this.rocketPosition = 0;

            // Clear any existing intervals/timeouts
            this.clearAnimationTimers();

            // Start the animation with smooth updates
            this.animationInterval = setInterval(() => {
                // Increase the multiplier at a rate that feels good
                // Slower at the beginning, faster as it goes up
                const increment = this.currentMultiplier < 2 ? 0.01 :
                    this.currentMultiplier < 5 ? 0.03 :
                        this.currentMultiplier < 10 ? 0.05 : 0.1;

                this.currentMultiplier += increment;

                // Update the rocket position smoothly
                this.rocketPosition = this.getMultiplierPosition(this.currentMultiplier);

                // Check for cosmic creature appearance (rare)
                if (this.currentMultiplier > 5 && Math.random() < 0.005 && !this.showCosmicCreature) {
                    this.showCosmicCreature = true;

                    // Position the UFO
                    this.cosmicCreaturePosition = {
                        x: Math.random() * 60 + 20, // Keep it more centered
                        y: Math.random() * 40 + 20, // Keep it in the middle area
                        showBeam: Math.random() > 0.5 // 50% chance to show beam
                    };

                    // Start the floating animation
                    let direction = 1;
                    let floatAmount = 0;

                    this.cosmicCreatureFloatInterval = setInterval(() => {
                        floatAmount += 0.2 * direction;
                        if (floatAmount > 10 || floatAmount < -10) {
                            direction *= -1;
                        }
                        this.cosmicCreatureFloat = floatAmount;
                    }, 50);

                    // Hide the creature after a few seconds
                    this.cosmicCreatureTimeout = setTimeout(() => {
                        this.showCosmicCreature = false;
                        clearInterval(this.cosmicCreatureFloatInterval);
                    }, 4000);
                }

                // Update the display multiplier (smoother than the actual multiplier)
                this.displayMultiplier = this.currentMultiplier;

            }, 50); // 20 FPS for smooth animation
        },

        stopRocketAnimation(win, multiplierAchieved) {
            // Clear the animation interval
            clearInterval(this.animationInterval);

            // Set the final multiplier
            this.currentMultiplier = multiplierAchieved;
            this.displayMultiplier = multiplierAchieved;
            this.rocketPosition = this.getMultiplierPosition(multiplierAchieved);

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
            if (this.multiplierUpdateInterval) clearInterval(this.multiplierUpdateInterval);
            if (this.crashTimeout) clearTimeout(this.crashTimeout);
            if (this.cosmicCreatureTimeout) clearTimeout(this.cosmicCreatureTimeout);
            if (this.cosmicCreatureFloatInterval) clearInterval(this.cosmicCreatureFloatInterval);
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
            if (!this.canPlay || this.isPlacingBet) return;
            this.isPlacingBet = true;

            try {
                await api.get('/sanctum/csrf-cookie');
                const response = await api.post('/api/limbo/play', {
                    betAmount: this.betAmount,
                    targetMultiplier: this.targetMultiplier
                });

                if (response.status !== 200) {
                    // Handle error (show notification or similar)
                    this.stopRocketAnimation(false, 1);
                    this.isPlacingBet = false;
                    return;
                }

                // TURBO MODE: Skip animation and display result instantly
                if (this.toggleTurboMode) {
                    // Instantly set state to the end result
                    this.isAnimating = false;
                    this.isCrashing = !response.data.win;
                    this.currentMultiplier = response.data.multiplierAchieved;
                    this.displayMultiplier = response.data.multiplierAchieved;
                    this.rocketPosition = this.getMultiplierPosition(response.data.multiplierAchieved);
                    this.lastResult = {
                        played: true,
                        win: response.data.win,
                        multiplierAchieved: response.data.multiplierAchieved
                    };
                    const gameResult = {
                        targetMultiplier: this.targetMultiplier,
                        multiplierAchieved: response.data.multiplierAchieved,
                        betAmount: this.betAmount,
                        win: response.data.win
                    };
                    this.gameHistory.unshift(gameResult);
                    if (this.gameHistory.length > 10) {
                        this.gameHistory.pop();
                    }
                    this.updateSessionStats(gameResult);
                    this.auth.fetchUser();
                    this.globalBalance.loadGlobalPoolData();
                    this.isPlacingBet = false;
                    return;
                }

                // Normal mode: play animation
                this.startRocketAnimation();

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

                        const gameResult = {
                            targetMultiplier: this.targetMultiplier,
                            multiplierAchieved: response.data.multiplierAchieved,
                            betAmount: this.betAmount,
                            win: response.data.win
                        };

                        this.gameHistory.unshift(gameResult);
                        if (this.gameHistory.length > 10) {
                            this.gameHistory.pop();
                        }

                        // Update session stats
                        this.updateSessionStats(gameResult);

                        // Update user data
                        this.auth.fetchUser();
                        this.globalBalance.loadGlobalPoolData();
                        this.isPlacingBet = false;
                    }
                }, 50);

            } catch (e) {
                // Handle network error
                this.stopRocketAnimation(false, 1);
                this.isPlacingBet = false;
            }
        },

        // Session stats methods
        updateSessionStats(gameResult) {
            // Update running profit
            const profitChange = gameResult.win
                ? gameResult.betAmount * (gameResult.targetMultiplier - 1)
                : -gameResult.betAmount;

            // Update session history for chart
            const currentProfit = this.sessionHistory.length > 0
                ? this.sessionHistory[this.sessionHistory.length - 1]
                : 0;
            this.sessionHistory.push(currentProfit + profitChange);

            // Update session stats
            this.sessionStats.profit += profitChange;
            this.sessionStats.totalWagered += gameResult.betAmount;
            this.sessionStats.totalGames++;
            if (gameResult.win) {
                this.sessionStats.wins++;
            } else {
                this.sessionStats.losses++;
            }
        },

        resetSessionStats() {
            this.sessionHistory = [];
            this.sessionStats = {
                profit: 0,
                totalWagered: 0,
                totalGames: 0,
                wins: 0,
                losses: 0
            };
        },

        // Profit tracker drag functionality
        startDrag(event) {
            // Only start drag if clicking on the header
            if (event.target.closest('.cursor-move')) {
                this.isDragging = true;
                const rect = event.target.getBoundingClientRect();
                this.dragOffset = {
                    x: event.clientX - this.profitTrackerPosition.x,
                    y: event.clientY - this.profitTrackerPosition.y
                };

                // Add event listeners for drag and drop
                document.addEventListener('mousemove', this.onDrag);
                document.addEventListener('mouseup', this.stopDrag);
            }
        },

        onDrag(event) {
            if (this.isDragging) {
                this.profitTrackerPosition = {
                    x: event.clientX - this.dragOffset.x,
                    y: event.clientY - this.dragOffset.y
                };
            }
        },

        stopDrag() {
            this.isDragging = false;
            document.removeEventListener('mousemove', this.onDrag);
            document.removeEventListener('mouseup', this.stopDrag);
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

        // Initialize profit tracker position
        this.profitTrackerPosition = {
            x: window.innerWidth - 420,
            y: 20
        };
    },

    beforeUnmount() {
        // Clean up any running animations
        this.clearAnimationTimers();

        // Remove event listeners
        document.removeEventListener('mousemove', this.onDrag);
        document.removeEventListener('mouseup', this.stopDrag);
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
.rocket-flying {
    animation: slight-wobble 3s ease-in-out infinite;
}

.rocket-crashed {
    animation: shake 0.2s ease-in-out infinite;
}

.rocket-landed {
    animation: bounce 1s ease-out;
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

/* Multiplier display */
.multiplier-display {
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
}

/* Subtle star pulsing */
@keyframes pulse-subtle {

    0%,
    100% {
        opacity: 0.5;
    }

    50% {
        opacity: 1;
    }
}

.animate-pulse-subtle {
    animation: pulse-subtle 3s infinite;
}

/* Slow pulse for multiplier */
@keyframes pulse-slow {

    0%,
    100% {
        opacity: 0.2;
    }

    50% {
        opacity: 0.4;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 2s infinite;
}

/* Flame animation */
@keyframes flame {

    0%,
    100% {
        height: 16px;
        border-radius: 0 0 50% 50%;
    }

    50% {
        height: 18px;
        border-radius: 0 0 40% 40%;
    }
}

.animate-flame {
    animation: flame 0.2s infinite;
}

@keyframes inner-flame {

    0%,
    100% {
        height: 12px;
        opacity: 0.8;
    }

    50% {
        height: 14px;
        opacity: 1;
    }
}

.animate-inner-flame {
    animation: inner-flame 0.15s infinite;
}

/* Explosion animation */
@keyframes explosion-flash {
    0% {
        opacity: 1;
        transform: scale(0.1);
    }

    50% {
        opacity: 0.8;
        transform: scale(1);
    }

    100% {
        opacity: 0;
        transform: scale(1.5);
    }
}

.animate-explosion-flash {
    animation: explosion-flash 0.5s ease-out forwards;
}

@keyframes shockwave {
    0% {
        transform: scale(0.1);
        opacity: 1;
    }

    100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-shockwave {
    animation: shockwave 0.6s ease-out forwards;
}

@keyframes explosion-particle {
    0% {
        transform: translate(-50%, -50%);
        opacity: 1;
    }

    100% {
        transform: translate(calc(-50% + cos(var(--angle)) * var(--distance)),
                calc(-50% + sin(var(--angle)) * var(--distance)));
        opacity: 0;
    }
}

/* UFO light animation */
@keyframes ufo-light {

    0%,
    100% {
        opacity: 0.5;
        transform: scale(1);
    }

    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

.animate-ufo-light {
    animation: ufo-light 1s infinite;
}

/* Shadow glow effect */
.shadow-glow {
    box-shadow: 0 0 8px var(--glow-color, rgba(255, 255, 255, 0.5));
}

.animate-turbo-glow {
    animation: turbo-glow 1s infinite alternate;
}

@keyframes turbo-glow {
    0% {
        box-shadow: 0 0 0px #22ff00, 0 0 20px #22ff00, 0 0 40px #22ff00;
    }

    100% {
        box-shadow: 0 0 16px #22ff00, 0 0 32px #22ff00, 0 0 64px #22ff00;
    }
}
</style>
