<script setup>
import { ref, onMounted } from "vue";
import { useCounterStore } from "@/stores/counter";
import { storeToRefs } from "pinia";
import Appbar from "./Appbar.vue";
import TaskList from "./TaskList.vue";
import TaskModal from "./TaskModal.vue";
import { useTaskStore } from "@/stores/taskStore";

const counterStore = useCounterStore();
const { counter } = storeToRefs(counterStore);
const taskStore = useTaskStore();

const showModal = ref(false);
const taskToEdit = ref(null);

const openModalForNewTask = () => {
  taskToEdit.value = null;
  showModal.value = true;
};
const openModalForEditTask = (task) => {
  taskToEdit.value = task;
  showModal.value = true;
};
const closeModal = () => {
  showModal.value = false;
};
const saveTask = async (taskData) => {
  if (taskToEdit.value) {
    await taskStore.updateTask(taskToEdit.value.id, taskData);
  } else {
    await taskStore.createTask(taskData);
  }
  closeModal();
  await taskStore.fetchTasks();
};
const deleteTask = async (task) => {
  if (confirm(`Deseja realmente excluir "${task.nome}"?`)) {
    await taskStore.deleteTask(task.id);
    await taskStore.fetchTasks();
  }
};
const toggleTask = async (task) => {
  await taskStore.toggleTask(task.id);
  await taskStore.fetchTasks();
};

onMounted(() => {
  taskStore.fetchTasks();
});
</script>

<template>
  <div class="tasks">
    <Appbar @add-task="openModalForNewTask" />
    <p>Contador: {{ counter }}</p>
    <button @click="counterStore.increment">Incrementar</button>
    <hr />
    <TaskList
      :tasks="taskStore.orderedTasks"
      @edit-task="openModalForEditTask"
      @delete-task="deleteTask"
      @toggle-task="toggleTask"
    />
    <TaskModal
      :visible="showModal"
      :task="taskToEdit"
      @close="closeModal"
      @save="saveTask"
    />
  </div>
</template>
