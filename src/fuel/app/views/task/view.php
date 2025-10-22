<?php

/**
 * @var array $task
 * @var object $user
 * @var bool $is_owner
 * @var bool $is_shared
 * @var array $shareable_users
 * @var string $status_text
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($task['title']) ?> - Todo App</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bs-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .task-hero {
            background: var(--bs-gradient);
            color: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 2rem;
        }

        .task-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .status-badge {
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .status-pending {
            background: #ffc107;
            color: #fff;
        }

        .status-progress {
            background: #0d6efd;
            color: #fff;
        }

        .status-completed {
            background: #28a745;
            color: #fff;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--success-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .shared-user-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .shared-user-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .shareable-user {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 1rem;
        }

        .shareable-user:hover,
        .shareable-user.selected {
            border-color: #0d6efd;
            background: #e7f3ff;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
        }

        .card-modern {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <a href="<?= \Uri::create('task') ?>" class="btn btn-outline-secondary btn-modern">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Tasks
                </a>
            </div>

            <div class="text-center flex-grow-1">
                <h1 class="h2 fw-bold mb-1">
                    <i class="bi bi-eye3 me-2 text-primary"></i>
                    Task Details
                </h1>
                <small class="text-muted">Task #<?= $task['id'] ?></small>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <?php if ($is_owner): ?>
                    <a href="<?= \Uri::create('task/edit/' . $task['id']) ?>" class="btn btn-outline-primary btn-modern">
                        <i class="bi bi-pencil-square me-2"></i>Edit Task
                    </a>
                    <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#shareTaskModal">
                        <i class="bi bi-share-fill me-2"></i>Share Task
                    </button>
                <?php endif; ?>
                <?php if ($is_shared): ?>
                    <span class="badge bg-info text-dark fs-6 px-3 py-2">
                        <i class="bi bi-people me-1"></i>Shared with you
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (\Session::get_flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-start border-5 border-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= \Session::get_flash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (\Session::get_flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-start border-5 border-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= \Session::get_flash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Main Task Hero -->
        <div class="task-hero p-5 position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3 lh-1"><?= htmlspecialchars($task['title']) ?></h1>
                    <?php if (!empty($task['description'])): ?>
                        <p class="lead fs-4 opacity-90 mb-0"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="status-badge status-<?=
                                                    $task['status'] == 0 ? 'pending' : ($task['status'] == 1 ? 'progress' : 'completed')
                                                    ?> d-inline-block">
                        <i class="bi bi-<?=
                                        $task['status'] == 0 ? 'hourglass-split' : ($task['status'] == 1 ? 'arrow-repeat' : 'check-circle-fill')
                                        ?> me-2"></i>
                        <?= $status_text ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Task Info -->
            <div class="col-lg-8">
                <div class="card card-modern h-100">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-4">
                            <i class="bi bi-info-circle-fill me-3 text-primary"></i>
                            Task Information
                        </h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-4 bg-primary">
                                        <?= strtoupper(substr($task['user']['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($task['user']['username']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($task['user']['email']) ?></small>
                                        <?php if ($is_owner): ?>
                                            <br><span class="badge bg-success mt-1">Owner</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3-event-check-fill text-success me-4 fs-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Created</h6>
                                        <small class="text-muted"><?= date('M j, Y \a\t g:i A', strtotime($task['created_at'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shared Users -->
            <div class="col-lg-4">
                <div class="card card-modern h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-people-fill me-3 text-info"></i>
                            Shared With (<?= count($task['shared_users'] ?? []) ?>)
                            <?php if ($is_owner && !empty($shareable_users)): ?>
                                <button class="btn btn-sm btn-outline-success ms-auto d-inline-block" data-bs-toggle="modal" data-bs-target="#shareTaskModal">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </button>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($task['shared_users'])): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-person-lines-dash display-4 text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted mb-0">No users shared</h6>
                                <?php if ($is_owner): ?>
                                    <p class="text-muted small mt-2">Click "Add User" to share this task</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($task['shared_users'] as $shared_user): ?>
                                    <div class="list-group-item px-4 py-3 shared-user-item">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);">
                                                <?= strtoupper(substr($shared_user['username'], 0, 1)) ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold"><?= htmlspecialchars($shared_user['username']) ?></div>
                                                <small class="text-muted d-block"><?= htmlspecialchars($shared_user['email']) ?></small>
                                            </div>
                                            <?php if ($is_owner): ?>
                                                <button class="btn btn-sm btn-outline-danger" onclick="confirmRemove(<?= $shared_user['id'] ?>, <?= $task['id'] ?>)"
                                                    title="Remove sharing">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Task Modal -->
    <?php if ($is_owner && !empty($shareable_users)): ?>
        <div class="modal fade" id="shareTaskModal" tabindex="-1" aria-labelledby="shareTaskModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareTaskModalLabel">
                            <i class="bi bi-share-fill me-2 text-success"></i>
                            Share "<?= htmlspecialchars($task['title']) ?>"
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= \Uri::create('task/share/' . $task['id']) ?>" method="POST">
                        <div class="modal-body">
                            <p class="text-muted mb-4">Select users to share this task:</p>
                            <div class="row g-3">
                                <?php foreach ($shareable_users as $share_user): ?>
                                    <div class="col-md-6">
                                        <div class="shareable-user" onclick="toggleShareUser(this, 'user_<?= $share_user['id'] ?>')">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="user_ids[]" value="<?= $share_user['id'] ?>"
                                                        id="user_<?= $share_user['id'] ?>">
                                                </div>
                                                <div class="user-avatar me-3" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                                    <?= strtoupper(substr($share_user['username'], 0, 1)) ?>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold"><?= htmlspecialchars($share_user['username']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($share_user['email']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (empty($shareable_users)): ?>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    All users are already shared with this task.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" <?= empty($shareable_users) ? 'disabled' : '' ?>>
                                <i class="bi bi-check2-all me-2"></i>
                                Share Task
                            </button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle share user selection
        function toggleShareUser(element, checkboxId) {
            const checkbox = document.getElementById(checkboxId);
            checkbox.checked = !checkbox.checked;
            element.classList.toggle('selected', checkbox.checked);
        }

        // Confirm remove share
        function confirmRemove(userId, taskId) {
            if (confirm('Remove this user from task sharing? This action cannot be undone.')) {
                // Redirect to unshare action
                window.location.href = `<?= \Uri::create('task/unshare') ?>/${taskId}/${userId}`;
            }
        }

        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>