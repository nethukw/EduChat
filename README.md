# 🎓 EduChat

### Learn Together. Connect Better. Grow Smarter.

**EduChat** is a full-stack, peer-to-peer learning platform that helps students collaborate through online study sessions, discussion forums, and AI-assisted learning support.

**Project status:** Completed (2025)  
**Project type:** Group project  
**Role:** Group Leader

---

## 📖 About the Project

EduChat was developed to make collaborative learning more accessible and organized. Instead of relying on separate tools to coordinate study groups, participate in conversations, and ask questions, students can use one platform to support their learning activities.

The application combines session scheduling with Zoom integration, student discussion forums, and an AI chatbot for learning-related assistance.

## ✨ Key Features

### 📅 Study Session Scheduling
- Organize online study sessions with other students.
- Support virtual meetings through Zoom API integration.
- Make peer-to-peer learning easier to coordinate.

### 💬 Discussion Forums
- Participate in learning-focused discussions.
- Ask questions and exchange knowledge with peers.
- Encourage collaboration and community learning.

### 🤖 AI Learning Assistant
- Ask learning-related questions through an integrated AI chatbot.
- Receive additional support while studying.
- Access chatbot assistance alongside the platform's collaborative features.

### 🤝 Collaborative Learning
- Bring scheduling, discussion, and learning assistance together in one web application.
- Support student-to-student engagement and knowledge sharing.

## 🛠️ Technology Stack

| Component | Technology |
| --- | --- |
| Backend framework | Laravel |
| Programming language | PHP |
| Database | MySQL |
| Online meetings | Zoom API |
| Learning support | AI chatbot integration |
| Dependency management | Composer |

The specific frontend framework and AI model/provider are not documented here because they have not been verified against the source code.

## 🏗️ Application Overview

EduChat brings together three core learning activities:

1. **Plan:** Students organize study sessions and use Zoom integration for online meetings.
2. **Discuss:** Students participate in forums to exchange questions, ideas, and explanations.
3. **Learn:** Students use an AI chatbot for additional learning-related assistance.

The Laravel application manages server-side functionality, with MySQL used for application data.

## ⚙️ Getting Started

The instructions below follow a typical Laravel development setup. Depending on the project's implementation, additional configuration may be necessary.

### Prerequisites

Before starting, install:

- PHP compatible with the Laravel version used in the project
- Composer
- MySQL
- Node.js and npm, if the repository uses compiled frontend assets
- API credentials for integrations that require them

### 1. Clone the repository

```bash
git clone https://github.com/nethukw/educhat.git
cd educhat
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, replace `cp` with `copy`.

### 4. Set up MySQL

Create an empty MySQL database, then set the corresponding values in `.env`:

```dotenv
APP_NAME=EduChat
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=educhat
DB_USERNAME=root
DB_PASSWORD=
```

Use your actual local database credentials. Configure Zoom and AI API credentials according to the environment variables required by the project's source code.

### 5. Run database migrations

```bash
php artisan migrate
```

If the project includes required database seeders, you may also need:

```bash
php artisan db:seed
```

### 6. Set up frontend assets (if applicable)

If `package.json` contains a frontend build setup:

```bash
npm install
npm run dev
```

### 7. Run the development server

```bash
php artisan serve
```

Visit **http://127.0.0.1:8000** in your browser.

> **Note:** These are conventional Laravel setup commands, not a verified one-command installation procedure for this repository.

## 👩‍💻 My Role — Group Leader

**Nethmi Wijekoon** served as the group leader for the EduChat project, helping guide team collaboration, project planning, and the overall development process.

EduChat was developed as a group effort; individual implementation responsibilities are not specified here.

## 📚 Learning Outcomes

The project provided practical experience with:

- **Full-stack application development:** Working with a Laravel-based backend and MySQL database.
- **Third-party service integration:** Incorporating online meeting functionality through the Zoom API.
- **Collaborative software development:** Planning work, coordinating as a team, and bringing features together into one application.
- **Designing for users:** Bringing multiple student learning needs into a single platform.

## 🚀 Project Status

**Completed in 2025.** EduChat is presented as an academic group project and portfolio showcase. Public hosting and ongoing maintenance are not implied.

## 🔐 Security and Configuration

- Keep `.env` out of version control.
- Never commit database passwords, Zoom credentials, or AI service API keys.
- Configure service credentials separately for each environment.
- Use appropriate permissions and secure production settings when deploying a Laravel application.

## 🤝 Contributions and Feedback

Feedback and suggestions are welcome. You can open an issue or propose a pull request through the GitHub repository.

## 📬 Contact

**Nethmi Wijekoon**

- **GitHub:** [github.com/nethukw](https://github.com/nethukw)
- **LinkedIn:** [linkedin.com/in/nethukzz](https://www.linkedin.com/in/nethukzz)
- **Portfolio:** [nethmi-wijekoon-portfolio.netlify.app](https://nethmi-wijekoon-portfolio.netlify.app/)

---

<p align="center"><strong>EduChat — Learning is better together. 🎓</strong></p>
