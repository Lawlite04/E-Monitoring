<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Leader" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Monitoring</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="">
                        <v-data-table :headers="headers" :items="desserts"
                            :sort-by="[{ key: 'project_name', order: 'asc' }]" class="elevation-1">
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Project Monitoring Datas</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialog" max-width="800px">
                                        <template v-slot:activator="{ props }">
                                            <v-btn color="primary" dark class="mb-2" v-bind="props">
                                                New Item
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{ formTitle }}</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="editedItem.project_name"
                                                                label="Project Name"></v-text-field>

                                                            <InputError v-if="errors && errors.project_name" class="-mt-4"
                                                                :message="errors.project_name" />
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <v-autocomplete v-model="editedItem.leader_id" label="Leader"
                                                                :items="leaderDesserts" item-title="leader_name"
                                                                item-value="id"></v-autocomplete>

                                                            <InputError v-if="errors && errors.leader_id" class="-mt-4"
                                                                :message="errors.leader_id" />
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <v-autocomplete v-model="editedItem.client_id" label="Client"
                                                                :items="clientDesserts" item-title="client_name"
                                                                item-value="id"></v-autocomplete>

                                                            <InputError v-if="errors && errors.client_id" class="-mt-4"
                                                                :message="errors.client_id" />
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <label for="start_date" class="block text-sm opacity-70">Start
                                                                date</label>
                                                            <input v-model="editedItem.start_date" type="date"
                                                                id="start_date"
                                                                class="block w-full p-4 bg-gray-100 border-none shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />

                                                            <InputError v-if="errors && errors.start_date" class="mt-2"
                                                                :message="errors.start_date" />
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <label for="end_date" class="block text-sm opacity-70">End
                                                                date</label>
                                                            <input v-model="editedItem.end_date" type="date" id="end_date"
                                                                class="block w-full p-4 bg-gray-100 border-none shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />

                                                            <InputError v-if="errors && errors.end_date" class="mt-2"
                                                                :message="errors.end_date" />
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <label
                                                                class="block text-base uppercase font-medium">Progress</label>
                                                            <InputError v-if="errors && errors.progress"
                                                                :message="errors.progress" class="mb-1" />

                                                            <div class="mt-4">
                                                                <v-row v-for="(item, i) in editedItem.progress" :key="i"
                                                                    class="relative mb-2">
                                                                    <v-col cols="11">
                                                                        <v-text-field v-model="item.progress_name"
                                                                            label="Progress Name"
                                                                            placeholder="Ex: Designing process"></v-text-field>

                                                                        <InputError
                                                                            v-if="errors && errors[`progress.${i}.progress_name`]"
                                                                            class="-mt-4"
                                                                            :message="errors[`progress.${i}.progress_name`]" />
                                                                    </v-col>

                                                                    <v-col cols="1" class="relative">
                                                                        <div class="absolute top-4">
                                                                            <v-btn @click="deleteProgress(i)"
                                                                                icon="mdi-delete" variant="text"
                                                                                color="error"></v-btn>
                                                                        </div>
                                                                    </v-col>
                                                                    <v-divider class="border-opacity-100"></v-divider>
                                                                </v-row>
                                                            </div>

                                                            <v-btn @click="addProgress" prepend-icon="mdi-plus"
                                                                variant="text" color="success">
                                                                Add progress
                                                            </v-btn>

                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue-darken-1" variant="text" @click="close">
                                                    Cancel
                                                </v-btn>
                                                <v-btn color="blue-darken-1" variant="text" @click="save">
                                                    Save
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="dialogDelete" max-width="500px">
                                        <v-card>
                                            <v-card-title class="text-h5">Are you sure you want to delete this
                                                item?</v-card-title>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue-darken-1" variant="text"
                                                    @click="closeDelete">Cancel</v-btn>
                                                <v-btn color="blue-darken-1" variant="text"
                                                    @click="deleteItemConfirm">OK</v-btn>
                                                <v-spacer></v-spacer>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.leaders="{ item }">
                                <div class="flex gap-3 items-center py-2">
                                    <img v-if="item.raw.leader.leader_avatar" class="rounded-full w-14 h-14 object-cover"
                                        :src="'images/avatars/leaders/' + item.raw.leader.leader_avatar"
                                        :alt="item.raw.leader.leader_avatar">
                                    <div v-else
                                        class="flex justify-center items-center rounded-full border border-slate-900 w-14 h-14">
                                        <v-icon class="opacity-50" icon="mdi-camera" size="large"></v-icon>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-semibold">{{ item.raw.leader.leader_name }}
                                        </h2>
                                        <p class="text-sm font-normal opacity-60">{{
                                            item.raw.leader.leader_email }}</p>
                                    </div>
                                </div>
                            </template>
                            <template v-slot:item.start_date="{ item }">
                                {{ formatDate(new Date(item.raw.start_date)) }}
                            </template>
                            <template v-slot:item.end_date="{ item }">
                                {{ formatDate(new Date(item.raw.end_date)) }}
                            </template>
                            <template v-slot:item.progress_bar="{ item }">
                                <v-progress-linear :model-value="countProgressBar(item.raw.progress)" class="rounded-[20px]"
                                :color="(countProgressBar(item.raw.progress) == 100) ? 'green' : 'blue'" height="20">
                                    <template v-slot:default="{ value }">
                                        <strong>{{ Math.ceil(value) }}%</strong>
                                    </template>
                                </v-progress-linear>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-icon size="small" class="me-2" @click="showDetail(item.raw.id)">
                                    mdi-eye
                                </v-icon>
                                <v-icon size="small" class="me-2" @click="editItem(item.raw)">
                                    mdi-pencil
                                </v-icon>
                                <v-icon size="small" @click="deleteItem(item.raw)">
                                    mdi-delete
                                </v-icon>
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="initialize">
                                    Reset
                                </v-btn>
                            </template>
                        </v-data-table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { format } from 'date-fns';

