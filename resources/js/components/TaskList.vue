<script setup>
import TaskItem from './TaskItem.vue';
const props = defineProps({
  tasks: {
    type: Array,
    default: () => []
  }
});
const emit = defineEmits(['edit-task', 'delete-task', 'toggle-task']);
</script>

<template>
  <div class="mt-8">
    <h3 class="text-lg font-bold mb-3">Lista de Tarefas</h3>
    <div v-if="!tasks.length" class="text-gray-500">Nenhuma tarefa cadastrada.</div>
    <div v-else class="flex flex-col gap-3">
      <TaskItem
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        @edit-task="emit('edit-task', $event)"
        @delete-task="emit('delete-task', $event)"
        @toggle-task="emit('toggle-task', $event)"
      />
    </div>
  </div>
</template>
