import { defineStore } from "pinia";
import api from "@/scripts/axios";

export const useGlobalBalanceStore = defineStore('globalBalance', {
    state: () => ({
        balances: null,
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
    }
});