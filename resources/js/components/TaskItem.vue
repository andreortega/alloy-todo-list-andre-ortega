<script setup>
const props = defineProps({
  task: { type: Object, required: true }
});
const emit = defineEmits(['edit-task', 'delete-task', 'toggle-task']);
</script>

<template>
  <div
    class="flex items-center bg-gray-50 rounded-xl px-4 py-3 shadow-sm w-full"
  >
    <input
      type="checkbox"
      :checked="task.finalizado"
      @change="$emit('toggle-task', task)"
      title="Marcar como finalizada"
      class="accent-green-500 w-5 h-5 mr-3 shrink-0"
    />
    <span
      :class="{ 'line-through text-gray-400': task.finalizado }"
      class="flex-1 text-base truncate"
    >
      {{ task.nome }}
    </span>
    <div class="flex gap-2 ml-3 shrink-0">
      <button
        @click.stop="$emit('edit-task', task)"
        class="bg-green-500 hover:bg-green-600 rounded-full p-2 shadow transition"
        title="Editar"
      >
        <svg width="18" height="18" viewBox="0 0 28 28" fill="none">
          <circle cx="14" cy="14" r="14" fill="#25cf88"/>
          <path d="M19.3 11.38L16.62 8.7a1.17 1.17 0 0 0-1.66 0l-7.16 7.16a.58.58 0 0 0-.15.27l-1.02 3.06a.58.58 0 0 0 .73.73l3.06-1.02a.58.58 0 0 0 .27-.15l7.16-7.16a1.17 1.17 0 0 0 0-1.66zm-7.6 8.22l-2.04.68.68-2.04 5.96-5.96 1.36 1.36-5.96 5.96zm7.09-7.09-1.36-1.36.71-.71a.19.19 0 0 1 .27 0l1.09 1.09a.19.19 0 0 1 0 .27l-.71.71z" fill="#fff"/>
        </svg>
      </button>
      <button
        @click.stop="$emit('delete-task', task)"
        class="bg-gray-700 hover:bg-gray-900 rounded-full p-2 shadow transition"
        title="Excluir"
      >
        <svg width="18" height="18" viewBox="0 0 28 28" fill="none">
          <circle cx="14" cy="14" r="14" fill="#404040"/>
          <path d="M18 10L10 18M10 10l8 8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
  </div>
</template>
