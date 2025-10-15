# 📝 Task Manager με Team Collaboration - Laravel Project

## 🎯 Concept

Ένα **απλό task management system** όπου teams μπορούν να δημιουργούν projects, να προσθέτουν tasks, και να παρακολουθούν την πρόοδο.


---

## 🚀 Core Features

### 1️⃣ **Projects**
- Create/Edit/Delete projects
- Assign team members
- Project status (Active/Completed/Archived)
- Project description & deadline

### 2️⃣ **Tasks**
- CRUD για tasks μέσα σε project
- Task priority (Low/Medium/High)
- Task status (Todo/In Progress/Done)
- Assign task σε συγκεκριμένο user
- Due date & description
- Comments on tasks

### 3️⃣ **Team Collaboration**
- Invite users σε project
- Role στο project (Owner/Member/Viewer)
- Real-time updates (optional με Livewire)

### 4️⃣ **Dashboard**
- My tasks overview
- Projects I'm involved in
- Upcoming deadlines
- Simple statistics

---

## 🗄️ Database Schema

```sql
users
├── id
├── name
├── email
├── avatar
└── timestamps

projects
├── id
├── user_id (creator)
├── name
├── description
├── status (active/completed/archived)
├── deadline
└── timestamps

project_user (pivot)
├── project_id
├── user_id
├── role (owner/member/viewer)
└── timestamps

tasks
├── id
├── project_id
├── assigned_to (user_id nullable)
├── title
├── description
├── status (todo/in_progress/done)
├── priority (low/medium/high)
├── due_date
└── timestamps

comments
├── id
├── task_id
├── user_id
├── content
└── timestamps
```

---

## 💻 Laravel Features

### 1. **Relationships**
```php
// Project.php
public function owner() {
    return $this->belongsTo(User::class, 'user_id');
}

public function members() {
    return $this->belongsToMany(User::class)
                ->withPivot('role')
                ->withTimestamps();
}

public function tasks() {
    return $this->hasMany(Task::class);
}

// Task.php
public function project() {
    return $this->belongsTo(Project::class);
}

public function assignedUser() {
    return $this->belongsTo(User::class, 'assigned_to');
}

public function comments() {
    return $this->hasMany(Comment::class);
}
```

### 2. **Scopes**
```php
// Task.php
public function scopeOverdue($query) {
    return $query->where('due_date', '<', now())
                 ->where('status', '!=', 'done');
}

public function scopeMyTasks($query, $userId) {
    return $query->where('assigned_to', $userId);
}

public function scopeHighPriority($query) {
    return $query->where('priority', 'high');
}
```

### 3. **Policies**
```php
// ProjectPolicy.php
public function update(User $user, Project $project) {
    return $project->members()
        ->where('user_id', $user->id)
        ->wherePivot('role', 'owner')
        ->exists();
}

public function addTask(User $user, Project $project) {
    return $project->members()
        ->where('user_id', $user->id)
        ->wherePivotIn('role', ['owner', 'member'])
        ->exists();
}
```

### 4. **Form Requests**
```php
// StoreTaskRequest.php
public function rules() {
    return [
        'project_id' => 'required|exists:projects,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'required|date|after:today',
        'assigned_to' => 'nullable|exists:users,id',
    ];
}
```

### 5. **Events (Optional)**
```php
// TaskCompletedEvent.php
// Στέλνει notification στον project owner
```

---

## 📱 Simple UI Structure

### Dashboard
```
┌──────────────────────────────┐
│  My Tasks (5)                │
├──────────────────────────────┤
│  ☐ Fix login bug (High)     │
│  ☐ Design homepage           │
│  ☑ Write docs                │
└──────────────────────────────┘

┌──────────────────────────────┐
│  My Projects (3)             │
├──────────────────────────────┤
│  • Website Redesign (12/20)  │
│  • Mobile App (5/15)         │
└──────────────────────────────┘

┌──────────────────────────────┐
│  Upcoming Deadlines          │
├──────────────────────────────┤
│  • Fix bug (Tomorrow)        │
│  • Deploy v2 (2 days)        │
└──────────────────────────────┘
```

### Project View (Kanban Style)
```
Project: Website Redesign
━━━━━━━━━━━━━━━━━━━━━━━━━━━

Todo          In Progress      Done
┌────────┐   ┌────────┐      ┌────────┐
│ Task 1 │   │ Task 3 │      │ Task 5 │
│ Task 2 │   │ Task 4 │      │ Task 6 │
└────────┘   └────────┘      └────────┘
```

---

## 🎨 Tech Stack

### Backend
- Laravel 11.x
- MySQL
- Blade Templates

### Frontend
- Tailwind CSS
- Alpine.js (για simple interactions)
- SortableJS (για drag-and-drop kanban)

### Optional
- Livewire (για real-time updates)
- Laravel Notifications (για email alerts)

---

## 🌟 Bonus Ideas (If Time)

1. **File Attachments** σε tasks (με Storage)
2. **Task Templates** (για recurring tasks)
3. **Time Tracking** (πόσες ώρες πήρε το task)
4. **Activity Log** (ποιος έκανε τι και πότε)
5. **Export Project** σε PDF
6. **Dark Mode** toggle

---

## 📚 Learning Outcomes

✅ Eloquent relationships (belongsTo, hasMany, belongsToMany)  
✅ Policies & Authorization  
✅ Form Requests & Validation  
✅ Scopes για reusable queries  
✅ Blade components  
✅ Basic Alpine.js interactions  
✅ Testing με PHPUnit  

---
