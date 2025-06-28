const API_URL = '/api/tasks';

export default {
  async getTasks() {
    const res = await fetch(API_URL);
    return await res.json();
  },
  async createTask(task) {
    const res = await fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(task)
    });
    return await res.json();
  },
  async updateTask(id, data) {
    const res = await fetch(`${API_URL}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    return await res.json();
  },
  async deleteTask(id) {
    await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
  },
  async toggleTask(id) {
    const res = await fetch(`${API_URL}/${id}/toggle`, { method: 'PATCH' });
    return await res.json();
  }
};
