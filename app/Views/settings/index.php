<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Settings Management</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="<?= base_url('admin/settings') ?>" method="get">
                                <div class="input-group">
                                    <select name="category" class="form-select">
                                        <option value="all" <?= $activeCategory == 'all' ? 'selected' : '' ?>>All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category ?>" <?= $activeCategory == $category ? 'selected' : '' ?>>
                                                <?= ucwords(str_replace('_', ' ', $category)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= base_url('admin/settings/create') ?>" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Add New Setting
                            </a>
                        </div>
                    </div>
                    
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Key</th>
                                    <th>Label</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Order</th>
                                    <th>Public</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($settings)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No settings found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($settings as $setting): ?>
                                        <tr>
                                            <td><?= $setting['id'] ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= ucwords(str_replace('_', ' ', $setting['category'])) ?>
                                                </span>
                                            </td>
                                            <td><?= $setting['key'] ?></td>
                                            <td><?= $setting['label'] ?></td>
                                            <td>
                                                <?php if ($setting['value_type'] == 'image' && !empty($setting['value'])): ?>
                                                    <img src="<?= base_url('uploads/' . $setting['value']) ?>" alt="<?= $setting['label'] ?>" height="50">
                                                <?php elseif ($setting['value_type'] == 'file' && !empty($setting['value'])): ?>
                                                    <a href="<?= base_url('uploads/' . $setting['value']) ?>" target="_blank">View File</a>
                                                <?php elseif (strlen($setting['value']) > 50): ?>
                                                    <?= substr($setting['value'], 0, 50) ?>...
                                                <?php else: ?>
                                                    <?= $setting['value'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= $setting['value_type'] ?></span></td>
                                            <td><?= $setting['order'] ?></td>
                                            <td>
                                                <?php if ($setting['is_public'] == 1): ?>
                                                    <span class="badge bg-success">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($setting['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/settings/edit/' . $setting['id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('admin/settings/delete/' . $setting['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this setting?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 