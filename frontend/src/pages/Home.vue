<template>
    <p class="text-3xl font-bold underline">NoEdgeCash Balance: {{ globalPoolData.NoEdgeCash.value }}</p>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Home',
    data() {
        return {
            globalPoolData: null,
        };
    },
    async mounted() {
        await this.loadGlobalPoolData();
    },
    methods: {
        async loadGlobalPoolData() {
            let response = await axios.get('http://localhost:8000/api/globalPool/balance');

            const mapped = {};
            response.data.forEach(item => {
                mapped[item.currency_name] = {
                    value: Number(item.currency_value)
                };
            });

            this.globalPoolData = mapped;
        },
    }
}
</script>