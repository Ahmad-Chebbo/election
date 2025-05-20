<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    votes: Array<{
        id: number;
        team_id: number;
        candidate_id: number | null;
        ip_address: string;
        created_at: string;
    }>;
}>();

// Compute total votes
const totalVotes = computed(() => props.votes.length);

// Compute votes by team
const votesByTeam = computed(() => {
    const teamVotes = new Map();
    props.votes.forEach(vote => {
        const count = teamVotes.get(vote.team_id) || 0;
        teamVotes.set(vote.team_id, count + 1);
    });
    return teamVotes;
});

// Compute votes by candidate
const votesByCandidate = computed(() => {
    const candidateVotes = new Map();
    props.votes.forEach(vote => {
        if (vote.candidate_id) {
            const count = candidateVotes.get(vote.candidate_id) || 0;
            candidateVotes.set(vote.candidate_id, count + 1);
        }
    });
    return candidateVotes;
});

// Compute votes by hour
const votesByHour = computed(() => {
    const hourlyVotes = new Map();
    props.votes.forEach(vote => {
        const hour = new Date(vote.created_at).getHours();
        const count = hourlyVotes.get(hour) || 0;
        hourlyVotes.set(hour, count + 1);
    });
    return hourlyVotes;
});
</script>

<template>
    <Head title="Analytics">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-6 text-[#1b1b18]">
        <div class="mx-auto max-w-7xl">
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
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Voting Analytics</h1>
                <p class="text-gray-600">Total Votes: {{ totalVotes }}</p>
            </div>

            <!-- Analytics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Team Votes Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Votes by Team</h2>
                    <div class="space-y-4">
                        <div v-for="[teamId, count] in votesByTeam" :key="teamId" class="relative">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Team {{ teamId }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ count }} votes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div
                                    class="bg-blue-600 h-2.5 rounded-full"
                                    :style="{ width: `${(count / totalVotes) * 100}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Candidate Votes Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Votes by Candidate</h2>
                    <div class="space-y-4">
                        <div v-for="[candidateId, count] in votesByCandidate" :key="candidateId" class="relative">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Candidate {{ candidateId }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ count }} votes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div
                                    class="bg-green-600 h-2.5 rounded-full"
                                    :style="{ width: `${(count / totalVotes) * 100}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Votes by Hour</h2>
                    <div class="grid grid-cols-24 gap-2">
                        <div v-for="hour in 24" :key="hour" class="relative">
                            <div class="text-xs text-gray-500 text-center mb-1">{{ hour - 1 }}:00</div>
                            <div class="w-full bg-gray-200 rounded-full h-24">
                                <div
                                    class="bg-purple-600 rounded-full w-full"
                                    :style="{
                                        height: `${((votesByHour.get(hour - 1) || 0) / Math.max(...Array.from(votesByHour.values()))) * 100}%`
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
