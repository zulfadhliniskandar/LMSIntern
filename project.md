<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" />
  <img src="https://img.shields.io/badge/Filament-4-FBBF24?style=for-the-badge&logo=data:image/svg+xml;base64,&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" />
  <img src="https://img.shields.io/badge/Gemini_AI-Powered-4285F4?style=for-the-badge&logo=google&logoColor=white" />
</p>

# 🎓 AI-Powered Learning Management System (LMS)

> **A next-generation, AI-enhanced Learning Management System designed to revolutionize the educational experience for students, instructors, and academic advisors.**

Built as a **Final Year Project (FYP)**, this system goes beyond traditional LMS platforms by integrating cutting-edge **Artificial Intelligence** capabilities — including AI-powered text simplification, automated plagiarism & AI-content detection, intelligent quiz generation, text-to-speech audio synthesis, and podcast-style NotebookLM audio generation — all within a modern, real-time, responsive web application.

---

## 📋 Table of Contents

- [System Overview](#-system-overview)
- [Key Features](#-key-features)
- [Technology Stack](#-technology-stack)
- [System Architecture](#-system-architecture)
- [User Roles & Access Control](#-user-roles--access-control)
- [AI-Powered Services](#-ai-powered-services)
- [Module Breakdown](#-module-breakdown)
- [Database Schema](#-database-schema)
- [Real-Time Features](#-real-time-features)
- [Security & Authentication](#-security--authentication)
- [Background Job Processing](#-background-job-processing)
- [Getting Started](#-getting-started)

---

## 🌟 System Overview

This Learning Management System is a **full-featured, multi-role academic platform** that serves three primary user groups within a university ecosystem:

| Role              | Description                                                                                                |
| ----------------- | ---------------------------------------------------------------------------------------------------------- |
| 🎒 **Student**    | Enrol in courses, submit assignments, take quizzes, access AI study tools, and manage advisor appointments |
| 👨‍🏫 **Instructor** | Manage courses, create assignments & quizzes, review submissions with plagiarism reports, advise students  |
| 🛡️ **Admin**      | Full system administration via Filament Admin Panel — manage users, departments, programmes, and subjects  |

The platform is built around a **department → programme → subject** academic hierarchy, with subjects assigned to instructors and enrolled by students.

---

## ✨ Key Features

### 📚 Core LMS Features

- **Course Management** — Instructors create subjects with materials, file uploads, and external links
- **Assignment System** — Full assignment lifecycle: creation → submission → plagiarism check → grading
- **Quiz Engine** — Instructor-created quizzes with MCQ support, time limits, open/close dates, max attempts, and auto-grading
- **Material Repository** — Per-subject file management supporting PDF, DOCX, PPTX, and TXT uploads
- **Student Registration & Enrollment** — Self-service student and instructor registration with programme-based enrollment
- **Academic Advising System** — Advisor assignment, student-advisor appointment booking with approval/rejection workflows

### 🤖 AI-Powered Features

- **AI Text Simplification** — Upload complex academic documents and receive AI-simplified versions with key points, summaries, and readability scores (powered by Google Gemini)
- **AI Plagiarism Detection** — Cosine similarity-based cross-submission plagiarism detection with percentage matching
- **AI Content Detection** — Detects AI-generated content in student submissions using multiple detection strategies
- **AI Quiz Generator** — Auto-generates MCQ quizzes from uploaded learning materials using Gemini AI
- **AI Audio Generator (Text-to-Speech)** — Converts learning materials or text into spoken audio with customizable voice, language, gender, speech rate, pitch, and style (via Edge TTS)
- **AI Podcast Generator (NotebookLM-style)** — Generates conversational, podcast-style audio discussions from uploaded learning materials
- **AI Script Generation** — AI-powered script generation for solo and podcast-style audio content

### 💬 Communication & Real-Time

- **Real-Time Course Discussion Chat** — WebSocket-powered live discussion per subject with message pinning (via Laravel Reverb)
- **Notification System** — In-app notifications via Filament Notifications

### 🔐 Security & Authentication

- **Passkey Authentication** — Passwordless login via WebAuthn / FIDO2 passkeys
- **Two-Factor Authentication (2FA)** — TOTP-based two-factor authentication
- **User Impersonation** — Admin can impersonate any non-admin user for debugging and support
- **Role-Based Access Control** — Route-level 403 protection based on user role (Student / Instructor / Admin)

---

## 🛠️ Technology Stack

### Backend

| Technology          | Version | Purpose                                                            |
| ------------------- | ------- | ------------------------------------------------------------------ |
| **PHP**             | 8.3+    | Core language                                                      |
| **Laravel**         | 13.x    | Backend framework                                                  |
| **Livewire**        | 3.x     | Full-stack reactive UI components                                  |
| **Filament**        | 4.x     | Admin panel & table builder                                        |
| **Laravel Fortify** | 1.x     | Authentication backend (Passkeys, 2FA)                             |
| **Laravel Reverb**  | 1.x     | WebSocket server for real-time broadcasting                        |
| **Laravel Queue**   | —       | Background job processing (Audio, Plagiarism, Material Processing) |
| **MySQL**           | 8.x     | Database engine                                                    |

### Frontend

| Technology                   | Version | Purpose                                 |
| ---------------------------- | ------- | --------------------------------------- |
| **Livewire Flux**            | 2.x     | Modern UI component library             |
| **Livewire Volt**            | 1.x     | Single-file Livewire components         |
| **Tailwind CSS**             | 4.x     | Utility-first CSS framework             |
| **Vite**                     | 8.x     | Frontend build tool                     |
| **Alpine.js**                | —       | Lightweight JS framework (via Livewire) |
| **Pusher JS / Laravel Echo** | —       | Client-side WebSocket support           |

### AI & Services

| Technology                     | Purpose                                                    |
| ------------------------------ | ---------------------------------------------------------- |
| **Google Gemini API**          | Text simplification, quiz generation, AI script generation |
| **Edge TTS**                   | Text-to-speech audio synthesis with 300+ voices            |
| **NotebookLM Integration**     | Podcast-style audio generation                             |
| **Cosine Similarity (PHP-ML)** | Plagiarism detection algorithm                             |
| **AI Detection Service**       | Multi-strategy AI content detection                        |

### Document Processing

| Technology            | Purpose                         |
| --------------------- | ------------------------------- |
| **smalot/pdfparser**  | PDF text extraction             |
| **PHPWord**           | DOCX / Word document processing |
| **DomPDF (barryvdh)** | PDF generation (quiz exports)   |

---

## 🏗️ System Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
│    Livewire Components + Flux UI + Tailwind CSS + Alpine.js      │
├──────────────────────────────────────────────────────────────────┤
│                      APPLICATION LAYER                           │
│                                                                  │
│  ┌─────────────┐  ┌──────────────┐  ┌─────────────────────────┐ │
│  │  Livewire   │  │   Filament   │  │    HTTP Controllers     │ │
│  │ Components  │  │ Admin Panel  │  │ (Quiz, Audio, Material) │ │
│  │  (36 pages) │  │ (8 resources)│  │                         │ │
│  └──────┬──────┘  └──────┬───────┘  └────────────┬────────────┘ │
│         │                │                        │              │
│  ┌──────▼────────────────▼────────────────────────▼────────────┐ │
│  │                    SERVICE LAYER                            │ │
│  │  ┌───────────────┐ ┌──────────────┐ ┌────────────────────┐ │ │
│  │  │ Text          │ │ AI Quiz      │ │ Audio Script       │ │ │
│  │  │ Simplification│ │ Generation   │ │ Generator          │ │ │
│  │  └───────────────┘ └──────────────┘ └────────────────────┘ │ │
│  │  ┌───────────────┐ ┌──────────────┐ ┌────────────────────┐ │ │
│  │  │ AI Detection  │ │ Edge TTS     │ │ NotebookLM         │ │ │
│  │  │ Service       │ │ Service      │ │ Service            │ │ │
│  │  └───────────────┘ └──────────────┘ └────────────────────┘ │ │
│  │  ┌───────────────┐ ┌──────────────┐                        │ │
│  │  │ Text          │ │ PDF          │                        │ │
│  │  │ Extraction    │ │ Generation   │                        │ │
│  │  └───────────────┘ └──────────────┘                        │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │                 BACKGROUND JOBS (Queue)                    │  │
│  │  CheckPlagiarism │ GenerateAudio │ GenerateNotebooklmAudio │  │
│  │                  │ ProcessLearningMaterial                  │  │
│  └────────────────────────────────────────────────────────────┘  │
├──────────────────────────────────────────────────────────────────┤
│                       DATA LAYER                                 │
│  ┌───────────┐  ┌────────────────┐  ┌──────────────────────┐    │
│  │  SQLite   │  │ Laravel Reverb │  │  File Storage        │    │
│  │  Database │  │ (WebSockets)   │  │  (Audio, Materials)  │    │
│  └───────────┘  └────────────────┘  └──────────────────────┘    │
└──────────────────────────────────────────────────────────────────┘
```

---

## 👥 User Roles & Access Control

### 🎒 Student Portal

| Module             | Features                                                                    |
| ------------------ | --------------------------------------------------------------------------- |
| **Dashboard**      | Overview of enrolled subjects, upcoming deadlines                           |
| **Course View**    | Access materials, announcements, and subject chat                           |
| **Assignments**    | View assignment details, upload submissions, track plagiarism status        |
| **Quizzes**        | Take timed quizzes with tab-switch detection, view results & scores         |
| **AI Study Tools** | Text simplification, AI quiz generator, audio generator, NotebookLM podcast |
| **Advisor**        | View assigned advisor, book appointments, track appointment status          |
| **Registration**   | Self-register as student, select programme                                  |

### 👨‍🏫 Instructor Portal

| Module                | Features                                                                                                    |
| --------------------- | ----------------------------------------------------------------------------------------------------------- |
| **Course Management** | View taught subjects, manage materials (CRUD with file/link support)                                        |
| **Assignments**       | Create assignments with deadlines, view submissions, check plagiarism reports                               |
| **Quiz Management**   | Create quizzes with MCQs, set time limits, open/close dates, max attempts, publish/unpublish, export to PDF |
| **Quiz Results**      | View per-student quiz attempt results and scores                                                            |
| **Advising**          | View advisees, approve/reject appointment requests with notes                                               |
| **Student Profile**   | View advisee academic profile, current & completed subjects                                                 |
| **Registration**      | Self-register as instructor, select department                                                              |

### 🛡️ Admin Panel (Filament)

| Resource          | Capabilities                                      |
| ----------------- | ------------------------------------------------- |
| **Users**         | Full CRUD, impersonation                          |
| **Students**      | Manage student records and assignments            |
| **Instructors**   | Manage instructor profiles                        |
| **Departments**   | Create and manage academic departments            |
| **Programmes**    | Define academic programmes under departments      |
| **Subjects**      | Assign subjects to instructors and programmes     |
| **Registrations** | Approve/manage student & instructor registrations |

---

## 🤖 AI-Powered Services

### 1. AI Text Simplification Service

> **File:** `app/Services/TextSimplificationService.php`

Powered by **Google Gemini API**, this service processes uploaded academic documents and produces:

- **Simplified Text** — Reformatted into clear, accessible Markdown with headings, bullet points, and highlighted key terms
- **Key Points** — 4–6 essential bullet point takeaways
- **Executive Summary** — 2-paragraph overview of core ideas
- **Readability Metrics** — Word count comparison, reading level assessment, and simplification percentage

### 2. AI Plagiarism & Content Detection

> **Files:** `app/Jobs/CheckPlagiarism.php`, `app/Services/AiDetectionService.php`

A dual-analysis pipeline that runs as a background queue job:

- **Cross-Submission Plagiarism** — Extracts text from PDF/DOCX submissions, computes **cosine similarity** (via TF-IDF vectorization from PHP-ML) against all other submissions for the same assignment
- **AI Content Detection** — Multi-strategy analysis to detect machine-generated text in student submissions
- **Results** — Similarity percentage, matched student names, AI probability score, and detailed analysis

### 3. AI Quiz Generator

> **Files:** `app/Livewire/AIQuizGeneratorPage.php`, `app/Services/AIQuizGenerationService.php`

Students can generate personalized practice quizzes from their uploaded learning materials:

- Upload a document or select from previously processed materials
- Specify the number of questions (1–20)
- AI generates MCQ questions with correct answers directly from the content
- Quizzes are saved and can be retaken for study purposes

### 4. AI Audio Generator (Text-to-Speech)

> **Files:** `app/Livewire/AudioGenrator.php`, `app/Services/EdgeTtsService.php`

A comprehensive text-to-speech system using **Microsoft Edge TTS**:

- **Source Modes** — Direct text input, file upload, or select from existing learning materials
- **Generation Modes** — Solo narration or AI-scripted podcast-style dialogue
- **Voice Customization** — 300+ voices across 40+ languages, gender selection, speech rate (0.5x–2x), pitch adjustment, and voice styles
- **AI Script Generation** — For podcast mode, AI generates a natural conversational script before synthesis
- **Background Processing** — Audio generation runs as a queue job with real-time status updates

### 5. NotebookLM Podcast Audio Generator

> **Files:** `app/Livewire/NotebooklmAudioGenerator.php`, `app/Services/NotebookLmService.php`

Generates **Google NotebookLM-style** conversational podcast audio:

- Produces natural two-host discussion-style audio from learning materials
- Supports text input, file upload, or existing material selection
- Audio streamed and downloadable with play/download tracking
- Background job processing with status monitoring

---

## 📦 Module Breakdown

### Academic Structure

```
Department
  └── Programme
        └── Subject (taught by Instructor)
              ├── Materials (files + links)
              ├── Assignments
              │     └── Submissions → Plagiarism Check + AI Detection
              ├── Course Quizzes (MCQ, timed, max attempts)
              │     └── Quiz Attempts (with tab-switch tracking)
              └── Course Discussion Chat (real-time)
```

### Student Learning Pipeline

```
Student
  ├── Enroll in Subjects (via SubjectTaking)
  ├── Complete Subjects (via SubjectTaken)
  ├── Upload Learning Materials
  │     ├── AI Simplification → Simplified PDF + Summary + Key Points
  │     ├── AI Quiz Generation → Practice Quizzes
  │     ├── AI Audio (TTS) → Solo / Podcast Audio
  │     └── NotebookLM Audio → Podcast Discussion
  ├── Submit Assignments → Auto Plagiarism + AI Detection
  ├── Take Course Quizzes → Auto-graded with results
  └── Advisor System → Book & manage appointments
```

---

## 🗄️ Database Schema

The system is powered by **62 database migrations** covering the following entity groups:

### Core Entities

| Table         | Description                                               |
| ------------- | --------------------------------------------------------- |
| `users`       | Base authentication table with 2FA support                |
| `admins`      | Admin role mapping                                        |
| `students`    | Student profiles with programme & advisor links           |
| `instructors` | Instructor profiles with department links                 |
| `departments` | Academic departments                                      |
| `programmes`  | Academic programmes under departments                     |
| `subjects`    | Course subjects with instructor and programme assignments |

### Course Content

| Table                    | Description                                    |
| ------------------------ | ---------------------------------------------- |
| `materials_for_subjects` | Per-subject uploaded materials (files + links) |
| `assignments`            | Assignment definitions with deadlines          |
| `assignment_submissions` | Student assignment submissions                 |
| `check_plagiarisms`      | Plagiarism analysis results                    |
| `ai_detections`          | AI content detection results                   |

### Quiz System

| Table                   | Description                                                |
| ----------------------- | ---------------------------------------------------------- |
| `course_quizzes`        | Instructor-created quizzes with time limits and scheduling |
| `course_quiz_questions` | Quiz questions (MCQ)                                       |
| `course_quiz_options`   | Answer options per question                                |
| `course_quiz_attempts`  | Student attempt records with tab-switch tracking           |

### AI Learning Tools

| Table                                                           | Description                                                 |
| --------------------------------------------------------------- | ----------------------------------------------------------- |
| `learning_materials`                                            | Student-uploaded materials with extracted & simplified text |
| `learning_material_summaries`                                   | AI-generated summaries and key points                       |
| `quizzes` / `quiz_questions` / `quiz_options` / `quiz_attempts` | AI-generated practice quizzes                               |
| `audio_generations`                                             | TTS audio records with voice settings and playback tracking |
| `notebooklm_generations`                                        | NotebookLM podcast audio records                            |

### Communication & Advising

| Table                       | Description                                            |
| --------------------------- | ------------------------------------------------------ |
| `subject_messages`          | Per-subject chat messages with pin support             |
| `advisings`                 | Advisor assignment records                             |
| `appointment_with_advisors` | Appointment bookings with approval/rejection workflows |
| `notifications`             | In-app notification records                            |

---

## ⚡ Real-Time Features

The application uses **Laravel Reverb** as a first-party WebSocket server, integrated with **Laravel Echo** and **Pusher JS** on the client side:

- **Course Discussion Chat** — Messages broadcast instantly to all participants in the subject channel (`subject.{id}`)
- **Live Status Updates** — Audio generation and plagiarism check status updates pushed in real-time
- **Notification System** — Toast-style notifications via Filament for actions across the platform

---

## 🔐 Security & Authentication

| Feature                 | Implementation                                                                                                |
| ----------------------- | ------------------------------------------------------------------------------------------------------------- |
| **Passkey Login**       | WebAuthn / FIDO2 via `@laravel/passkeys` — passwordless biometric authentication                              |
| **Two-Factor Auth**     | TOTP-based 2FA via `Laravel Fortify` with recovery codes                                                      |
| **Role-Based Guards**   | Route-level `abort(403)` checks ensuring students, instructors, and admins access only their authorized pages |
| **Admin Impersonation** | Admins can impersonate non-admin users via `lab404/laravel-impersonate` for support and debugging             |
| **Quiz Integrity**      | Tab-switch detection during quiz attempts — switches are logged and counted                                   |
| **CSRF Protection**     | Laravel's built-in CSRF token verification on all forms                                                       |
| **Password Hashing**    | Bcrypt hashing via Laravel's `hashed` cast                                                                    |

---

## ⚙️ Background Job Processing

Heavy processing tasks are offloaded to **Laravel Queue workers** to keep the UI responsive:

| Job                       | Description                                                                      |
| ------------------------- | -------------------------------------------------------------------------------- |
| `CheckPlagiarism`         | Extracts text from submissions, computes cosine similarity, runs AI detection    |
| `GenerateAudio`           | Generates TTS audio files via Edge TTS with customizable voice settings          |
| `GenerateNotebooklmAudio` | Produces NotebookLM podcast-style audio from learning materials                  |
| `ProcessLearningMaterial` | Extracts text from uploaded documents, runs AI simplification, generates summary |

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js & npm
- SQLite

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd LearningManagementSystem

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Build frontend assets
npm run build
```

### Running the Application

The project includes a convenient single-command development server that starts all services concurrently:

```bash
# Start all services (Web Server + Queue Worker + Reverb WebSocket + Vite)
composer run dev
```

This starts four concurrent processes:
| Process | Color | Description |
|---------|-------|-------------|
| 🔵 **server** | Blue | `php artisan serve` — Laravel development server |
| 🟣 **queue** | Purple | `php artisan queue:listen` — Background job processor |
| 🟠 **reverb** | Orange | `php artisan reverb:start` — WebSocket server |
| 🩷 **vite** | Pink | `npm run dev` — Frontend asset bundler with HMR |

### Create Admin User

```bash
php artisan make:filament-user
```

---

## 📊 Project Statistics

| Metric                       | Count                          |
| ---------------------------- | ------------------------------ |
| **Livewire Components**      | 36+                            |
| **Filament Admin Resources** | 8                              |
| **Database Migrations**      | 62                             |
| **Eloquent Models**          | 33                             |
| **Service Classes**          | 8                              |
| **Background Jobs**          | 4                              |
| **AI-Powered Features**      | 5                              |
| **User Roles**               | 3 (Student, Instructor, Admin) |

---

## 📄 License

This project is developed as a **Final Year Project (FYP)** for academic purposes.

---

<p align="center">
  <strong>Built with ❤️ using Laravel, Livewire, Filament, and AI</strong>
</p>
