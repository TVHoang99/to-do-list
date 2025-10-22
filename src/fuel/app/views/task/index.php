<?php

/**
 * @var object $user
 * @var array $tasks
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks - Todo App</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .task-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 1rem;
            overflow: hidden;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .task-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 50px;
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

        .meta-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            font-size: 0.9rem;
        }

        .meta-icon {
            width: 20px;
            margin-right: 0.75rem;
            color: #6c757d;
        }

        .shared-user-tag {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-state {
            background: white;
            border-radius: 1rem;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .empty-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .action-bar {
            margin-top: 3rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 mb-0">
                            <i class="bi bi-check2-square me-3"></i>
                            My Tasks
                        </h1>
                        <p class="mb-0 opacity-75">Manage your tasks efficiently</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="user-welcome">
                            <i class="bi bi-person-circle me-2"></i>
                            Welcome, <strong><?= htmlspecialchars($user->username) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Flash Messages -->
            <?php if (\Session::get_flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= \Session::get_flash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (\Session::get_flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= \Session::get_flash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Stats Row -->
            <?php if (!empty($tasks)): ?>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="bi bi-list-task display-4 text-primary mb-2"></i>
                            <div class="h4 mb-0 fw-bold text-primary"><?= count($tasks) ?></div>
                            <div class="text-muted small">Total Tasks</div>
                        </div>
                    </div>
                    <?php
                    $pending = $progress = $completed = 0;
                    foreach ($tasks as $task) {
                        if ($task['status'] == 0) $pending++;
                        elseif ($task['status'] == 1) $progress++;
                        else $completed++;
                    }
                    ?>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="bi bi-clock display-4 text-warning mb-2"></i>
                            <div class="h4 mb-0 fw-bold text-warning"><?= $pending ?></div>
                            <div class="text-muted small">Pending</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="bi bi-arrow-repeat display-4 text-info mb-2"></i>
                            <div class="h4 mb-0 fw-bold text-info"><?= $progress ?></div>
                            <div class="text-muted small">In Progress</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="bi bi-check-lg display-4 text-success mb-2"></i>
                            <div class="h4 mb-0 fw-bold text-success"><?= $completed ?></div>
                            <div class="text-muted small">Completed</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tasks Content -->
            <?php if (empty($tasks)): ?>
                <!-- Empty State -->
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h3 class="h2 fw-bold text-muted mb-3">No tasks yet</h3>
                            <p class="lead text-muted mb-4">Get started by creating your first task!</p>
                            <a href="<?= \Uri::create('task/create') ?>" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-plus-circle me-2"></i>
                                Create First Task
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Tasks Grid -->
                <div class="row g-4">
                    <?php foreach ($tasks as $task): ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card task-card h-100">
                                <!-- Task Header -->
                                <div class="task-header">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-bold"><?= htmlspecialchars($task['title']) ?></h5>
                                            <span class="status-badge status-<?=
                                                                                $task['status'] == 0 ? 'pending' : ($task['status'] == 1 ? 'progress' : 'completed')
                                                                                ?>">
                                                <i class="bi bi-<?=
                                                                $task['status'] == 0 ? 'hourglass-split' : ($task['status'] == 1 ? 'arrow-repeat' : 'check-circle')
                                                                ?> me-1"></i>
                                                <?= $task['status'] == 0 ? 'Pending' : ($task['status'] == 1 ? 'In Progress' : 'Completed') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Task Body -->
                                <div class="card-body py-3">
                                    <?php if (!empty($task['description'])): ?>
                                        <p class="card-text text-muted small mb-3">
                                            <?= nl2br(htmlspecialchars($task['description'])) ?>
                                        </p>
                                    <?php endif; ?>

                                    <!-- Meta Info -->
                                    <div class="mb-3">
                                        <div class="meta-item">
                                            <i class="bi bi-person meta-icon"></i>
                                            <span class="fw-semibold">Owner:</span>
                                            <span class="ms-2"><?= htmlspecialchars($task['user']['username']) ?></span>
                                            <?php if (!empty($task['user']['email'])): ?>
                                                <small class="text-muted ms-1">(<?= htmlspecialchars($task['user']['email']) ?>)</small>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($task['shared_users'])): ?>
                                            <div class="meta-item">
                                                <i class="bi bi-people meta-icon"></i>
                                                <span class="fw-semibold">Shared with:</span>
                                                <div class="d-flex flex-wrap mt-2">
                                                    <?php foreach ($task['shared_users'] as $shared_user): ?>
                                                        <span class="shared-user-tag">
                                                            <?= htmlspecialchars($shared_user['username']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="meta-item">
                                            <i class="bi bi-calendar3 meta-icon"></i>
                                            <span class="fw-semibold">Created:</span>
                                            <span class="ms-2"><?= date('M j, Y \a\t g:i A', strtotime($task['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Task Actions -->
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-flex gap-2">
                                        <a href="<?= \Uri::create('task/view/' . $task['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="<?= \Uri::create('task/edit/' . $task['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <a href="<?= \Uri::create('task/share/' . $task['id']) ?>" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-share me-1"></i>Share
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Action Bar -->
            <div class="action-bar text-center">
                <a href="<?= \Uri::create('task/create') ?>" class="btn btn-success btn-lg px-5">
                    <i class="bi bi-plus-circle me-2"></i>
                    Create New Task
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>