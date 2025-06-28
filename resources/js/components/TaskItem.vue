<script setup>
const props = defineProps({
  task: { type: Object, required: true }
});
const emit = defineEmits(['edit-task', 'delete-task', 'toggle-task']);
</script>

<template>
  <div
    class="task-item"
    style="display: flex; align-items: center; justify-content: space-between;"
  >
    <div style="display: flex; align-items: center; gap: 0.5rem; flex: 1;">
      <!-- Checkbox finalizado -->
      <input
        type="checkbox"
        :checked="task.finalizado"
        @change="$emit('toggle-task', task)"
        title="Marcar como finalizada"
        style="accent-color: #25cf88; width: 18px; height: 18px; cursor: pointer;"
      />
      <span :class="{ 'done': task.finalizado }" style="flex: 1;">
        {{ task.nome }}
      </span>
    </div>
    <div style="display: flex; gap: 0.5rem;">
      <!-- Botão editar -->
      <button
        @click.stop="$emit('edit-task', task)"
        class="btn-edit"
        title="Editar"
      >
        <!-- Ícone lápis SVG branco em círculo verde -->
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
          <circle cx="14" cy="14" r="14" fill="#25cf88"/>
          <path d="M19.3 11.38L16.62 8.7a1.17 1.17 0 0 0-1.66 0l-7.16 7.16a.58.58 0 0 0-.15.27l-1.02 3.06a.58.58 0 0 0 .73.73l3.06-1.02a.58.58 0 0 0 .27-.15l7.16-7.16a1.17 1.17 0 0 0 0-1.66zm-7.6 8.22l-2.04.68.68-2.04 5.96-5.96 1.36 1.36-5.96 5.96zm7.09-7.09-1.36-1.36.71-.71a.19.19 0 0 1 .27 0l1.09 1.09a.19.19 0 0 1 0 .27l-.71.71z" fill="#fff"/>
        </svg>
      </button>
      <!-- Botão deletar -->
      <button
        @click.stop="$emit('delete-task', task)"
        class="btn-delete"
        title="Excluir"
      >
        <!-- Ícone X SVG branco em círculo cinza escuro -->
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
          <circle cx="14" cy="14" r="14" fill="#404040"/>
          <path d="M18 10L10 18M10 10l8 8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<style scoped>
.done {
  text-decoration: line-through;
  color: #aaa;
}
.task-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid #eee;
  cursor: pointer;
}
.btn-edit, .btn-delete {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
}
</style>
