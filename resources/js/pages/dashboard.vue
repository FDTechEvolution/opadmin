<template>
    <div>
        <b-row>
            <b-col lg="3">
                <dashboard-card
                    title="Orgs"
                    :data="org_count"
                    icon="ti-star"
                    icon_color="info"
                    percent="50"
                    p_type="pos"
                ></dashboard-card>
            </b-col>

            <b-col lg="3">
                <dashboard-card
                    title="Users"
                    :data="user_count"
                    icon="ti-user"
                    icon_color="warning"
                    percent="50"
                    p_type="nev"
                ></dashboard-card>
            </b-col>
            
            <b-col lg="3">
                <dashboard-card
                    title="User Online"
                    :data="user_online"
                    icon="ti-light-bulb"
                    icon_color="success"
                    percent="50"
                    p_type="pos"
                ></dashboard-card>
            </b-col>

            <b-col lg="3">
                <dashboard-card
                    title="XXX"
                    data="200"
                    icon="ti-check"
                    icon_color="secondary"
                    percent="50"
                    p_type="nev"
                ></dashboard-card>
            </b-col>
        </b-row>

        <b-row class="mt-3">
            <b-col lg="4">
                <b-card class="mb-2 p-4">
                    <b-card-text>
                        <doughnut-chart></doughnut-chart>
                    </b-card-text>
                </b-card>
            </b-col>

            <b-col lg="8">
                <b-card class="mb-2 p-4">
                    <b-card-text>
                        <line-chart></line-chart>
                    </b-card-text>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>


<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {

        }
    },
    created() {
        this.$store.dispatch('user/userCount')
        this.$store.dispatch('org/orgCount')
        this.$store.dispatch('user/userOnline')
    },
    mounted() {
        this.userOnline()
    },
    computed: {
        ...mapGetters('user', [
            'user_count',
            'user_online'
        ]),

        ...mapGetters('org', [
            'org_count'
        ])
    },
    methods: {
        userOnline() {
            setInterval(() => {
                this.$store.dispatch('user/userOnline')
            }, 10000)
        }
    }
}
</script>