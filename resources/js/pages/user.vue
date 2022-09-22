<template>
    <div>
        <b-row>
            <b-col lg="6"></b-col>
            <b-col lg="6">
                <b-form-group
                    label="Per page"
                    label-for="per-page-select"
                    label-cols-sm="6"
                    label-cols-md="4"
                    label-cols-lg="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-2"
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
            <b-col lg="6" class="my-1">
                <b-form-group
                    label="Filter"
                    label-for="filter-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
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

            <b-col sm="5" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="users_length"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>

        <!-- Main table element -->
        <b-table 
            :items="users"
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

            <template #cell(status)="row">
                <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
            </template>

            <template #cell(employee)="row">
                <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
            </template>

            <template #cell(freelance)="row">
                <i :class="row.value === 'Y' ? 'mdi mdi-check text-danger' : 'mdi mdi-close text-secondary'"></i>
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" class="mr-1" @click="confirmStatus(row.item.name, row.item.id)">
                    เปิด/ปิด การใช้งาน
                </b-button>
                <b-button size="sm" >
                    แก้ไข
                </b-button>
            </template>

        </b-table>

        <b-row class="mt-2">
            <b-col lg="6"></b-col>
            <b-col lg="6">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="users_length"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>

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
                { key: 'org', label: 'Org', sortable: true, class: 'text-center' },
                { key: 'role', label: 'Role', sortable: true, class: 'text-center' },
                { key: 'created', label: 'Created', sortable: true, class: 'text-center' },
                { key: 'status', label: 'Status', class: 'text-center' },
                { key: 'employee', label: 'Employee', class: 'text-center' },
                { key: 'freelance', label: 'Freelance', class: 'text-center' },
                { key: 'actions', label: 'Actions', class: 'text-center' }
            ],
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 20, 30, 50, { value: 100, text: "Show a lot" }],
            filter: null,
            filterOn: [],
            sortBy: '',
            sortDesc: false,
            sortDirection: 'asc',
            editModal: {
                id: 'info-modal',
                org_id: '',
                name: '',
                mobileno: '',
                code: '',
            },
        }
    },
    created() {
        this.$store.dispatch('user/getUsers')
    },
    computed: {
        ...mapGetters('user', [
            'users',
            'users_length'
        ])
    },
    methods: {
        onFiltered(filteredItems) {
            // this.users_length = filteredItems.length
            this.$store.commit('user/SET_USER_LENGTH', {'length': filteredItems.length})
            this.currentPage = 1
        },
        confirmStatus(name, id) {
            this.status = ''
            this.$bvModal.msgBoxConfirm(`ยืนยันการแก้ไขสถานะของ user:${name.username} | ${name.fullname}`, {
                title: 'Please Confirm',
                size: 'md',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
            })
            .then(value => {
                if(value) this.$store.dispatch('user/updateStatus', {user_id: id})
            })
            .catch(err => {
                // An error occurred
            })
        },
    }
}
</script>