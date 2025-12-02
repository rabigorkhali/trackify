# Kanban Board Quick Start Guide

## 🚀 Quick Setup (3 Steps)

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Seed Ticket Statuses
```bash
php artisan db:seed --class=TicketStatusSeeder
```

### Step 3: Create Your First Project
1. Go to `/admin/projects`
2. Click "Create Project"
3. Enter:
   - **Name**: "My First Project"
   - **Key**: "MFP" (will be used for ticket keys like MFP-1, MFP-2)
   - **Description**: "Project description"
4. Click "Create"

## 📋 Using the Kanban Board

### Access the Board
```
/admin/projects/{project-id}/tickets-kanban
```

### Creating Your First Ticket
1. Click the **"Create Ticket"** button
2. Fill in:
   - **Board**: Select a board (create one if needed)
   - **Status**: "To Do"
   - **Title**: "My first ticket"
   - **Priority**: "Medium"
   - **Type**: "Task"
3. Click **"Create Ticket"**

### Moving Tickets
Just **drag and drop** tickets between columns! Status updates automatically.

## 🎯 Key Features at a Glance

| Feature | How to Use |
|---------|------------|
| **Drag & Drop** | Click and drag tickets between columns |
| **Quick Edit** | Click the ⋮ menu on any ticket card |
| **View Details** | Click anywhere on a ticket card |
| **Add Labels** | Open ticket → Click "Add Label" |
| **Add Checklist** | Open ticket → Click "Add Item" in checklist section |
| **Log Time** | Open ticket → Click "+" in Time Tracking |
| **Add Watchers** | Open ticket → Click "+" in Watchers section |
| **Comment** | Open ticket → Type in comment box → "Post Comment" |
| **Filter** | Use the filters at the top of Kanban board |

## 🎨 Default Ticket Statuses
- 🔘 **To Do** - New tickets
- 🔵 **In Progress** - Work started
- 🟡 **In Review** - Under review
- 🟢 **Done** - Completed
- 🔴 **Blocked** - Blocked/On hold

## 📊 Dashboard Routes

| Page | Route |
|------|-------|
| **Kanban Board** | `/admin/projects/{id}/tickets-kanban` |
| **Projects List** | `/admin/projects` |
| **Boards List** | `/admin/boards` |
| **Tickets List** | `/admin/projects/{id}/tickets` |
| **Labels Management** | `/admin/ticket-labels` |

## 💡 Pro Tips

1. **Use Labels** to categorize tickets (Bug, Feature, etc.)
2. **Add Watchers** to keep team members informed
3. **Log Time** to track effort on each ticket
4. **Use Checklists** to break down complex tasks
5. **Filter by Assignee** to see your tickets
6. **Check Activity Tab** to see ticket history

## 🎯 Common Workflows

### Creating a Bug Report
1. Create Ticket
2. Set Type: "Bug"
3. Set Priority: "High" or "Critical"
4. Add description with steps to reproduce
5. Assign to developer
6. Add "bug" label

### Sprint Planning
1. Create board for sprint (e.g., "Sprint 1")
2. Create tickets in "To Do"
3. Assign story points
4. Assign to team members
5. Set due dates
6. Move to "In Progress" when work starts

### Feature Development
1. Create Epic ticket
2. Create child Task tickets
3. Add checklists for requirements
4. Track time spent
5. Add watchers for stakeholders
6. Move through workflow

## 🔧 Customization

### Change Ticket Status Colors
Edit: `database/seeders/TicketStatusSeeder.php`

### Add Custom Labels
Go to: `/admin/ticket-labels` → "Create Label"

### Add Team Members
Go to: `/admin/users` → "Create User"

## 📱 Mobile Friendly
The Kanban board is fully responsive and works great on tablets and mobile devices!

## 🆘 Need Help?
Check the full documentation: `KANBAN_DOCUMENTATION.md`

## ✨ Features Included

✅ Drag & drop Kanban board
✅ Multiple projects & boards
✅ Custom labels with colors
✅ Checklist items with progress tracking
✅ Comments system
✅ Time tracking & logging
✅ Activity timeline
✅ Watchers system
✅ File attachments
✅ Priority levels (Low/Medium/High/Critical)
✅ Ticket types (Bug/Task/Story/Epic)
✅ Due date tracking with overdue indicators
✅ Story points for agile estimation
✅ Advanced filters
✅ Beautiful, modern UI
✅ Fully responsive design

---

**That's it! You're ready to start managing your projects! 🎉**

