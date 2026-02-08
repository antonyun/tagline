<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as accountsIndex } from '@/routes/accounts';
import { edit as accountsEdit } from '@/routes/accounts';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

import { Account } from '@/types/models/Account'
import { Media } from '@/types/models/Media'
import { Profile } from '@/types/models/Profile'

const props = defineProps<{account: Account;}>();

const profile = computed(() => props.account.profile);

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
        title: <string>(<unknown>props.account.id),
        href: accountsIndex().url,
    },
];

// Image handling
const thumbSrc = ref(
    props.account.thumbnail?.relative_thumbnail_path ??
    props.account.thumbnail?.relative_path ??
    ''
)

const hasImage = computed(() => thumbSrc.value !== '');

function onError(fallback: string) {
    thumbSrc.value =
        thumbSrc.value !== fallback ? fallback : '';
}

// Boolean flags → UI chips
const flags: { key: keyof Profile; label: string }[] = [
  { key: 'has_face_photo', label: 'Face Photo' },
  { key: 'has_body_photo', label: 'Body Photo' },
  { key: 'has_dick_photo', label: 'Dick Photo' },
  { key: 'has_anus_photo', label: 'Anal Photo' },
  { key: 'has_cum_photo', label: 'Cum Photo' },
  { key: 'has_had_sex', label: 'Sex Experienced' },
];

// Media category navigation
const mediaCategories = [
  { key: 'all', label: 'All Media', route: (id : number) => `/accounts/${id}/media` },
  { key: 'face', label: 'Face Photos', route: (id : number) => `/accounts/${id}/media?category_id=1` },
  { key: 'body', label: 'Body Photos', route: (id : number) => `/accounts/${id}/media?category_id=2` },
  { key: 'dick', label: 'Dick / NSFW', route: (id : number) => `/accounts/${id}/media?category_id=3` },
  { key: 'asshole', label: 'Ass / NSFW', route: (id : number) => `/accounts/${id}/media?category_id=4` },
  { key: 'cum', label: 'Cum / NSFW', route: (id : number) => `/accounts/${id}/media?category_id=5` },
  { key: 'other', label: 'Other', route: (id : number) => `/accounts/${id}/media?category_id=6` },
];

</script>

<template>
    <Head title="Account" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">

            <!-- Header -->
            <div class="flex place-content-between">
                <h1 class="text-2xl font-semibold">
                    Account #{{ props.account.id }}
                </h1>
                <Link
                    :href="accountsEdit(account.id)"
                    class="px-3 py-2 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border hover:bg-accent/10 transition"
                >Edit</Link>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Thumbnail Panel -->
                <div class="rounded-2xl overflow-hidden border border-sidebar-border/70 dark:border-sidebar-border bg-black/5">

                    <Link :href="`/accounts/${account.id}/avatar`">
                        <template v-if="hasImage">
                            <img
                                :src="thumbSrc"
                                :alt="props.account.thumbnail?.filename || 'Thumbnail'"
                                class="w-full h-[480px] object-cover"
                                @error="onError(props.account.thumbnail?.relative_path ?? '')"
                            />
                        </template>

                        <template v-else>
                            <div class="relative w-full h-[480px] flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                <PlaceholderPattern />
                            </div>
                        </template>
                    </Link>
                </div>

                <!-- Right Panel: Profile -->
                <div class="flex flex-col gap-4">

                    <!-- Basic Identity -->
                    <div>
                        <h2 class="text-xl font-semibold">
                            {{ profile?.name ?? 'Unnamed Profile' }}

                            <span v-if="profile?.name_latin" class="text-gray-500 ml-1">
                                ({{ profile.name_latin }})
                            </span>
                        </h2>
                    
                        <div class="text-gray-600 dark:text-gray-400 mt-1 space-x-1">
                            <span v-if="profile?.age">{{ profile.age }} yrs</span>
                            <span v-if="profile?.location"> • {{ profile.location }}</span>
                            <span v-if="profile?.ethnicity"> • {{ profile.ethnicity }}</span>
                        </div>
                    </div>

                    <!-- Attributes -->
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div v-if="profile?.height">Height: {{ profile.height }} cm</div>
                        <div v-if="profile?.weight">Weight: {{ profile.weight }} kg</div>
                        <div v-if="profile?.measurement_size">Size: {{ profile.measurement_size }}</div>
                        <div v-if="profile?.position">Position: {{ profile.position }}</div>
                        <div v-if="profile?.preferences">Preferences: {{ profile.preferences }}</div>
                    </div>

                    <!-- Additional Profile Info -->
                    <div class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">

                        <div v-if="profile?.legacy_id">
                            <strong>Legacy ID:</strong> {{ profile.legacy_id }}
                        </div>

                        <div v-if="profile?.year_of_birth">
                            <strong>Year of Birth:</strong> {{ profile.year_of_birth }}
                        </div>

                        <div v-if="profile?.date_of_birth">
                            <strong>Date of Birth:</strong> {{ profile.date_of_birth }}
                        </div>

                        <div v-if="profile?.phone_number">
                            <strong>Phone:</strong> {{ profile.phone_number }}
                        </div>

                        <div v-if="profile?.social_profiles" class="break-words">
                            <strong>Social Profiles:</strong> {{ profile.social_profiles }}
                        </div>

                        <div v-if="profile?.comment" class="whitespace-pre-line">
                            <strong>Comment:</strong>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">{{ profile.comment }}</p>
                        </div>

                        <div v-if="profile?.extra" class="mt-2">
                            <strong>Extra Data:</strong>
                            <pre class="overflow-auto max-h-32 bg-gray-100 dark:bg-gray-800 p-2 rounded text-xs">{{ JSON.stringify(profile.extra, null, 2) }}</pre>
                        </div>
                    </div>

                    <!-- Booleans as Chips -->
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div
                            v-for="flag in flags"
                            :key="flag.key"
                            >
                            <span v-if="profile && profile[flag.key as keyof Profile]"
                            class="px-2 py-1 rounded-lg text-xs bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200"
                            >
                            ✔ {{ flag.label }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Links -->
                    <div class="mt-4">
                        <h3 class="font-semibold mb-2">Media Categories</h3>

                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="cat in mediaCategories"
                                :key="cat.key"
                                :href="cat.route(props.account.id)"
                                class="px-3 py-2 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border hover:bg-accent/10 transition"
                            >
                                {{ cat.label }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>