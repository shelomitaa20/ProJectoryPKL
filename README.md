<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Project Management Web Application (ProJectory)

**Developed using Laravel (PHP), JavaScript, HTML, CSS**  
Development Environment: **Visual Studio Code**

## Project Overview
This web application is designed to manage projects, tasks, and team collaborations. It includes features for both admin and team members to track project progress, manage tasks, and view detailed reports.

## Features

### Authentication
- **Login/Register**:
  - **Register**: Team Member registration.
  - **Login**: Admin & Team Member login.

### Admin Dashboard
- Overview of:
  - Total projects
  - Projects in progress
  - Completed projects
  - Total users

### Team Dashboard
- Overview of:
  - Total projects owned by the team member
  - Projects in progress
  - Completed projects
  - Total tasks to be completed

### Profile Management
- **Edit Profile**: Update personal details.

### Project Management
- **Project List**: View a table of projects owned by the team member with options to:
  - View project details
  - Edit project
  - Delete project
- **Add New Project**: Modal to create a new project.
  - Assign team members by their email to collaborate on the project.

### Project Details
- **Project Description**: View detailed project information.
- **Project Content**: Tasks related to the project are managed under three status tables:
  - **To Do**: Tasks to be done (status can be edited, tasks can be deleted).
  - **In Progress**: Ongoing tasks (status can be edited, tasks can be deleted).
  - **Completed**: Finished tasks (tasks can be deleted).

### Task Management
- **Add New Task**: Modal to add a new task (status defaults to "To Do").
  
### Admin Reports
- **Monthly Report**: Admin can generate reports on project and task progress across the system.

## Technology Stack
- **Laravel** (Backend)
- **JavaScript** (Frontend)
- **HTML/CSS** (Frontend)
- **Visual Studio Code** (Development IDE)

## Usage
- Admins can log in to view overall project and user metrics, manage team members, and generate reports.
- Team members can manage their projects, collaborate with other team members, and track task completion.
