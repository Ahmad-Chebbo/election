<script setup lang="ts">
import { Candidate, Team } from '../types/Team';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';


const props = defineProps<{
    teams: Team[];
    candidates: Candidate[];
}>();

const form = ref({
    team_id: '',
    candidate_id: '',
});

const isSubmitting = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
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

const filteredCandidates = computed(() => {
    if (!form.value.team_id) return [];
    return props.candidates.filter(candidate => candidate.team_id === Number(form.value.team_id));
});
const submitForm = async () => {
    isSubmitting.value = true;
    showSuccess.value = false;
    showError.value = false;

    // Validate form
    if (!form.value.team_id) {
        alert('Please select a team');
        isSubmitting.value = false;
        return;
    }

    try {
        await router.post(route('home'), form.value);
        showSuccess.value = true;
        form.value = {
            team_id: '',
            candidate_id: '',
        };
    } catch (error: any) {
        if (error.response?.data?.errors) {
            // Handle validation errors from the server
            const errors = error.response.data.errors;
            Object.keys(errors).forEach(key => {
                alert(errors[key][0]);
            });
        } else {
            console.error('Error submitting vote:', error);
            alert('An error occurred while submitting your vote. Please try again.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <Head title="Election Voting">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-6 text-[#1b1b18]">

        <!-- Password Protection -->
        <div v-if="!isAuthenticated" class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto mt-20">
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
                        Unlock Form
                    </button>
                </div>
            </div>


        <div v-else class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Election Voting System</h1>
                <p class="text-gray-600">Cast your vote for your preferred candidate</p>
                <Link
                    :href="route('result')"
                    class="inline-flex items-center px-6 py-3 text-lg font-semibold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                >
                    View Voting Results
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <!-- Main Content -->
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <!-- Success Message -->
                    <div v-if="showSuccess" class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">The vote has been successfully recorded!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div v-if="showError" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 3v1m0 16H9m3 0h3m-3 0l-4-4m4 4l4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">An error occurred while submitting your vote. Please try again.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Voting Form -->
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Team Selection -->
                        <div class="space-y-2">
                            <label for="team" class="block text-lg font-medium text-gray-900">Select Team</label>
                            <select
                                id="team"
                                v-model="form.team_id"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg py-3 text-gray-900"
                                required
                                :disabled="isSubmitting"
                            >
                                <option value="" class="text-gray-900">Choose a team...</option>
                                <option v-for="team in teams" :key="team.id" :value="team.id" class="text-gray-900">
                                    {{ team.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Candidate Selection -->
                        <div class="space-y-2">
                            <label for="candidate" class="block text-lg font-medium text-gray-900">Select Candidate</label>
                            <select
                                id="candidate"
                                v-model="form.candidate_id"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg py-3 text-gray-900"
                                :disabled="isSubmitting || !form.team_id"
                            >
                                <option value="" class="text-gray-900">Choose a candidate...</option>
                                <option v-for="candidate in filteredCandidates" :key="candidate.id" :value="candidate.id">
                                    {{ candidate.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-blue-600 text-white rounded-lg py-3 px-6 text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isSubmitting || !form.team_id"
                        >
                            <span v-if="isSubmitting" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else>Cast Your Vote</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


        <!-- <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header> -->