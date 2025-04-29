<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Edit News<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit News</h1>
        <a href="<?= base_url('news') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">News Information</h6>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('news/update/' . $news['id']) ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" 
                                   id="title" name="title" value="<?= old('title', $news['title']) ?>" required>
                            <?php if (session('errors.title')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.title') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= session('errors.content') ? 'is-invalid' : '' ?>" 
                                      id="content" name="content" rows="10" required><?= old('content', $news['content']) ?></textarea>
                            <?php if (session('errors.content')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.content') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control <?= session('errors.type') ? 'is-invalid' : '' ?>" 
                                    id="type" name="type" required>
                                <option value="">Select Type</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= $type ?>" <?= old('type', $news['type']) === $type ? 'selected' : '' ?>>
                                        <?= ucfirst($type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.type')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.type') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.category') ? 'is-invalid' : '' ?>" 
                                   id="category" name="category" value="<?= old('category', $news['category']) ?>" required>
                            <?php if (session('errors.category')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.category') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control <?= session('errors.status') ? 'is-invalid' : '' ?>" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status ?>" <?= old('status', $news['status']) === $status ? 'selected' : '' ?>>
                                        <?= ucfirst($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.status')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.status') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image" class="form-label">Image</label>
                            <?php if ($news['image']): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url($news['image']) ?>" alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control <?= session('errors.image') ? 'is-invalid' : '' ?>" 
                                   id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Max file size: 2MB. Allowed types: JPG, JPEG, PNG</small>
                            <?php if (session('errors.image')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.image') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm text-white-50"></i> Update News
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for the content textarea
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
<?= $this->endSection() ?> 