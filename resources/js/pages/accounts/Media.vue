<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as mediaIndex } from '@/routes/media';
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
        title: 'Media',
        href: mediaIndex().url,
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

interface Link {
  url: string | null;
  label: string;
  active: boolean;
}

interface MediaPagination {
  current_page: number;
  data: Media[];
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
  media: MediaPagination;
}

const props = defineProps<Props>();

const mediaSrcs = reactive<Record<number, string>>({});

props.media.data.forEach(media => {
    mediaSrcs[media.id] = media.relative_thumbnail_path ?? media.relative_path ?? '';
});

function onError(mediaId: number, fallbackSrc: string) {
  if (mediaSrcs[mediaId] !== fallbackSrc) {
    mediaSrcs[mediaId] = fallbackSrc;
  } else {
    // both failed, clear to show placeholder
    mediaSrcs[mediaId] = '';
  }
}

</script>

<template>
    <Head title="Media" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1>Media</h1>

        <div class="flex flex-wrap justify-start gap-3">
            <div
            v-for="media in props.media.data"
            :key="media.id"
            class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border cursor-pointer">
            <template v-if="mediaSrcs[media.id]">
                <a target="_blank" :href="media.relative_path ?? '#'">
                    <img
                    :src="mediaSrcs[media.id]"
                    :alt="media.filename || 'Image'"
                    class="h-46 w-auto object-cover rounded-xl"
                    @error="onError(media.id, media.relative_path ?? '')"
                    />
                </a>
            </template>
            <template v-else>
                <div class="h-46 w-auto flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-xl aspect-3/4">
                <PlaceholderPattern />
                </div>
            </template>
            </div>
        </div>


        <!-- Pagination -->
        <div class="flex justify-center items-center gap-2 mt-8">
            <Link
                v-for="link in media.links"
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