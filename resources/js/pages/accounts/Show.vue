<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as accountsIndex } from '@/routes/accounts';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted, reactive, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

interface Media {
  id: number;
  filename: string;
  original_name: string | null;
  mime_type: string | null;
  size: number | null;
  md5: string | null;
  sha256: string | null;
  thumbnail: string | null;
  relative_path: string | null;
  relative_thumbnail_path: string | null;
}

interface Profile {
  id: number;
  legacy_id: number | null;

  name: string | null;
  name_latin: string | null;
  ethnicity: string | null;
  location: string | null;

  age: number | null;
  year_of_birth: number | null;
  date_of_birth: string | null;

  height: number | null;
  weight: number | null;
  measurement_size: number | null;

  position: string | null;
  preferences: string | null;

  has_face_photo: boolean;
  has_body_photo: boolean;
  has_dick_photo: boolean;
  has_anus_photo: boolean;
  has_cum_photo: boolean;
  has_had_sex: boolean;

  phone_number: string | null;
  social_profiles: string | null;

  comment: string | null;
  extra: Record<string, unknown> | null;
}

interface Account {
    id: number;
    thumbnail: Media | null;
    profile: Profile | null;
}

interface Props {
  account: Account;
}

const props = defineProps<Props>();

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

const thumbSrc = ref<string>(
    props.account.thumbnail?.relative_thumbnail_path ??
    props.account.thumbnail?.relative_path ??
    ''
)

const hasImage = computed(() => thumbSrc.value !== '');

function onError(fallback: string) {
    thumbSrc.value =
        thumbSrc.value !== fallback
            ? fallback
            : '';
}

/* const mediaSrcs = reactive<Record<number, string>>({});

props.accounts.data.forEach(account => {
    mediaSrcs[account.id] = account.thumbnail?.relative_thumbnail_path ?? account.thumbnail?.relative_path ?? '';
});

function onError(accountId: number, fallbackSrc: string) {
  if (mediaSrcs[accountId] !== fallbackSrc) {
    mediaSrcs[accountId] = fallbackSrc;
  } else {
    // both failed, clear to show placeholder
    mediaSrcs[accountId] = '';
  }
} */

</script>

<template>
    <Head title="Account" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1>Account #{{ props.account.id }}</h1>

            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden max-w-md">

                <!-- Thumbnail -->
                <div class="relative">
                    <template v-if="hasImage">
                        <img
                        :src="thumbSrc"
                        :alt="props.account.thumbnail?.filename || 'Thumbnail'"
                        class="w-full h-64 object-cover"
                        @error="onError(props.account.thumbnail?.relative_path ?? '')"
                        />
                    </template>

                    <template v-else>
                        <div class="w-full h-64 flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                        <PlaceholderPattern />
                        </div>
                    </template>
                </div>

                <!-- Profile -->
                <div class="p-3 space-y-2 text-sm">
                    <div class="font-semibold">
                    {{ account.profile?.name ?? 'No name' }}
                    <span v-if="account.profile?.name_latin" class="text-gray-500">
                        ({{ account.profile.name_latin }})
                    </span>
                    </div>

                    <div class="text-gray-600 dark:text-gray-400">
                    <span v-if="account.profile?.age">{{ account.profile.age }} yrs</span>
                    <span v-if="account.profile?.location"> • {{ account.profile.location }}</span>
                    <span v-if="account.profile?.ethnicity"> • {{ account.profile.ethnicity }}</span>
                    </div>

                    <!-- Physical metrics -->
                    <div class="flex flex-wrap gap-2 text-gray-700 dark:text-gray-300">
                    <span v-if="account.profile?.height">H: {{ account.profile.height }} cm</span>
                    <span v-if="account.profile?.weight">W: {{ account.profile.weight }} kg</span>
                    <span v-if="account.profile?.measurement_size">M: {{ account.profile.measurement_size }}</span>
                    </div>

                    <!-- Role / position -->
                    <div v-if="account.profile?.position">
                    Position: {{ account.profile.position }}
                    </div>

                    <div v-if="account.profile?.preferences">
                    Pref: {{ account.profile.preferences }}
                    </div>

                    <!-- Boolean flags -->
                    <div class="flex flex-wrap gap-1 mt-1">
                    <span
                        v-if="account.profile?.has_face_photo"
                        class="px-2 py-0.5 text-xs rounded bg-green-200 dark:bg-green-700"
                    >Face</span>

                    <span
                        v-if="account.profile?.has_body_photo"
                        class="px-2 py-0.5 text-xs rounded bg-green-200 dark:bg-green-700"
                    >Body</span>

                    <span
                        v-if="account.profile?.has_dick_photo"
                        class="px-2 py-0.5 text-xs rounded bg-green-200 dark:bg-green-700"
                    >Dick</span>

                    <span
                        v-if="account.profile?.has_anus_photo"
                        class="px-2 py-0.5 text-xs rounded bg-green-200 dark:bg-green-700"
                    >Anus</span>

                    <span
                        v-if="account.profile?.has_cum_photo"
                        class="px-2 py-0.5 text-xs rounded bg-green-200 dark:bg-green-700"
                    >Cum</span>

                    <span
                        v-if="account.profile?.has_had_sex"
                        class="px-2 py-0.5 text-xs rounded bg-blue-200 dark:bg-blue-700"
                    >Sex</span>
                    </div>

                    <!-- Contact -->
                    <div v-if="account.profile?.phone_number" class="text-gray-600 dark:text-gray-400">
                    📞 {{ account.profile.phone_number }}
                    </div>

                    <div v-if="account.profile?.social_profiles" class="text-gray-600 dark:text-gray-400">
                    🔗 {{ account.profile.social_profiles }}
                    </div>

                    <!-- Comment preview -->
                    <div v-if="account.profile?.comment" class="text-gray-500 line-clamp-2">
                    {{ account.profile.comment }}
                    </div>

                    <!-- Extra preview -->
                    <div v-if="account.profile?.extra" class="text-gray-500 line-clamp-2">
                    {{ account.profile.extra }}
                    </div>
                </div>


            </div>

            
        </div>
    </AppLayout>
</template>