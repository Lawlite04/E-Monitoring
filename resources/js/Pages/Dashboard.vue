<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps(['client', 'leader', 'project'])

function onRouting(url) {
    router.get(route(url))
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-base text-gray-800 leadi50-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <div class="bg-white pa-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-medium opacity-50">Leader</h2>
                                    <h1 class="text-5xl font-bold">{{ leader }}</h1>
                                </div>
                                <div><v-icon icon="mdi-account-tie" size="80" color="gray" class="opacity-20"></v-icon></div>
                            </div>
                            <div>
                                <v-btn @click.prevent="onRouting('leader.home')" variant="text" color="blue" class="w-full">
                                    View more
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <div class="bg-white pa-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-medium opacity-50">Client</h2>
                                    <h1 class="text-5xl font-bold">{{ client }}</h1>
                                </div>
                                <div><v-icon icon="mdi-account" size="80" color="gray" class="opacity-20"></v-icon></div>
                            </div>
                            <div>
                                <v-btn @click.prevent="onRouting('client.home')" variant="text" color="blue" class="w-full">
                                    View more
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <div class="bg-white pa-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-medium opacity-50">Project Monitoring</h2>
                                    <h1 class="text-5xl font-bold">{{ project }}</h1>
                                </div>
                                <div><v-icon icon="mdi-monitor-dashboard" size="80" color="gray" class="opacity-20"></v-icon></div>
                            </div>
                            <div>
                                <v-btn @click.prevent="onRouting('project.home')" variant="text" color="blue" class="w-full">
                                    View more
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
