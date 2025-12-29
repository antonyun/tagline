<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Mass Upload',
        href: dashboard().url,
    },
];

const props = usePage().props;
//const successMessage = props.flash.success;

/* onMounted(() => {
  if (successMessage) {
    alert(successMessage)
  }
}) */

const files = ref<File[]>([])
const form = useForm({
  files: null
})
const uploaded = ref<any[]>([])
const loading = ref(false)
const error = ref('')

function handleFiles(event: Event) {
  const target = event.target as HTMLInputElement
  if (target.files) {
    files.value = Array.from(target.files)
  }
}

function uploadFiles() {
    loading.value = true
    form.files = files.value
    form.post('/media', {
      forceFormData: true,

      onSuccess: (res) => {
        console.log('res:', res)
        console.log('usePage props:', usePage().props.value)
        console.log('Success:', props.flash)
        console.log('Success message:', props.flash?.message)
        console.log('Returned data:', props.flash?.data)
      },

      onFinish: () => loading.value = false,
    })
}

</script>

<template>
  <Head title="Mass Upload" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div v-if="props.flash?.success" class="mb-4 rounded-lg bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 p-4 border border-green-300 dark:border-green-600">
        {{ props.flash?.success }}
      </div>
      <h1>Mass Upload Files</h1>

      <input type="file" multiple @change="handleFiles" />

      <button @click="uploadFiles" :disabled="files.length === 0 || loading">
        Upload
      </button>

      <div v-if="loading">Uploading...</div>

      <div v-if="uploaded.length">
        <h2>Uploaded files:</h2>
        <ul>
          <li v-for="(file, index) in uploaded" :key="index">
            {{ file.original_name }} (stored as: {{ file.filename }})
          </li>
        </ul>
      </div>

      <div v-if="error" style="color: red;">
        {{ error }}
      </div>
    </div>
  </AppLayout>
</template>