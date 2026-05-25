# Kizo Study Timer - Project Summary

## What Was Built

A complete **full-stack gamified study timer application** built with Laravel and Blade:
- **Unified Architecture**: Laravel 11+ backend and frontend (Blade) in a single repository.
- **Universal Design**: A gender-neutral color palette using Indigo, Teal, and Amber.
- **Gamification System**: XP, leveling, challenges, and achievements to motivate learners.
- **Real-time Tracking**: Pomodoro timer with automatic session recording and XP rewards.
- **Social Features**: Leaderboard and challenge invitations via email.
- **Localization**: Fully localized into **French** (interface, notifications, and labels).

---

## Frontend Components (Blade)

### Core Pages
1. **Welcome/Landing Page** (`resources/views/welcome.blade.php`)
   - Hero section with feature overview.
   - Dynamic stats cards for active learners.
2. **Dashboard** (`resources/views/dashboard.blade.php`)
   - XP progress visualization.
   - Interactive activity chart (Chart.js).
   - Global leaderboard.
3. **Study Timer** (Pomodoro-based tracking)
4. **Challenges & Achievements** (Enrolled and unlocked tracking)

### Authentication
- **Login** (`resources/views/auth/login.blade.php`)
- **Register** (`resources/views/auth/register.blade.php`)

---

## Backend Infrastructure

### Database Schema
1. **Users**: Profiles, levels, XP, and study statistics.
2. **Study Sessions**: Tracking duration, type, and XP earned.
3. **Challenges**: Definitions and user progress tracking.
4. **Achievements**: Badge definitions and unlocked status.
5. **Tasks**: Personal TODO list management.

### API Controllers
- `AuthController`: Secure registration and login.
- `SessionController`: Study session recording and XP calculation.
- `ChallengeController`: Challenge enrollment and progress.
- `AchievementController`: Badge unlocking and tracking.
- `UserController`: Profile management and leaderboard.
- `ChallengeInviteController`: Email-based invitations via Resend API.

---

## Key Features

### Gamification Mechanic
- **XP System**: Earn XP for every study minute (bonus for Pomodoro sessions).
- **Leveling**: Automatic level up every 1000 XP.
- **Streaks**: Daily study streak tracking to maintain momentum.

### Universal Aesthetic
- **Indigo & Teal**: Primary and secondary colors for a modern, professional feel.
- **Amber/Gold**: Highlight color for trophies, rewards, and achievements.
- **Glassmorphism**: Subtle backdrop filters and gradients for a premium experience.

---

## Technical Stack
- **Framework**: Laravel 11.x
- **Frontend**: Blade + Alpine.js + Tailwind CSS
- **Database**: PostgreSQL (Production) / SQLite (Local)
- **Email**: Resend API Integration
- **Icons**: Lucide React / Lucide SVG

---

**Status**: Production-ready, fully rebranded to **Kizo Study Timer** and localized in **French**. 🚀
