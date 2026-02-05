<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as accountsIndex } from '@/routes/accounts';
import { show as accountsShow } from '@/routes/accounts';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted, reactive, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Accounts',
        href: accountsIndex().url,
    },
];

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

interface Account {
    id: number;
    thumbnail: Media | null;
}

interface Link {
  url: string | null;
  label: string;
  active: boolean;
}

interface AccountsPagination {
  current_page: number;
  data: Account[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: Link[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

interface Props {
  accounts: AccountsPagination;
}

const props = defineProps<Props>();

const mediaSrcs = reactive<Record<number, string>>({});

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
}

</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between">
                <h1>Accounts</h1>
                <Link
                    href="/accounts/create"
                    class="px-3 py-1 rounded bg-indigo-600 text-white"
                >Create</Link>
            </div>

        <div class="flex flex-wrap justify-start gap-3">
            <Link
                v-for="account in props.accounts.data"
                :key="account.id"
                :href=accountsShow(account.id).url
                class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border cursor-pointer">
                <template v-if="mediaSrcs[account.id]">
                    <img
                    :src="mediaSrcs[account.id]"
                    :alt="account.thumbnail?.filename || 'Image'"
                    class="h-46 w-auto object-cover rounded-xl"
                    @error="onError(account.id, account.thumbnail?.relative_path ?? '')"
                    />
                </template>
                <template v-else>
                    <div class="h-46 w-auto flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-xl aspect-3/4">
                    <PlaceholderPattern />
                    </div>
                </template>
            </Link>
        </div>


        <!-- Pagination -->
        <div class="flex justify-center items-center gap-2 mt-8">
            <Link
                v-for="link in accounts.links"
                :key="link.label"
                :href="link.url || '#'"
                v-html="link.label"
                class="px-3 py-1 rounded border text-sm"
                :class="[
                    link.active
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'border-gray-400 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                ]"
            />
        </div>
        </div>
    </AppLayout>
</template>