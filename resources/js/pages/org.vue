<template>
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
                        :total-rows="orgs_length"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                    ></b-pagination>
                </b-col>
            </b-row>

            <!-- Main table element -->
            <b-table 
                :items="orgs"
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
                <template #cell(status)="row">
                    <p :class="row.value === 'Y' ? 'text-success' : 'text-danger'">
                        {{ row.value === 'Y' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                    </p>
                </template>

                <template #cell(actions)="row">
                    <b-button size="sm" class="mr-1" @click="confirmStatus(row.item.name, row.item.id)">
                        เปิด/ปิด การใช้งาน
                    </b-button>
                    <router-link :to="`/app/org/view/${row.item.id}`" class="mr-1 btn btn-sm btn-secondary" @click="view(row.item, row.index, $event.target)">
                        รายละเอียด
                    </router-link>
                    <b-button size="sm" @click="edit(row.item, $event.target)">
                        แก้ไข
                    </b-button>
                </template>

            </b-table>

            <b-row>
                <b-col lg="8"></b-col>
                <b-col lg="4">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="orgs_length"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                    ></b-pagination>
                </b-col>
            </b-row>

            <b-modal :id="editModal.id" :title="editModal.title" @hide="resetEditModal" hide-footer>
                <b-form-group
                    label="Name"
                    label-for="name-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-2"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="name-input"
                            v-model="editModal.name"
                            type="text"
                            placeholder="name"
                            required
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>

                <b-form-group
                    label="Mobile No."
                    label-for="mobile-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-2"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="mobile-input"
                            v-model="editModal.mobileno"
                            type="text"
                            placeholder="mobile"
                            required
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>

                <b-form-group
                    label="Code"
                    label-for="code-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-2"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="code-input"
                            v-model="editModal.code"
                            type="text"
                            placeholder="code"
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>

                <div class="text-right">
                    <b-button size="sm" variant="success" @click="saveOrg()">
                        แก้ไข
                    </b-button>
                </div>
                
            </b-modal>

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
                { key: 'code', label: 'Code', sortable: true, class: 'text-center' },
                { key: 'created', label: 'Created', sortable: true, class: 'text-center' },
                { key: 'user_count', label: 'Total Users', sortable: true, class: 'text-center' },
                { key: 'status', label: 'Status', class: 'text-center' },
                { key: 'actions', label: 'Actions', class: 'text-center' }
            ],
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 20, 30, { value: 100, text: "Show a lot" }],
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
        this.$store.dispatch('org/getOrgs')
    },
    computed: {
        ...mapGetters('org', [
            'orgs',
            'orgs_length'
        ])
    },
    methods: {
        onFiltered(filteredItems) {
            this.$store.commit('SET_ORG_LENGTH', {'length': filteredItems.length})
            this.currentPage = 1
        },
        edit(item, button) {
            this.editModal.org_id = item.id
            this.editModal.name = item.name
            this.editModal.mobileno = item.mobileno
            this.editModal.code = item.code
            this.$root.$emit('bv::show::modal', this.editModal.id, button)
        },
        resetEditModal() {
            this.editModal.org_id = ''
            this.editModal.name = ''
            this.editModal.mobileno = ''
            this.editModal.code = ''
        },
        confirmStatus(name, id) {
            this.status = ''
            this.$bvModal.msgBoxConfirm(`ยืนยันการแก้ไขสถานะของ ${name}`, {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
            })
            .then(value => {
                this.$store.dispatch('org/updateStatus', {org_id: id})
            })
            .catch(err => {
                // An error occurred
            })
        },
        saveOrg() {
            console.log('saved...')
            // this.$store.dispatch('org/updateOrg', this.editModal)
        },
    }
}
</script>