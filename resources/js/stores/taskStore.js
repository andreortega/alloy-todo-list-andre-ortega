import { defineStore } from 'pinia';
import taskService from '../services/taskService';

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    loadingFetch: false,
    loadingCreate: false,
    loadingUpdate: false,
    loadingDelete: false,
    loadingToggle: false,
    errorFetch: null,
    errorCreate: null,
    errorUpdate: null,
    errorDelete: null,
    errorToggle: null,
  }),
  getters: {
    // Ativas primeiro, finalizadas depois
    orderedTasks(state) {
      return [...state.tasks].sort((a, b) => {
        if (a.finalizado === b.finalizado) return 0;
        return a.finalizado ? 1 : -1;
      });
    },
    // Útil se quiser saber se tem alguma operação rodando
    isLoadingAny(state) {
      return state.loadingFetch || state.loadingCreate || state.loadingUpdate || state.loadingDelete || state.loadingToggle;
    }
  },
  actions: {
    async fetchTasks() {
      this.loadingFetch = true;
      this.errorFetch = null;
      try {
        this.tasks = await taskService.getTasks();
      } catch (e) {
        this.errorFetch = 'Erro ao buscar tarefas';
      }
      this.loadingFetch = false;
    },
    async createTask(task) {
      this.loadingCreate = true;
      this.errorCreate = null;
      try {
        await taskService.createTask(task);
        await this.fetchTasks();
      } catch (e) {
        this.errorCreate = 'Erro ao criar tarefa';
      }
      this.loadingCreate = false;
    },
    async updateTask(id, data) {
      this.loadingUpdate = true;
      this.errorUpdate = null;
      try {
        await taskService.updateTask(id, data);
        await this.fetchTasks();
      } catch (e) {
        this.errorUpdate = 'Erro ao atualizar tarefa';
      }
      this.loadingUpdate = false;
    },
    async deleteTask(id) {
      this.loadingDelete = true;
      this.errorDelete = null;
      try {
        await taskService.deleteTask(id);
        await this.fetchTasks();
      } catch (e) {
        this.errorDelete = 'Erro ao excluir tarefa';
      }
      this.loadingDelete = false;
    },
    async toggleTask(id) {
      this.loadingToggle = true;
      this.errorToggle = null;
      try {
        await taskService.toggleTask(id);
        await this.fetchTasks();
      } catch (e) {
        this.errorToggle = 'Erro ao atualizar status da tarefa';
      }
      this.loadingToggle = false;
    }
  }
});
