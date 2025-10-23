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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Font Segoe UI */
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand,
        .nav-link {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        /* Header */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.75rem 0;
        }

        .logo-text {
            font-size: 1.75rem;
            font-weight: 700;
            color: #333333 !important;
            letter-spacing: -0.5px;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            flex: 1;
        }

        .nav-link-custom {
            color: #666666 !important;
            font-weight: 500;
            padding: 0.75rem 1.5rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: #ff6b35 !important;
            background: rgba(255, 107, 53, 0.1);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }

        .welcome-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: black;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
        }

        .username {
            font-weight: 600;
            color: #333333;
            font-size: 0.95rem;
        }

        .logout-link {
            color: #999999 !important;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #ff6b35 !important;
        }

        /* Banner Section */
        .banner-section {
            background: black;
            position: relative;
            overflow: hidden;
            padding: 5rem 0;
            margin-top: 77px;
        }

        .banner-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1) translateY(0);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.1) translateY(-10px);
            }
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .banner-title {
            font-size: 3rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .banner-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 0;
            font-weight: 400;
        }

        .stats-overview {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-item {
            padding: 1rem 0;
        }

        .stat-number {
            font-size: 2rem;
        }

        .stat-label {
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Page Content */
        .page-content {
            background: #f8f9fa;
            min-height: calc(100vh - 80px - 380px);
            padding: 3rem 0;
        }

        /* Task Cards */
        .task-card-custom {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .task-card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .task-header-custom {
            background: black;
            color: white;
            padding: 1.75rem;
            min-height: 80px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .task-title-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            vertical-align: middle;
            min-width: 0;
        }

        .task-title {
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1rem;
            color: white;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .status-badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            align-self: flex-start;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .status-pending {
            background: #ffc107;
            color: black;
        }

        .status-progress {
            background: #0d6efd;
            color: #fff;
        }

        .status-completed {
            background: #28a745;
            color: #fff;
        }

        .shared-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', sans-serif !important;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #e55a2b 0%, #e6820d 100%);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .nav-menu {
                display: none;
            }

            .welcome-section {
                gap: 0.5rem;
            }

            .banner-title {
                font-size: 2.5rem;
            }

            .banner-subtitle {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .logo-text {
                font-size: 1.5rem;
            }

            .user-info {
                display: none;
            }

            .banner-section {
                padding: 4rem 0;
                text-align: center;
            }

            .banner-title {
                font-size: 2rem;
            }

            .banner-subtitle {
                font-size: 1rem;
            }

            .stats-overview {
                margin-top: 2rem;
            }
        }

        /* Pagination */
        .pagination-link {
            color: #666666 !important;
            font-weight: 500;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', sans-serif !important;
        }

        .pagination-link:hover {
            color: #ff6b35 !important;
            background: rgba(255, 107, 53, 0.1);
            border-color: #ff6b35;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%) !important;
            border-color: #ff6b35 !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .page-item.disabled .page-link {
            color: #999999 !important;
            background: #f8f9fa !important;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <!-- Logo (trái) -->
            <a class="navbar-brand logo-text" href="<?= \Uri::create('task') ?>">
                My Task
            </a>

            <!-- Menu trung tâm -->
            <div class="nav-menu mx-auto">
                <a class="nav-link-custom active" href="<?= \Uri::create('task') ?>">
                    <i class="bi bi-house-door me-1"></i>Home
                </a>
                <a class="nav-link-custom" href="<?= \Uri::create('task/shared') ?>">
                    <i class="bi bi-people me-1"></i>Friend
                </a>
            </div>

            <!-- Welcome + Logout (phải) -->
            <div class="welcome-section">
                <div class="user-avatar">
                    <?= strtoupper(substr($user->username, 0, 1)) ?>
                </div>
                <div class="user-info">
                    <div class="username">Welcome, <?= htmlspecialchars($user->username) ?></div>
                    <a href="<?= \Uri::create('auth/logout') ?>" class="logout-link">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <section class="banner-section">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Banner Content -->
                <div class="col-lg-8">
                    <div class="banner-content">
                        <h1 class="banner-title">
                            <i class="bi bi-check2-all me-3"></i>
                            Manage Your Tasks Efficiently
                        </h1>
                        <p class="banner-subtitle">
                            Stay organized, collaborate with friends, and get things done faster
                        </p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="<?= \Uri::create('task/create') ?>" class="btn btn-primary-custom btn-lg px-5">
                                <i class="bi bi-plus-circle me-2"></i>Create New Task
                            </a>
                            <a href="<?= \Uri::create('task/shared') ?>" class="btn btn-outline-light btn-lg px-4">
                                <i class="bi bi-people me-2"></i>View Shared Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="col-lg-4">
                    <div class="stats-overview">
                        <div class="row g-3">
                            <?php
                            $total = count($tasks ?? []);
                            $pending = $progress = $completed = 0;
                            if (!empty($tasks)) {
                                foreach ($tasks as $task) {
                                    if ($task['status'] == 0) $pending++;
                                    elseif ($task['status'] == 1) $progress++;
                                    else $completed++;
                                }
                            }
                            ?>
                            <div class="col-4">
                                <div class="stat-item text-center">
                                    <div class="stat-number h3 fw-bold text-white mb-1"><?= $total ?></div>
                                    <div class="stat-label text-white-50 small fw-semibold">Total</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item text-center">
                                    <div class="stat-number h3 fw-bold text-warning mb-1"><?= $pending ?></div>
                                    <div class="stat-label text-white-50 small fw-semibold">Pending</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item text-center">
                                    <div class="stat-number h3 fw-bold text-success mb-1"><?= $completed ?></div>
                                    <div class="stat-label text-white-50 small fw-semibold">Done</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="page-content">
        <div class="container">
            <!-- Flash Messages -->
            <?php if (\Session::get_flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= \Session::get_flash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Tasks Grid -->
            <?php if (empty($tasks)): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted mb-4"></i>
                            <h3 class="fw-bold text-muted mb-3">No tasks yet</h3>
                            <p class="lead text-muted mb-4">Get started by creating your first task!</p>
                            <a href="<?= \Uri::create('task/create') ?>" class="btn btn-primary-custom btn-lg px-5">
                                <i class="bi bi-plus-circle me-2"></i>Create First Task
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-4" id="tasksGrid">
                    <?php foreach ($tasks as $task): ?>
                        <div class="col-xl-4 col-lg-6 col-md-6 task-item" data-task-id="<?= $task['id'] ?>">
                            <!-- Task card HTML (giữ nguyên như cũ) -->
                            <div class="card task-card-custom h-100">
                                <div class="task-header-custom">
                                    <div class="d-flex w-100" style="gap: 1rem;">
                                        <div class="task-title-container flex-grow-1">
                                            <h5 class="task-title mb-0"><?= htmlspecialchars($task['title']) ?></h5>
                                        </div>
                                        <span class="status-badge-custom status-<?=
                                                                                $task['status'] == 0 ? 'pending' : ($task['status'] == 1 ? 'progress' : 'completed')
                                                                                ?>">
                                            <i class="bi bi-<?=
                                                            $task['status'] == 0 ? 'hourglass-split' : ($task['status'] == 1 ? 'arrow-repeat' : 'check-circle')
                                                            ?> me-1"></i>
                                            <?= $task['status'] == 0 ? 'Pending' : ($task['status'] == 1 ? 'In Progress' : 'Completed') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (!empty($task['description'])): ?>
                                        <p class="text-muted small mb-3 lh-sm">
                                            <?= substr(nl2br(htmlspecialchars($task['description'])), 0, 120) ?>...
                                        </p>
                                    <?php endif; ?>

                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                <?= strtoupper(substr($task['user']['username'], 0, 1)) ?>
                                            </div>
                                            <span class="fw-semibold"><?= htmlspecialchars($task['user']['username']) ?></span>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M j', strtotime($task['created_at'])) ?>
                                        </small>
                                    </div>

                                    <?php if (!empty($task['shared_users'])): ?>
                                        <div class="mb-3">
                                            <small class="fw-semibold text-muted d-block mb-1">Shared with:</small>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php foreach (array_slice($task['shared_users'], 0, 2) as $shared_user): ?>
                                                    <span class="shared-tag"><?= htmlspecialchars($shared_user['username']) ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($task['shared_users']) > 2): ?>
                                                    <span class="shared-tag">+<?= count($task['shared_users']) - 2 ?> more</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 px-4 pb-4">
                                    <div class="d-flex gap-2">
                                        <a href="<?= \Uri::create('task/view/' . $task['id']) ?>" class="btn btn-outline-primary btn-sm btn-custom flex-grow-1">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="<?= \Uri::create('task/edit/' . $task['id']) ?>" class="btn btn-outline-secondary btn-sm btn-custom">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm btn-custom"
                                            onclick="confirmDelete(<?= $task['id'] ?>, '<?= htmlspecialchars(addslashes($task['title'])) ?>')"
                                            title="Delete task">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ✅ PAGINATION -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <nav aria-label="Task pagination" class="mt-5">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <!-- Info -->
                            <div class="text-muted small">
                                Showing <strong><?= $pagination['from'] ?>-<?= $pagination['to'] ?></strong>
                                of <strong><?= $pagination['total_items'] ?></strong> tasks
                            </div>

                            <!-- Pagination -->
                            <ul class="pagination pagination-lg mb-0">
                                <!-- First Page -->
                                <li class="page-item <?= $pagination['current_page'] == 1 ? 'disabled' : '' ?>">
                                    <a class="page-link pagination-link" href="<?= \Uri::create('task?page=1') ?>" data-page="1">
                                        <i class="bi bi-chevron-bar-left"></i>
                                    </a>
                                </li>

                                <!-- Previous -->
                                <li class="page-item <?= $pagination['current_page'] == 1 ? 'disabled' : '' ?>">
                                    <a class="page-link pagination-link" href="<?= \Uri::create('task?page=' . ($pagination['current_page'] - 1)) ?>"
                                        data-page="<?= $pagination['current_page'] - 1 ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                <!-- Pages -->
                                <?php
                                $start_page = max(1, $pagination['current_page'] - 2);
                                $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                ?>
                                <?php if ($start_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link pagination-link" href="<?= \Uri::create('task?page=1') ?>" data-page="1">1</a>
                                    </li>
                                    <?php if ($start_page > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?= $pagination['current_page'] == $i ? 'active' : '' ?>">
                                        <a class="page-link pagination-link" href="<?= \Uri::create('task?page=' . $i) ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($end_page < $pagination['total_pages']): ?>
                                    <?php if ($end_page < $pagination['total_pages'] - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link pagination-link" href="<?= \Uri::create('task?page=' . $pagination['total_pages']) ?>" data-page="<?= $pagination['total_pages'] ?>"><?= $pagination['total_pages'] ?></a>
                                    </li>
                                <?php endif; ?>

                                <!-- Next -->
                                <li class="page-item <?= $pagination['current_page'] == $pagination['total_pages'] ? 'disabled' : '' ?>">
                                    <a class="page-link pagination-link" href="<?= \Uri::create('task?page=' . ($pagination['current_page'] + 1)) ?>"
                                        data-page="<?= $pagination['current_page'] + 1 ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>

                                <!-- Last Page -->
                                <li class="page-item <?= $pagination['current_page'] == $pagination['total_pages'] ? 'disabled' : '' ?>">
                                    <a class="page-link pagination-link" href="<?= \Uri::create('task?page=' . $pagination['total_pages']) ?>" data-page="<?= $pagination['total_pages'] ?>">
                                        <i class="bi bi-chevron-bar-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Delete Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="bi bi-trash3-fill display-1 text-danger opacity-20"></i>
                    </div>
                    <h6 id="deleteTaskTitle" class="fw-bold mb-3"></h6>
                    <p class="text-muted mb-0">
                        This action cannot be undone. Are you sure you want to delete this task?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-custom" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button"
                        class="btn btn-danger btn-custom"
                        id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="bi bi-trash3-fill me-2"></i>Delete Task
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        let currentTaskId = null;
        let deleteModal = null;
        let confirmDeleteBtn = null;

        // ✅ INIT ON DOM READY
        document.addEventListener('DOMContentLoaded', function() {
            // Cache elements
            deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                backdrop: 'static',
                keyboard: false
            });
            confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // ✅ ATTACH EVENT LISTENER NGAY LẬP TỨC
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', handleConfirmDelete);
            }
        });

        // ✅ CONFIRM DELETE FUNCTION
        function confirmDelete(taskId, taskTitle) {
            currentTaskId = taskId;
            document.getElementById('deleteTaskTitle').textContent = taskTitle;

            // Enable button
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.querySelector('.btn-loading').classList.add('d-none');
            confirmDeleteBtn.querySelector('.btn-text').classList.remove('d-none');

            deleteModal.show();
        }

        // ✅ HANDLE DELETE
        function handleConfirmDelete() {
            if (!currentTaskId) return;

            const btn = confirmDeleteBtn;
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');

            // Loading state ✅
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');

            // ✅ SỬA: Gọi delete_ajax thay vì delete
            fetch(`<?= \Uri::create('task/delete_ajax') ?>/${currentTaskId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json(); // ✅ JSON thay vì text()
                })
                .then(data => {
                    if (data.success) {
                        // Remove task card
                        const taskCard = document.querySelector(`[onclick*="${currentTaskId}"`).closest('.col-xl-4, .col-lg-6, .col-md-6');
                        if (taskCard) {
                            taskCard.style.transition = 'all 0.3s ease';
                            taskCard.style.opacity = '0';
                            taskCard.style.transform = 'translateX(-20px)';
                            setTimeout(() => {
                                taskCard.remove();
                                showToast(data.message, 'success');
                                deleteModal.hide();

                                // Update banner stats
                                updateBannerStats();
                            }, 300);
                        }
                    } else {
                        showToast(data.message || 'Failed to delete task', 'error');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showToast('Failed to delete task. Please try again.', 'error');
                })
        }

        // ✅ TOAST NOTIFICATION
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 320px;';
            toast.innerHTML = `
            <div class="d-flex">
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2 mt-1 flex-shrink-0"></i>
                <div>${message}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        `;
            document.body.appendChild(toast);

            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(toast);
                bsAlert.close();
            }, 4000);
        }

        // Pagination click handler
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('pagination-link')) {
                e.preventDefault();
                const page = e.target.dataset.page;
                if (page) {
                    window.location.href = `<?= \Uri::create('task') ?>?page=${page}`;
                }
            }
        });

        // ✅ AUTO HIDE FLASH ALERTS
        setTimeout(() => {
            document.querySelectorAll('.alert:not(.position-fixed)').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>