            <form
                class="todo-form"
                method="post"
                action="/todo/store"
            >

                <?php echo Form::csrf(); ?>

                <input
                    type="hidden"
                    name="group_id"
                    value="<?php echo (int) $group_id; ?>"
                >

                <div class="form-group">

                    <label class="form-label">
                        タイトル
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        name="title"
                        placeholder="タイトルを入力"
                    >

                </div>

                <div class="form-group">

                    <label class="form-label">
                        メモ
                    </label>

                    <textarea
                        class="form-control"
                        name="description"
                        placeholder="メモを入力"
                    ></textarea>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        優先度
                    </label>

                    <select
                        class="form-control"
                        name="priority"
                    >

                        <?php foreach (Config::get('todo.priority_labels', []) as $value => $label): ?>

                            <option
                                value="<?php echo (int) $value; ?>"
                                <?php if ((int) $value === 2) echo ' selected'; ?>
                            >
                                <?php echo e($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        ステータス
                    </label>

                    <select
                        class="form-control"
                        name="status"
                    >

                        <?php foreach (Config::get('todo.status_labels', []) as $value => $label): ?>

                            <option value="<?php echo (int) $value; ?>">
                                <?php echo e($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        期限
                    </label>

                    <input
                        class="form-control"
                        type="date"
                        name="due_date"
                    >

                </div>

                <div class="todo-form__actions">

                    <button
                        class="btn btn--primary"
                        type="submit"
                    >
                        作成する
                    </button>

                </div>

            </form>


