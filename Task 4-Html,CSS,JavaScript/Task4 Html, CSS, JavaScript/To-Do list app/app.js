document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const todoInput = document.getElementById('todo-input');
    const addBtn = document.getElementById('add-btn');
    const todoList = document.getElementById('todo-list');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const remainingCount = document.getElementById('remaining-count');
    const clearCompletedBtn = document.getElementById('clear-completed');

    // State
    let todos = [];
    let currentFilter = 'all';

    // Initialize the app
    function init() {
        loadTodos();
        renderTodos();
        setupEventListeners();
    }

    // Load todos from localStorage
    function loadTodos() {
        const storedTodos = localStorage.getItem('todos');
        if (storedTodos) {
            todos = JSON.parse(storedTodos);
        }
    }

    // Save todos to localStorage
    function saveTodos() {
        localStorage.setItem('todos', JSON.stringify(todos));
    }

    // Render todos based on current filter
    function renderTodos() {
        todoList.innerHTML = '';

        const filteredTodos = todos.filter(todo => {
            if (currentFilter === 'active') return !todo.completed;
            if (currentFilter === 'completed') return todo.completed;
            return true;
        });

        if (filteredTodos.length === 0) {
            const emptyMessage = document.createElement('li');
            emptyMessage.textContent = currentFilter === 'all' ? 'No tasks yet!' :
                currentFilter === 'active' ? 'No active tasks!' : 'No completed tasks!';
            emptyMessage.classList.add('empty-message');
            todoList.appendChild(emptyMessage);
        } else {
            filteredTodos.forEach(todo => {
                const todoItem = createTodoElement(todo);
                todoList.appendChild(todoItem);
            });
        }

        updateStats();
    }

    // Create a todo DOM element
    function createTodoElement(todo) {
        const li = document.createElement('li');
        li.className = 'todo-item';
        if (todo.completed) li.classList.add('completed');
        li.dataset.id = todo.id;

        li.innerHTML = `
            <input type="checkbox" class="todo-checkbox" ${todo.completed ? 'checked' : ''}>
            <span class="todo-text">${todo.text}</span>
            <div class="todo-actions">
                <button class="edit-btn"><i class="fas fa-edit"></i></button>
                <button class="delete-btn"><i class="fas fa-trash"></i></button>
            </div>
        `;

        return li;
    }

    // Update the stats (remaining items count)
    function updateStats() {
        const activeTodos = todos.filter(todo => !todo.completed).length;
        remainingCount.textContent = `${activeTodos} ${activeTodos === 1 ? 'item' : 'items'} left`;
    }

    // Add a new todo
    function addTodo() {
        const text = todoInput.value.trim();
        if (text) {
            const newTodo = {
                id: Date.now(),
                text: text,
                completed: false
            };

            todos.unshift(newTodo);
            saveTodos();
            renderTodos();
            todoInput.value = '';
            todoInput.focus();
        }
    }

    // Toggle todo completion status
    function toggleTodo(id) {
        todos = todos.map(todo =>
            todo.id === Number(id) ? { ...todo, completed: !todo.completed } : todo
        );
        saveTodos();
        renderTodos();
    }

    // Delete a todo
    function deleteTodo(id) {
        todos = todos.filter(todo => todo.id !== Number(id));
        saveTodos();
        renderTodos();
    }

    // Edit a todo
    function editTodo(id, newText) {
        if (newText.trim()) {
            todos = todos.map(todo =>
                todo.id === Number(id) ? { ...todo, text: newText.trim() } : todo
            );
            saveTodos();
            renderTodos();
        }
    }

    // Clear all completed todos
    function clearCompleted() {
        todos = todos.filter(todo => !todo.completed);
        saveTodos();
        renderTodos();
    }

    // Set up event listeners
    function setupEventListeners() {
        // Add todo
        addBtn.addEventListener('click', addTodo);
        todoInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') addTodo();
        });

        // Filter todos
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                renderTodos();
            });
        });

        // Clear completed
        clearCompletedBtn.addEventListener('click', clearCompleted);

        // Delegated events for dynamic elements
        todoList.addEventListener('click', function (e) {
            const todoItem = e.target.closest('.todo-item');
            if (!todoItem) return;

            const id = todoItem.dataset.id;

            // Checkbox click
            if (e.target.classList.contains('todo-checkbox')) {
                toggleTodo(id);
            }

            // Delete button click
            if (e.target.closest('.delete-btn')) {
                deleteTodo(id);
            }

            // Edit button click
            if (e.target.closest('.edit-btn')) {
                const todoText = todoItem.querySelector('.todo-text');
                const currentText = todoText.textContent;
                const newText = prompt('Edit your task:', currentText);
                if (newText !== null) {
                    editTodo(id, newText);
                }
            }
        });
    }

    // Initialize the app
    init();
});