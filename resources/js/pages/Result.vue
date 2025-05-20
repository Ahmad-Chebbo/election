<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    teamsVotes: Array<{
        team_id: number;
        team_name: string;
        vote_count: number;
    }>;
    totalVotes: number;
}>();

const password = ref('');
const isAuthenticated = ref(false);
const correctPassword = 'CLACBarja25';

const authenticate = () => {
    if (password.value === correctPassword) {
        isAuthenticated.value = true;
    } else {
        alert('Incorrect password');
    }
};
</script>

<template>
    <Head title="Voting Results">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-6 text-[#1b1b18]">
        <div class="mx-auto max-w-4xl">
            <!-- Password Protection -->
            <div v-if="!isAuthenticated" class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto mt-20 text-center">
                <Link
                        :href="route('home')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors mb-4"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Home
                    </Link>
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Protected Content</h2>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            type="password"
                            id="password"
                            v-model="password"
                            @keyup.enter="authenticate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter password"
                        />
                    </div>
                    <button
                        @click="authenticate"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Unlock Results
                    </button>
                </div>
            </div>

            <!-- Content (only shown when authenticated) -->
            <div v-else>
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors mb-4"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Home
                    </Link>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Voting Results</h1>
                    <p class="text-gray-600">Total Votes: {{ totalVotes }}</p>
                </div>

                <!-- Team Rankings Section -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Team Rankings</h2>
                    <div class="space-y-4">
                        <template v-if="teamsVotes.length > 0">
                            <div
                                v-for="(team, index) in teamsVotes"
                                :key="team.team_name"
                                class="group relative bg-white rounded-lg border border-gray-200 p-6 hover:border-blue-500 transition-all duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold">
                                            #{{ index + 1 }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ team.team_name }}</h3>
                                            <p class="text-sm text-gray-500">Votes: {{ team.vote_count }}</p>
                                        </div>
                                    </div>
                                    <Link
                                        :href="route('candidateResult', team.team_id)"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                    >
                                        View Details
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Votes Yet!</h3>
                                <p class="text-gray-600">Looks like everyone is still thinking... 🤔</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
