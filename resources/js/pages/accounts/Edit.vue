<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as accountsIndex } from '@/routes/accounts';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

import { defaultFields } from '@/config/accountFields'

const props = defineProps<{
    account: {
        id: number
        profile: Record<string, any> | null
    }
}>();

const account = props.account
const profile = account.profile ?? {}

// build initial form state automatically
const initialData = Object.fromEntries(
  Object.entries(defaultFields).map(([key, type]) => [
    key,
    type === 'checkbox' ? false : null,
  ])
)

const form = useForm<Record<string, any>>({
  ...initialData,

  ...profile,

  // ensure JSON fields are strings for inputs
  social_profiles: profile.social_profiles
    ? JSON.stringify(profile.social_profiles, null, 2)
    : null,

  // ensure JSON fields are strings for inputs
  extra: profile.extra
    ? JSON.stringify(profile.extra, null, 2)
    : null,
})

function tryParseJson(value: unknown) {
  if (typeof value !== 'string') return value
  try {
    return JSON.parse(value)
  } catch {
    return value
  }
}

const submit = () => {
    /* if (form.social_profiles) {
        form.social_profiles = tryParseJson(form.social_profiles)
    }

    if (form.extra) {
        form.extra = tryParseJson(form.extra)
    } */
    
    form.patch(`/accounts/${account.id}`), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    }
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Accounts',
        href: accountsIndex().url,
    },
    {
        title: 'Edit',
        href: accountsIndex().url,
    },
];

</script>

<template>
    <Head title="Edit Account" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">

            <!-- Header -->
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Edit Account
            </h1>

            <!-- Form Container -->
            <div
                class="max-w-4xl w-full mx-auto bg-white dark:bg-gray-900
                        border border-sidebar-border/70 dark:border-sidebar-border
                        rounded-2xl p-6 shadow-sm">

                <form @submit.prevent="submit" class="space-y-6">

                    <div v-for="(type, field) in defaultFields" :key="field">

                        <!-- Text / Number / Date inputs -->
                        <div v-if="type !== 'checkbox'" class="flex flex-col md:flex-row md:items-center">
                            <label
                                :for="field"
                                class="md:w-1/4 mb-2 md:mb-0 text-gray-600 dark:text-gray-300 font-medium"
                            >{{ field }}</label>
                            <input
                                :id="field"
                                :type="type === 'date' ? 'date' : type"
                                v-model="form[field]"
                                class="md:w-3/4 p-3 rounded-xl border
                                        border-sidebar-border/70 dark:border-sidebar-border
                                        bg-gray-50 dark:bg-gray-800 text-gray-900
                                        dark:text-gray-100 focus:outline-none focus:ring-2
                                        focus:ring-accent dark:focus:ring-accent transition"
                            />
                        </div>

                        <!-- Checkbox inputs -->
                        <div v-else class="flex items-center space-x-3">
                            <input
                                :id="field"
                                type="checkbox"
                                v-model="form[field]"
                                class="w-5 h-5 rounded-lg border border-sidebar-border/70
                                        dark:border-sidebar-border text-accent bg-gray-100
                                        dark:bg-gray-700 focus:ring-accent focus:ring-2"
                            />
                            <label
                                :for="field"
                                class="text-gray-900 dark:text-gray-100 font-medium"
                            >{{ field }}</label>
                        </div>

                         <!-- Validation error -->
                        <p v-if="form.errors[field]" class="text-sm text-red-600 mt-1">
                            {{ form.errors[field] }}
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl bg-purple-600
                                    hover:bg-purple-500 text-white font-semibold
                                    shadow-md transition"
                        >Submit</button>
                    </div>

                    <!-- Debug JSON -->
                    <pre class="text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 p-3 rounded-lg max-h-48 overflow-auto">
                        {{ JSON.stringify(form.data(), null, 2) }}
                    </pre>

                </form>
              
            </div>
        </div>
    </AppLayout>
</template>