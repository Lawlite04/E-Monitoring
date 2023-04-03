<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Leader" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Leader</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="">
                        <v-data-table :headers="headers" :items="desserts" :search="search" :sort-by="[{ key: 'leader_name', order: 'asc' }]"
                            class="elevation-1">
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Leader Datas</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                                        hide-details></v-text-field>
                                    <v-dialog v-model="dialog" max-width="500px">
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
                                                            <v-checkbox v-model="editedItem.is_delete_avatar"
                                                                label="Delete avatar."></v-checkbox>
                                                        </v-col>
                                                        <v-col v-if="!editedItem.is_delete_avatar" cols="12">
                                                            <v-file-input @change="onFileChange" accept="image/*"
                                                                label="Isi untuk mengubah avatar"
                                                                prepend-icon="mdi-camera"></v-file-input>
                                                            <InputError v-if="errors && errors.leader_avatar" class="-mt-4"
                                                                :message="errors.leader_avatar" />
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="editedItem.leader_name"
                                                                label="Name"></v-text-field>

                                                            <InputError v-if="errors && errors.leader_name" class="-mt-4"
                                                                :message="errors.leader_name" />
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="editedItem.leader_email"
                                                                label="Email"></v-text-field>

                                                            <InputError v-if="errors && errors.leader_email" class="-mt-4"
                                                                :message="errors.leader_email" />
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="editedItem.leader_telp"
                                                                label="Telphone"></v-text-field>

                                                            <InputError v-if="errors && errors.leader_telp" class="-mt-4"
                                                                :message="errors.leader_telp" />
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
                            <template v-slot:item.leader_avatar="{ item }">
                                <div class="w-20 h-20 m-2 mx-auto border flex justify-center items-center">
                                    <img v-if="item.raw.leader_avatar" class="w-full h-full object-cover"
                                        :src="'/images/avatars/leaders/' + item.raw.leader_avatar"
                                        :alt="item.raw.leader_avatar">
                                    <p v-else class="italic opacity-50">
                                        <v-icon icon="mdi-camera" size="large"></v-icon>
                                    </p>
                                </div>
                            </template>
                            <template v-slot:item.actions="{ item }">
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
export default {
    props: ['leaders', 'errors'],
    data: () => ({
        dialog: false,
        dialogDelete: false,
        search: '',
        headers: [
            {
                title: 'Avatar',
                align: 'center',
                sortable: false,
                key: 'leader_avatar',
            },
            { title: 'Name', key: 'leader_name' },
            { title: 'Email', key: 'leader_email' },
            { title: 'Telphone', key: 'leader_telp' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        idItem: null,
        editedItem: {
            leader_avatar: null,
            leader_name: '',
            leader_email: '',
            leader_telp: '',
            is_delete_avatar: false
        },
        defaultItem: {
            leader_avatar: null,
            leader_name: '',
            leader_email: '',
            leader_telp: '',
            is_delete_avatar: false
        },
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
            this.desserts = this.leaders
        },

        onFileChange(e) {
            if (e.target.files[0]) {
                this.editedItem.leader_avatar = e.target.files[0];
            } else {
                this.editedItem.leader_avatar = null;
            }
        },

        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            // this.editedItem = Object.assign({}, item)
            this.idItem = item.id
            this.editedItem.leader_avatar = null
            this.editedItem.leader_name = item.leader_name
            this.editedItem.leader_email = item.leader_email
            this.editedItem.leader_telp = item.leader_telp
            this.editedItem.is_delete_avatar = item.leader_avatar == null ? true : false
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.idItem = item.id
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            this.$inertia.delete(route('leader.destroy', this.idItem), {
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
                // Object.assign(this.desserts[this.editedIndex], this.editedItem)
                this.$inertia.post(route('leader.update', this.idItem), this.editedItem, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.close();
                        this.initialize();
                        alert('Updated successfully!')
                    }
                })
            } else {
                // tambah
                // this.desserts.push(this.editedItem)
                this.$inertia.post(route('leader.store'), this.editedItem, {
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
