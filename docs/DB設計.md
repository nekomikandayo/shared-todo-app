# データベーススキーマ

## テーブル一覧

| テーブル名 | 説明 |
|---|---|
| users | ユーザー情報 |
| groups | グループ情報 |
| group_users | ユーザー・グループ中間テーブル |
| todos | ToDoタスク |

---

## users

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| id | int | PK, AUTO_INCREMENT | ユーザーID |
| username | varchar(255) | UNIQUE, NOT NULL | ログインID |
| password | varchar(255) | NOT NULL | ハッシュ化 |
| created_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 作成日時 |
| updated_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新日時 |

---

## groups

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| id | int | PK, AUTO_INCREMENT | グループID |
| name | varchar(255) | NOT NULL | グループ名 |
| invite_token | varchar(255) | UNIQUE, NOT NULL | 招待トークン |
| used_at | datetime | NULL | トークン使用日時 |
| expires_at | datetime | NULL | トークン有効期限 |
| created_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 作成日時 |
| updated_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新日時 |

---

## group_users

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| user_id | int | PK, FK → users.id | ユーザーID |
| group_id | int | PK, FK → groups.id | グループID |
| created_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 参加日時 |

> 複合主キー：(user_id, group_id)

---

## todos

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| id | int | PK, AUTO_INCREMENT | タスクID |
| title | varchar(255) | NOT NULL | タイトル |
| description | text | NULL | 詳細 |
| due_date | date | NULL | 期限 |
| priority | int(1) | NOT NULL, DEFAULT 2 | 1:低 / 2:中 / 3:高 |
| status | int(1) | NOT NULL, DEFAULT 0 | 0:未完了 / 1:完了 |
| created_by | int | NOT NULL, FK → users.id | 作成者 |
| group_id | int | NOT NULL, FK → groups.id | グループID |
| deleted_at | datetime | NULL | 論理削除 |
| created_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 作成日時 |
| updated_at | datetime | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新日時 |