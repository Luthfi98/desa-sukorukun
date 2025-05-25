<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Contact List</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Contact List
        </div>
        <div class="card-body">
            <table id="contactTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= esc($contact['name']) ?></td>
                        <td><?= esc($contact['email']) ?></td>
                        <td><?= esc($contact['subject']) ?></td>
                        <td>
                            <span class="badge bg-<?= $contact['status'] == 'read' ? 'success' : 'warning' ?>">
                                <?= ucfirst($contact['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($contact['created_at'])) ?></td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm view-contact" data-id="<?= $contact['id'] ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contact View Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Contact Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    <p id="modal-name"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    <p id="modal-email"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject:</label>
                    <p id="modal-subject"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Message:</label>
                    <p id="modal-message"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Date:</label>
                    <p id="modal-date"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#contactTable').DataTable();

    // Handle view contact button click
    $('.view-contact').click(function() {
        const id = $(this).data('id');
        
        // Show loading state
        $(this).prop('disabled', true);
        
        // Fetch contact details
        $.get(`<?= base_url('admin/contact/view/') ?>${id}`, function(response) {
            if (response.success) {
                const contact = response.data;
                
                // Update modal content
                $('#modal-name').text(contact.name);
                $('#modal-email').text(contact.email);
                $('#modal-subject').text(contact.subject);
                $('#modal-message').text(contact.message);
                $('#modal-date').text(new Date(contact.created_at).toLocaleString());
                
                // Show modal
                $('#contactModal').modal('show');
                
                // Update status badge in table
                const badge = $(`button[data-id="${id}"]`).closest('tr').find('.badge');
                badge.removeClass('bg-warning').addClass('bg-success');
                badge.text('Read');
            } else {
                alert('Failed to load contact details');
            }
        }).fail(function() {
            alert('Failed to load contact details');
        }).always(function() {
            // Re-enable button
            $(this).prop('disabled', false);
        });
    });
});
</script>
<?= $this->endSection() ?> 