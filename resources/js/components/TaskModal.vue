<script setup>
import TaskForm from './TaskForm.vue';
import { ref, watch } from 'vue';

const props = defineProps({
  visible: Boolean,
  task: Object,
  loading: Boolean,
  error: String,
});
const emit = defineEmits(['close', 'save']);

const localTask = ref(null);

watch(
  () => props.task,
  (newTask) => {
    localTask.value = newTask ? { ...newTask } : null;
  },
  { immediate: true }
);

function handleSave(taskData) {
  emit('save', taskData);
}
</script>

<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center px-4"
    aria-modal="true"
    role="dialog"
  >
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative animate-fadeIn">
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 w-12 h-12 text-gray-400 hover:text-gray-700 transition flex items-center justify-center"
        aria-label="Fechar"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
      <h2 class="text-2xl font-bold mb-8 text-gray-900 pb-4 border-b border-gray-100">{{ localTask ? 'Editar Tarefa' : 'Nova Tarefa' }}</h2>
      <div v-if="error" class="text-red-500 mb-2">{{ error }}</div>
      <TaskForm
        :modelValue="localTask"
        :loading="loading"
        @submit="handleSave"
      />
    </div>
  </div>
</template>
