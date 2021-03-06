A plugin for Kanboard app
==============================

- This is a plugin coded to be used by Kanboard app.
- Kanboard is mainly developed by Frédéric Guillot, but more than 267+ people have contributed.
- Kanboard is a free and open source Kanban project management software.

TaskCreatedDate plugin
==============================

- This plugin allows you to update the created date for a given task.

Author
------

- Olavo Alexandrino [www.oalexandrino.com.br]
- This plugin is distributed under the permissive MIT License

Requirements
------------

- Kanboard >= 1.0.35
- Development and tested in the following environment: kanboard-1.2.14 and kanboard-1.2.15, MySQL 8.0.22-0ubuntu0.20.04.2, and PHP Version 7.4.12

Installation
------------

You have the choice between 3 methods:

1. Install the plugin from the Kanboard plugin manager in one click
2. Download the zip file and decompress everything under the directory `plugins/TaskCreatedDate`
3. Clone this repository into the folder `plugins/TaskCreatedDate`

Note: Plugin folder is case-sensitive.

Documentation
-------------

1. After installing, go to Kanboard settings page.
2. Go to "TaskCreatedDate settings" view. It is going to be one of the menu options.
3. Enable the plugin by checkin "Yes".
4. Go to any board of any project.
5. Click on any task.
6. Click on the link for editing the current task.
7. Below the "Priority" setting for the selected task, there is a link "Update the creation date for this task"
8. Click on it
9. Read the "Warning message" before going to the next page
10. Provide a new and valid date for the creation date field

### Business restrictions

* Only administrators can update task creation dates.
* You are not allowed to update tasks assigned to someone else.
* The provided date must be earlier than the task due date.
* The provided date must be earlier than the task started date.
* The provided date must be earlier than the task completed date.
* The provided date must be earlier than the last date of movement of the task.

Very important
-------------

### Why do that?

* Changing the task creation dates is a procedure that requires attention, responsibility, sincerity and respect for the team involved, as it will directly interfere the project's flow metrics.

### Who can do that?

* It should be done only by administrators and when there is a need to include an old task or when you forget to create a task at the right time.

### Changes will affect your team performance

* You should not take advantage of this functionality because it will decrease or increase the lead time, reaction time, cycle time, and other insights of your team performance.

### Everything will be recorded

* Keep in mind that every change (who did it and when did it) will be recorded at the page activity stream of the this task.