export default {
    props: ['leaders', 'clients', 'projects', 'errors'],
    data: () => ({
        dialog: false,
        dialogDelete: false,
        skill: 50,
        rules: {
            required: value => !!value || 'Field is required',
        },
        headers: [
            { title: 'Project Name', key: 'project_name' },
            { title: 'Client', key: 'client.client_name' },
            { title: 'Project Leader', key: 'leaders', sortable: false },
            { title: 'Start Date', key: 'start_date' },
            { title: 'End Date', key: 'end_date' },
            { title: 'Progress', key: 'progress_bar' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        idItem: null,
        leaderDesserts: [],
        clientDesserts: [],
        editedItem: {
            leader_id: '',
            client_id: '',
            project_name: '',
            start_date: '',
            end_date: '',
            progress: []
        },
        defaultItem: {
            leader_id: '',
            client_id: '',
            project_name: '',
            start_date: '',
            end_date: '',
            progress: []
        },
        defaultProgress: {
            id: null,
            progress_name: '',
            isFinished: false
        }
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    created() {
        this.initialize()
    },

    methods: {
        initialize() {
            this.leaderDesserts = this.leaders
            this.clientDesserts = this.clients
            this.desserts = this.projects
        },

        showDetail(id) {
            this.$inertia.get(route('project.detail', id))
        },

        countProgressBar(items) {
            let filtered = items.filter((item) => item.isFinished)
            const countProgressFinish = filtered.length
            const totalAllProgress = items.length
            let result = (countProgressFinish / totalAllProgress) * 100
            return result
        },

        formatDate(date) {
            return format(date, 'dd MMM yyyy')
        },

        addProgress() {
            this.editedItem.progress.push({
                id: null,
                progress_name: '',
                isFinished: false
            })

        },

        deleteProgress(i) {
            this.editedItem.progress.splice(i, 1)
        },

        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            // this.editedItem = Object.assign({}, item)
            this.idItem = item.id
            this.editedItem.project_name = item.project_name
            this.editedItem.leader_id = item.leader_id
            this.editedItem.client_id = item.client_id
            this.editedItem.start_date = item.start_date
            this.editedItem.end_date = item.end_date
            this.editedItem.progress = item.progress
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            // this.editedItem = Object.assign({}, item)
            this.idItem = item.id
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            this.$inertia.delete(route('project.destroy', this.idItem), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeDelete()
                    this.initialize()
                    alert('Deleted successfully!')
                }
            })
            // this.desserts.splice(this.editedIndex, 1)
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            if (this.editedIndex > -1) {
                // update
                this.$inertia.post(route('project.update', this.idItem), this.editedItem, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.close();
                        this.initialize();
                        alert('Updated successfully!')
                    }
                })
            } else {
                // tambah

                this.$inertia.post(route('project.store'), this.editedItem, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.close();
                        this.initialize();
                        alert('Created successfully!')
                    }
                })
            }
        },
    },
}
</script>
