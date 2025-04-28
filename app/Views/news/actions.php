<div class="btn-group" role="group">
    <a href="<?= base_url('news/view/' . $news['id']) ?>" class="btn btn-info btn-sm" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="<?= base_url('news/edit/' . $news['id']) ?>" class="btn btn-primary btn-sm" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <form action="<?= base_url('news/delete/' . $news['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this news?');">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div> 