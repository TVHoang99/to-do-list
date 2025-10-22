<?php
/**
 * @var object $user
 * @var array $errors
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Task - Todo App</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Quill Editor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .create-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .form-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
        }
        .form-section {
            padding: 2.5rem;
        }
        .form-section h5 {
            color: #495057;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }
        .priority-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid;
        }
        .priority-low { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .priority-medium { background: #fff3cd; color: #856404; border-color: #ffeaa7; }
        .priority-high { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .ql-editor {
            min-height: 150px;
            font-size: 1rem;
            line-height: 1.6;
        }
        .character-counter {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .submit-section {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 2rem;
        }
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary-modern {
            background: var(--primary-gradient);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
        .preview-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        .preview-description {
            color: #6c757d;
            line-height: 1.6;
        }
        .form-check-input[type="radio"] {
            margin-top: 12px;
        }
        @media (max-width: 768px) {
            .form-section { padding: 1.5rem !important; }
        }
    </style>
</head>
<body class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="create-header p-5 position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3 lh-1">
                        <i class="bi bi-plus-circle-fill me-3"></i>
                        Create New Task
                    </h1>
                    <p class="lead fs-4 opacity-90 mb-0">
                        Add a new task to your list and stay organized
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="d-flex align-items-center justify-content-lg-end flex-wrap gap-3">
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0">1</div>
                            <small class="opacity-75">Task Form</small>
                        </div>
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0 text-success">2</div>
                            <small class="opacity-75">Done ✓</small>
                        </div>
                    </div>
                </div>
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

        <!-- Create Form -->
        <form method="POST" action="<?= \Uri::create('task/create') ?>" id="taskForm" novalidate>
            <div class="form-modern">
                <!-- Basic Info Section -->
                <div class="form-section mb-5">
                    <h5>
                        <i class="bi bi-card-text me-2 text-primary"></i>
                        Basic Information
                    </h5>
                    
                    <div class="row g-4">
                        <!-- Title -->
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">
                                <i class="bi bi-type me-2 text-primary"></i>Task Title *
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                                   id="title" 
                                   name="title" 
                                   placeholder="Enter a clear and concise task title..."
                                   value="<?= \Input::post('title', '') ?>"
                                   maxlength="255"
                                   required>
                            <?php if (isset($errors['title'])): ?>
                                <div class="invalid-feedback"><?= $errors['title'][0] ?></div>
                            <?php endif; ?>
                            <div class="character-counter mt-1" id="titleCounter">0/255</div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-text-paragraph me-2 text-info"></i>Task Description
                            </label>
                            <div id="descriptionEditor"></div>
                            <input type="hidden" name="description" id="descriptionInput" value="<?= \Input::post('description', '') ?>">
                            <div class="character-counter mt-2" id="descCounter">0/1000</div>
                        </div>
                    </div>
                </div>

                <!-- Status & Priority Section -->
                <div class="form-section">
                    <h5>
                        <i class="bi bi-gear-fill me-2 text-warning"></i>
                        Task Settings
                    </h5>
                    
                    <div class="row g-4">
                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle2-on me-2 text-success"></i>Status
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusPending" value="0" 
                                           <?= \Input::post('status', 0) == 0 ? 'checked' : '' ?> required>
                                    <label class="form-check-label d-flex align-items-center" for="statusPending">
                                        <span class="priority-badge priority-low me-2">
                                            <i class="bi bi-hourglass-split"></i> Pending
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusProgress" value="1" 
                                           <?= \Input::post('status', 0) == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label d-flex align-items-center" for="statusProgress">
                                        <span class="priority-badge priority-medium me-2">
                                            <i class="bi bi-arrow-repeat"></i> In Progress
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusCompleted" value="2" 
                                           <?= \Input::post('status', 0) == 2 ? 'checked' : '' ?>>
                                    <label class="form-check-label d-flex align-items-center" for="statusCompleted">
                                        <span class="priority-badge priority-high me-2">
                                            <i class="bi bi-check-circle-fill"></i> Completed
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-star-fill me-2 text-warning"></i>Priority
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="priority" id="priorityLow" value="low" 
                                           <?= \Input::post('priority', 'medium') == 'low' ? 'checked' : '' ?> required>
                                    <label class="form-check-label priority-badge priority-low" for="priorityLow">
                                        <i class="bi bi-flag me-1"></i>Low
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="priority" id="priorityMedium" value="medium" 
                                           <?= \Input::post('priority', 'medium') == 'medium' ? 'checked' : '' ?>>
                                    <label class="form-check-label priority-badge priority-medium" for="priorityMedium">
                                        <i class="bi bi-flag-fill me-1"></i>Medium
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="priority" id="priorityHigh" value="high" 
                                           <?= \Input::post('priority', 'medium') == 'high' ? 'checked' : '' ?>>
                                    <label class="form-check-label priority-badge priority-high" for="priorityHigh">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>High
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="col-12">
                            <label for="due_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-2 text-info"></i>Due Date (Optional)
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="due_date" 
                                   name="due_date" 
                                   value="<?= \Input::post('due_date', '') ?>">
                            <div class="form-text">Set a due date to stay on track</div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Section -->
                <div class="form-section">
                    <h5>
                        <i class="bi bi-eye-fill me-2 text-success"></i>
                        Live Preview
                    </h5>
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm p-4" style="background: #f8f9fa; border-radius: 15px;">
                                <div class="preview-title" id="previewTitle">Task Title</div>
                                <div class="preview-description" id="previewDescription">Task description will appear here...</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100 p-4 text-center" style="background: #f8f9fa; border-radius: 15px;">
                                <h6 class="fw-bold text-muted mb-3">Task Summary</h6>
                                <div class="priority-badge priority-medium mb-3 d-inline-block w-100" id="previewPriority">
                                    <i class="bi bi-flag-fill me-1"></i>Medium
                                </div>
                                <div class="d-flex justify-content-between small text-muted" id="previewStatus">
                                    <span><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                </div>
                                <div class="mt-3 small text-muted" id="previewDueDate">
                                    No due date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-primary fs-5"></i>
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($user->username) ?></div>
                                <small class="text-muted">Task will be assigned to you</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?= \Uri::create('task') ?>" class="btn btn-outline-secondary btn-modern">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary-modern btn-modern" id="submitBtn">
                            <span class="btn-text">
                                <i class="bi bi-check-lg me-2"></i>Create Task
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Creating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Quill Editor JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize Quill Editor
        const quill = new Quill('#descriptionEditor', {
            theme: 'snow',
            placeholder: 'Enter task description (optional)...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [1, 2, 3, false] }]
                ]
            }
        });

        // Form elements
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('descriptionInput');
        const priorityInputs = document.querySelectorAll('input[name="priority"]');
        const statusInputs = document.querySelectorAll('input[name="status"]');
        const dueDateInput = document.getElementById('due_date');
        const form = document.getElementById('taskForm');
        const submitBtn = document.getElementById('submitBtn');

        // Character counters
        const titleCounter = document.getElementById('titleCounter');
        const descCounter = document.getElementById('descCounter');

        // Live preview elements
        const previewTitle = document.getElementById('previewTitle');
        const previewDescription = document.getElementById('previewDescription');
        const previewPriority = document.getElementById('previewPriority');
        const previewStatus = document.getElementById('previewStatus');
        const previewDueDate = document.getElementById('previewDueDate');

        // Update title counter and preview
        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            titleCounter.textContent = `${length}/255`;
            previewTitle.textContent = this.value || 'Task Title';
            titleCounter.className = length > 200 ? 'character-counter text-warning' : 'character-counter';
        });

        // Update description counter and preview
        quill.on('text-change', function() {
            const text = quill.getText();
            const length = text.length - 1; // -1 for newline
            descCounter.textContent = `${length}/1000`;
            descCounter.className = length > 900 ? 'character-counter text-warning' : 'character-counter';
            
            const html = quill.root.innerHTML;
            descriptionInput.value = html;
            
            previewDescription.innerHTML = html || '<em>Task description will appear here...</em>';
        });

        // Update priority preview
        priorityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const priority = this.value;
                const icon = priority === 'low' ? 'bi-flag' : 
                            priority === 'medium' ? 'bi-flag-fill' : 'bi-exclamation-triangle-fill';
                const badgeClass = `priority-badge priority-${priority}`;
                
                previewPriority.innerHTML = `<i class="${icon} me-1"></i>${this.nextElementSibling.textContent}`;
                previewPriority.className = `${badgeClass} d-inline-block w-100 mb-3`;
            });
        });

        // Update status preview
        statusInputs.forEach(input => {
            input.addEventListener('change', function() {
                const status = this.value;
                let icon, text;
                if (status == 0) { icon = 'bi-hourglass-split'; text = 'Pending'; }
                else if (status == 1) { icon = 'bi-arrow-repeat'; text = 'In Progress'; }
                else { icon = 'bi-check-circle-fill'; text = 'Completed'; }
                
                previewStatus.innerHTML = `<span><i class="${icon} me-1"></i>${text}</span>`;
            });
        });

        // Update due date preview
        dueDateInput.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                previewDueDate.textContent = `Due ${date.toLocaleDateString('en-US', { 
                    month: 'short', day: 'numeric', year: 'numeric' 
                })}`;
                previewDueDate.className = 'mt-3 small text-primary fw-semibold';
            } else {
                previewDueDate.textContent = 'No due date';
                previewDueDate.className = 'mt-3 small text-muted';
            }
        });

        // Form submission with loading
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        });

        // Initialize previews
        titleInput.dispatchEvent(new Event('input'));
        quill.on('text-change');
        priorityInputs[1].dispatchEvent(new Event('change')); // medium
        statusInputs[0].dispatchEvent(new Event('change')); // pending
    </script>
</body>
</html>
