import { defineStore } from "pinia";
import api from "@/scripts/axios";

export const useGlobalBalanceStore = defineStore('globalBalance', {
    state: () => ({
        balances: null,
        refreshIntervalId: null as null | ReturnType<typeof setInterval>,
    }),
    actions: {
        async loadGlobalPoolData() {
            let response = await api.get('/api/globalPool/balance');

            const mapped = {};
            response.data.forEach(item => {
                mapped[item.currency_name] = {
                    value: Number(item.currency_value)
                };
            });

            this.balances = mapped;
        },
        startAutoRefresh(intervalMs = 10000) {
            if (this.refreshIntervalId) return;

            this.loadGlobalPoolData();

            this.refreshIntervalId = setInterval(() => {
                this.loadGlobalPoolData();
            }, intervalMs);
        },
        stopAutoRefresh() {
            if (this.refreshIntervalId) {
                clearInterval(this.refreshIntervalId);
                this.refreshIntervalId = null;
            }
        },
    }
});