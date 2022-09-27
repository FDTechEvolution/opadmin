<template>
    <b-card class="mb-2">
        <b-card-text>
            <b-row>
                <b-col lg="8">
                    <h6 class="text-secondary">{{ title }}</h6>
                    <h3>{{ data }}</h3>
                </b-col>
                <b-col lg="4" class="text-center">
                    <b-avatar :variant="icon_color" rounded><i :class="icon"></i></b-avatar>
                </b-col>
                <b-col v-if="percent !== ''" lg="12">
                    <p class="mt-2 mb-0 text-sm" style="font-weight: 100;">
                        <span :class="p_type === 'pos' ? 'text-success' : 'text-danger'">{{ percent }}%</span> Since last month
                    </p>
                </b-col>
            </b-row>
        </b-card-text>
    </b-card>
</template>


<script>
export default {
    props: ['title', 'data', 'icon', 'icon_color', 'percent', 'p_type']
}
</script>