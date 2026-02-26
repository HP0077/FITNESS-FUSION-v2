-- ============================================================
-- Fitness Fusion — Database Schema
-- Engine:  InnoDB (transactional, FK support)
-- Charset: utf8mb4 (full Unicode — emojis, multilingual names)
-- ============================================================

CREATE DATABASE IF NOT EXISTS fitness_fusion_v2
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE fitness_fusion_v2;

-- ── Users ───────────────────────────────────────────────────
-- Core identity table. Email is the login credential.
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(255)  NOT NULL,
    password   VARCHAR(255)  NOT NULL,  -- bcrypt hash (60 chars), 255 for future algos
    age        TINYINT UNSIGNED  DEFAULT NULL,
    gender     ENUM('male', 'female', 'other') DEFAULT NULL,
    state      VARCHAR(100)  DEFAULT NULL,
    diet_type  ENUM('veg', 'nonveg') DEFAULT 'veg',
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Metrics (health snapshots) ──────────────────────────────
-- One row per measurement session. Never overwritten — append only.
-- This lets the dashboard show latest AND the history chart show trends.
CREATE TABLE IF NOT EXISTS metrics (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id    INT UNSIGNED  NOT NULL,
    height     DECIMAL(5,2)  NOT NULL COMMENT 'cm',
    weight     DECIMAL(5,2)  NOT NULL COMMENT 'kg',
    bmi        DECIMAL(4,1)  DEFAULT NULL,
    body_fat   DECIMAL(4,1)  DEFAULT NULL COMMENT 'percentage',
    calories   INT UNSIGNED  DEFAULT NULL COMMENT 'BMR kcal/day',
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_metrics_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_metrics_user_date (user_id, created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Weight History (lightweight tracker) ────────────────────
-- Users can log weight daily without a full metrics session.
-- Separate table keeps it cheap to query for the weight chart.
CREATE TABLE IF NOT EXISTS weight_history (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    weight      DECIMAL(5,2) NOT NULL COMMENT 'kg',
    recorded_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_weight_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_weight_user_date (user_id, recorded_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
