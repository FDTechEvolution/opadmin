<template>
    <div class="row">
        <div class="col-12">
            <router-link to="/app/org" class="btn btn-secondary">
                <i class="ti-home"></i><span> Org </span>
            </router-link>
        </div>
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-3">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h4>{{ org_view.name }}</h4>
                            <p class="mb-0"><strong>Code:</strong> {{ org_view.code }}</p>
                            <p class="mb-0"><strong>Mobile:</strong> {{ org_view.mobileno }}</p>
                            <p class="mb-0"><strong>status:</strong> 
                                <b-badge :variant="org_view.status === 'Y' ? 'success' : 'danger'">
                                    {{ org_view.status === 'Y' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </b-badge>
                            </p>
                            <p class="mb-0"><strong>Created:</strong> {{ org_view.created }}</p>
                        </div>
                    </div>

                    <dashboard-card
                        title="Employee"
                        :data="org_view.user_count"
                        icon="ti-user"
                        icon_color="info"
                        percent=""
                        p_type=""
                    ></dashboard-card>

                    <dashboard-card
                        title="Order / Month"
                        :data="order_month.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')"
                        icon="ti-star"
                        icon_color="success"
                        percent=""
                        p_type=""
                    ></dashboard-card>

                    <dashboard-card
                        title="Order / Year"
                        :data="order_year.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')"
                        icon="ti-star"
                        icon_color="warning"
                        percent=""
                        p_type=""
                    ></dashboard-card>
                </div>

                <div class="col-9" v-if="org_loading">
                    <div class="text-center">
                        <b-spinner label="Loading..."></b-spinner>
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="col-9" v-else>
                    <div class="card">
                        <div class="card-body">
                            <b-row>
                                <b-col lg="8"></b-col>
                                <b-col lg="4">
                                    <b-form-group
                                        label="Per page"
                                        label-for="per-page-select"
                                        label-cols-sm="6"
                                        label-cols-md="4"
                                        label-cols-lg="3"
                                        label-align-sm="right"
                                        label-size="sm"
                                        class="mb-2 float-right per-page-group"
                                    >
                                        <b-form-select
                                            id="per-page-select"
                                            v-model="perPage"
                                            :options="pageOptions"
                                            size="sm"
                                        ></b-form-select>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <b-row class="mb-3">
                                <b-col lg="8" class="my-1">
                                    <b-form-group
                                        label="Filter"
                                        label-for="filter-input"
                                        label-cols-sm="3"
                                        label-align-sm="right"
                                        label-size="sm"
                                        class="mb-0 float-left"
                                    >
                                        <b-input-group size="sm">
                                            <b-form-input
                                                id="filter-input"
                                                v-model="filter"
                                                type="search"
                                                placeholder="Type to Search"
                                            ></b-form-input>

                                            <b-input-group-append>
                                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                </b-col>

                                <b-col sm="5" md="4" class="my-1">
                                    <b-pagination
                                        v-model="currentPage"
                                        :total-rows="org_user_length"
                                        :per-page="perPage"
                                        align="fill"
                                        size="sm"
                                        class="my-0"
                                    ></b-pagination>
                                </b-col>
                            </b-row>

                            <!-- Main table element -->
                            <b-table 
                                :items="org_user"
                                :fields="fields"
                                :current-page="currentPage"
                                :per-page="perPage"
                                :filter="filter"
                                :filter-included-fields="filterOn"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                :sort-direction="sortDirection"
                                stacked="md"
                                show-empty
                                small
                                @filtered="onFiltered"
                                >
                                <template #cell(name)="row">
                                    <strong>{{ row.value.username }}</strong>
                                    <p>{{ row.value.fullname }} <small>({{ row.value.nickname }})</small></p>
                                </template>

                                <template #cell(online)="row">
                                    <i class="ion-record" :class="row.value ? 'text-success' : 'text-danger'"></i>
                                </template>

                                <template #cell(status)="row">
                                    <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
                                </template>

                                <template #cell(employee)="row">
                                    <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
                                </template>

                                <template #cell(freelance)="row">
                                    <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
                                </template>

                            </b-table>

                            <b-row class="mt-2">
                                <b-col lg="8"></b-col>
                                <b-col lg="4">
                                    <b-pagination
                                        v-model="currentPage"
                                        :total-rows="org_user_length"
                                        :per-page="perPage"
                                        align="fill"
                                        size="sm"
                                        class="my-0"
                                    ></b-pagination>
                                </b-col>
                            </b-row>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            fields: [
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                { key: 'mobileno', label: 'Mobile No.', sortable: true, class: 'text-center' },
                { key: 'type', label: 'Type', sortable: true, class: 'text-center' },
                { key: 'role', label: 'Role', sortable: true, class: 'text-center' },
                { key: 'created', label: 'Created', sortable: true, class: 'text-center' },
                { key: 'status', label: 'Status', class: 'text-center' },
                { key: 'employee', label: 'Employee', class: 'text-center' },
                { key: 'freelance', label: 'Freelance', class: 'text-center' },
                { key: 'online', label: 'UserOnline', class: 'text-center' },
            ],
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 20, 30, 50, { value: 100, text: "Show a lot" }],
            filter: null,
            filterOn: [],
            sortBy: '',
            sortDesc: false,
            sortDirection: 'asc',
        }
    },
    created() {
        this.$store.dispatch('org/orgView', {id: this.$route.params.id})
    },
    mounted() {
        this.userOnline()
        this.orderUpdate()
    },
    computed: {
        ...mapGetters('org', [
            'org_view',
            'org_user',
            'org_user_length',
            'order_month',
            'order_year',
            'org_loading'
        ])
    },
    methods: {
        onFiltered(filteredItems) {
            this.$store.commit('org/SET_USER_LENGTH', {'length': filteredItems.length})
            this.currentPage = 1
        },
        userOnline() {
            setInterval(() => {
                this.$store.dispatch('org/userOnline', {'id': this.$route.params.id})
            }, 10000)
        },
        orderUpdate() {
            setInterval(() => {
                this.$store.dispatch('org/orderUpdate', {'id': this.$route.params.id})
            }, 60000)
        }
    }
}
</script>