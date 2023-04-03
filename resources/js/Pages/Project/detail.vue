<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Leader" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Detail</h2>
        </template>

        <div class="py-12">
            <v-row no-gutters class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <v-col cols="12" sm="7">
                    <v-sheet class="ma-2 pa-5 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-5">
                            <h1 class="text-2xl font-bold">{{ project.project_name }}</h1>
                            <p class="text-sm font-medium text-red-500">{{ formatDate(new Date(project.start_date)) }} - {{
                                formatDate(new Date(project.end_date)) }}</p>
                        </div>

                        <v-progress-linear class="rounded-[20px]" :model-value="progressBar" :color="(progressBar == 100) ? 'green' : 'blue'"
                            height="25">
                            <template v-slot:default="{ value }">
                                <strong>{{ Math.ceil(value) }}%</strong>
                            </template>
                        </v-progress-linear>

                        <v-list lines="one">
                            <v-list-subheader>Progress list</v-list-subheader>

                            <v-list-item v-for="(item, idx) in progress" :key="idx">
                                <v-checkbox-btn v-model="item.isFinished" :value="1" :false-value="0" :label="item.progress_name"></v-checkbox-btn>
                            </v-list-item>
                        </v-list>

                        <div class="flex justify-end">
                            <v-btn @click.prevent="save" prepend-icon="mdi-content-save" color="info">
                                Save changes
                            </v-btn>
                        </div>
                    </v-sheet>
                </v-col>
                <v-col cols="12" sm="5">
                    <v-row>
                        <v-col cols="12">
                            <v-sheet class="ma-2 pa-5 rounded-lg shadow">
                                <div class="w-32 h-32 rounded-full mx-auto border overflow-hidden">
                                    <img v-if="project.leader.leader_avatar" class="w-full h-full object-cover"
                                        :src="'/images/avatars/leaders/' + project.leader.leader_avatar"
                                        :alt="project.leader.leader_avatar">
                                    <div v-else class="flex justify-center items-center w-full h-full">
                                        <v-icon class="opacity-50" icon="mdi-camera" size="large"></v-icon>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h2 class="text-lg font-semibold">{{ project.leader.leader_name }}</h2>
                                    <p class="text-base font-semibold -mt-1.5">(Project Leader)</p>
                                    <h3 class="text-base font-normal">{{ project.leader.leader_email }}</h3>
                                    <h4 class="text-base font-normal">{{ project.leader.leader_telp }}</h4>
                                </div>
                            </v-sheet>
                        </v-col>
                        <v-col cols="12">
                            <v-sheet class="ma-2 pa-5 rounded-lg shadow">
                                <div class="w-32 h-32 rounded-full mx-auto border overflow-hidden">
                                    <img v-if="project.client.client_avatar" class="w-full h-full object-cover"
                                        :src="'/images/avatars/clients/' + project.client.client_avatar"
                                        :alt="project.client.client_avatar">
                                    <div v-else class="flex justify-center items-center w-full h-full">
                                        <v-icon class="opacity-50" icon="mdi-camera" size="x-large"></v-icon>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h2 class="text-lg font-semibold">{{ project.client.client_name }}</h2>
                                    <p class="text-base font-semibold -mt-1.5">(Client)</p>
                                    <h3 class="text-base font-normal">{{ project.client.client_email }}</h3>
                                    <h4 class="text-base font-normal">{{ project.client.client_telp }}</h4>
                                    <h4 class="text-base font-normal">{{ project.client.client_address }}</h4>
                                </div>
                            </v-sheet>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { format } from 'date-fns'

export default {
    props: ['project', 'errors'],

    data: () => ({
        dessert: [],
        progress: [],
    }),

    mounted() {
        this.initialize()
    },

    computed: {
        progressBar() {
            const filtered = this.progress.filter((item) => item.isFinished)

            const nilai = filtered.length
            const total = this.progress.length
            const result = (nilai / total) * 100
            return result
        }
    },

    methods: {
        initialize() {
            this.dessert = this.project
            this.progress = this.project.progress
        },

        formatDate(date) {
            return format(date, 'dd MMM yyyy')
        },

        save() {
            console.log(this.progress, this.project.id)
            this.$inertia.post(`/progress/${this.project.id}`, this.progress, {
                preserveScroll: true,
                onSuccess: () => alert('Updated progress successfully!')
            })
        }
    }
}
</script>
