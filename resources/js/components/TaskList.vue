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
  <div>
    <h3>Lista de Tarefas</h3>
    <div v-if="!tasks.length">Nenhuma tarefa cadastrada.</div>
    <div v-else>
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
