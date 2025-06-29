<script setup>
import { ref, onMounted, computed } from "vue";
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

const loadingModal = computed(() => taskToEdit.value
  ? taskStore.loadingUpdate
  : taskStore.loadingCreate
);
const errorModal = computed(() => taskToEdit.value
  ? taskStore.errorUpdate
  : taskStore.errorCreate
);

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
  if (!loadingModal.value && !errorModal.value) {
    closeModal();
    await taskStore.fetchTasks();
  }
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
  <div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header Sticky -->
    <div class="sticky top-0 bg-gray-50 z-10 py-4 -mx-4 px-4 mb-8">
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
          <h2 class="text-3xl font-bold text-gray-800">Tarefas</h2>
          <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
            {{ taskStore.tasks.length }} tarefas
          </div>
        </div>
        <button @click="openModalForNewTask" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl flex items-center">
          <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
          </svg>
          <span class="text-white">Nova Tarefa</span>
        </button>
      </div>
    </div>

    <!-- Lista de Tarefas -->
    <div class="bg-white rounded-xl shadow-lg p-6">
      <div v-if="taskStore.loading" class="text-center py-8">
        <div class="text-gray-500">Carregando tarefas...</div>
      </div>
      
      <div v-else-if="taskStore.tasks.length === 0" class="text-center py-8">
        <div class="text-gray-500">Nenhuma tarefa encontrada</div>
      </div>
      
      <div v-else class="space-y-4">
        <div v-for="task in taskStore.orderedTasks" :key="task.id" 
             class="p-4 rounded-lg border border-gray-200 hover:shadow-md hover:bg-gray-100 transition-all cursor-pointer"
             :class="task.finalizado ? 'bg-gray-50' : 'bg-white'">
          
          <!-- Layout condicional: items-center quando sem descrição, items-start quando com descrição -->
          <div class="flex justify-between" :class="task.descricao ? 'items-start' : 'items-center'">
            <div class="flex-1" v-if="task.descricao">
              <!-- Com descrição: layout vertical -->
              <label class="w-checkbox checkbox-field flex items-center cursor-pointer">
                <div class="w-checkbox-input w-checkbox-input--inputType-custom checkbox margin-right-10"></div>
                <input type="checkbox" 
                       :checked="task.finalizado" 
                       @change="toggleTask(task)"
                       style="opacity:0;position:absolute;z-index:-1">
                <span class="checkbox-label w-form-label font-medium"
                      :class="task.finalizado ? 'checked line-through text-gray-500' : 'text-gray-800'">
                  {{ task.nome }}
                </span>
              </label>
              <div class="task-details text-gray-600 text-sm mt-1 ml-6">
                <div>{{ task.descricao }}</div>
              </div>
            </div>
            
            <!-- Sem descrição: layout horizontal centralizado -->
            <label v-else class="w-checkbox checkbox-field flex items-center cursor-pointer flex-1">
              <div class="w-checkbox-input w-checkbox-input--inputType-custom checkbox margin-right-10"></div>
              <input type="checkbox" 
                     :checked="task.finalizado" 
                     @change="toggleTask(task)"
                     style="opacity:0;position:absolute;z-index:-1">
              <span class="checkbox-label w-form-label font-medium"
                    :class="task.finalizado ? 'checked line-through text-gray-500' : 'text-gray-800'">
                {{ task.nome }}
              </span>
            </label>
            
            <!-- Botões alinhados à direita -->
            <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
              <div class="px-3 py-1 rounded-full text-sm font-medium"
                   :class="task.finalizado ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                {{ task.finalizado ? 'Concluída' : 'Pendente' }}
              </div>
              <button @click="openModalForEditTask(task)" 
                      class="cursor-pointer p-3 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5l3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </button>
              <button @click="deleteTask(task)" 
                      class="cursor-pointer p-3 hover:bg-red-50 rounded-lg transition-colors text-red-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <TaskModal
      :visible="showModal"
      :task="taskToEdit"
      :loading="loadingModal"
      :error="errorModal"
      @close="closeModal"
      @save="saveTask"
    />
  </div>
</template>
