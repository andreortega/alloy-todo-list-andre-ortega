// resources/js/stores/taskStore.js
import { defineStore } from 'pinia';
import taskService from '../services/taskService';

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    loading: false,
  }),
  getters: {
    // Ativas primeiro, finalizadas depois
    orderedTasks(state) {
      return [...state.tasks]
        .sort((a, b) => {
          if (a.finalizado === b.finalizado) return 0;
          return a.finalizado ? 1 : -1;
        });
    }
  },
  actions: {
    async fetchTasks() {
      this.loading = true;
      this.tasks = await taskService.getTasks();
      this.loading = false;
    },
    async createTask(task) {
      await taskService.createTask(task);
      await this.fetchTasks();
    },
    async updateTask(id, data) {
      await taskService.updateTask(id, data);
      await this.fetchTasks();
    },
    async deleteTask(id) {
      await taskService.deleteTask(id);
      await this.fetchTasks();
    },
    async toggleTask(id) {
      await taskService.toggleTask(id);
      await this.fetchTasks();
    }
  }
});
