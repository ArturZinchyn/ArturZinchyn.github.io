class Todo {
  constructor() {
    this.tasks = [];
    this.term = "";
  }

  add(text, date) {
    this.tasks.push({ text, date, done: false });
    this.save();
    this.draw();
  }

  remove(index) {
    this.tasks.splice(index, 1);
    this.save();
    this.draw();
  }

  toggle(index) {
    this.tasks[index].done = !this.tasks[index].done;
    this.save();
    this.draw();
  }

  getFilteredTasks() {
    if (this.term.length < 2) return this.tasks;

    return this.tasks.filter(task =>
      task.text.toLowerCase().includes(this.term)
    );
  }

  draw() {
    const list = document.getElementById("taskList");
    list.innerHTML = "";

    this.getFilteredTasks()
      .map((task) => ({
        task,
        index: this.tasks.indexOf(task)
      }))
      .forEach(({ task, index }) => {

        const li = document.createElement("li");
        li.className = "task";

        // CHECKBOX
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = task.done;
        checkbox.addEventListener("change", () => this.toggle(index));

        // TEXT
        const textSpan = document.createElement("span");
        textSpan.className = "task-text";
        textSpan.innerHTML = this.highlight(task.text);

        // DATE
        const dateSpan = document.createElement("span");
        dateSpan.className = "task-date";
        dateSpan.textContent = task.date || "";

        // EDIT
        textSpan.addEventListener("click", () => {

          const input = document.createElement("input");
          input.value = task.text;
          input.className = "edit-input";

          const dateInput = document.createElement("input");
          dateInput.type = "date";
          dateInput.value = task.date || "";
          dateInput.className = "edit-date";

          textSpan.replaceWith(input);
          dateSpan.replaceWith(dateInput);

          input.focus();

          setTimeout(() => {
            const handler = (e) => {
              if (li.contains(e.target)) return;

              const newText = input.value.trim();
              const newDate = dateInput.value;

              const today = new Date().toISOString().split("T")[0];

              if (
                newText.length >= 3 &&
                newText.length <= 255 &&
                (!newDate || newDate > today)
              ) {
                task.text = newText;
                task.date = newDate;
                this.save();
              }

              this.draw();
              document.removeEventListener("click", handler);
            };

            document.addEventListener("click", handler);
          }, 0);
        });

        // DELETE
        const delBtn = document.createElement("button");
        delBtn.textContent = "Delete";
        delBtn.addEventListener("click", () => this.remove(index));

        li.appendChild(checkbox);
        li.appendChild(textSpan);
        li.appendChild(dateSpan);
        li.appendChild(delBtn);

        list.appendChild(li);
      });
  }

  highlight(text) {
    if (this.term.length < 2) return text;

    return text.replace(
      new RegExp(this.term, "gi"),
      match => `<mark>${match}</mark>`
    );
  }

  save() {
    localStorage.setItem("tasks", JSON.stringify(this.tasks));
  }

  load() {
    this.tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    this.draw();
  }
}

// INIT 
document.todo = new Todo();

window.onload = () => {
  document.todo.load();
};

// ADD
document.getElementById("addBtn").addEventListener("click", () => {
  const text = document.getElementById("taskInput").value.trim();
  const date = document.getElementById("dateInput").value;

  const today = new Date().toISOString().split("T")[0];

  if (text.length < 3 || text.length > 255) return;
  if (date && date <= today) return;

  document.todo.add(text, date);

  document.getElementById("taskInput").value = "";
  document.getElementById("dateInput").value = "";
});

// SEARCH
document.getElementById("searchInput").addEventListener("input", (e) => {
  document.todo.term = e.target.value.toLowerCase();
  document.todo.draw();
});
