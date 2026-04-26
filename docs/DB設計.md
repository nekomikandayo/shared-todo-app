# DB設計

## テーブル一覧
- users
- groups
- group_users
- todos

---

## users

| カラム名 | 型 | 説明 |
|---------|----|------|
| id | string | ログインID |
| password | string | パスワード |
| created_at | datetime | 作成日時 |
| updated_at | datetime | 更新日時 |

---

## groups

| カラム名 | 型 | 説明 |
|---------|----|------|
| id | int | 主キー |
| name | string | グループ名 |
| invite_token | string | 招待用トークン |
| token_expired | boolean | 使用済みフラグ |
| created_at | datetime | 作成日時 |
| updated_at | datetime | 更新日時 |

---

## group_users

| カラム名 | 型 | 説明 |
|---------|----|------|
| id | int | 主キー |
| user_id | string | ユーザーID |
| group_id | int | グループID |

---

## todos

| カラム名 | 型 | 説明 |
|---------|----|------|
| id | int | 主キー |
| title | string | タイトル |
| description | text | 詳細 |
| due_date | date | 期日（任意） |
| priority | int | 1:低 2:中 3:高 |
| status | int | 0:未完了 1:完了 |
| is_deletable_by_all | boolean | 全員削除可 |
| user_id | string | 作成者 |
| group_id | int | グループID |
| created_at | datetime | 作成日時 |
| updated_at | datetime | 更新日時 |

---

## リレーション

- users ⇄ groups → 多対多（group_users）
- groups → todos → 1対多
- users → todos → 1対多