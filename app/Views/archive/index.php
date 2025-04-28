<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arsip Pengajuan Surat</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="archiveTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Surat</th>
                                    <th>Nama Pemohon</th>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#archiveTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('archive/getDataTable') ?>',
            type: 'POST'
        },
        columns: [
            { 
                data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nomor_surat' },
            { data: 'nama_pemohon' },
            { data: 'jenis_surat' },
            { data: 'tanggal_pengajuan' },
            { 
                data: 'status',
                render: function(data) {
                    let badge = 'secondary';
                    if (data === 'selesai') {
                        badge = 'success';
                    } else if (data === 'ditolak') {
                        badge = 'danger';
                    }
                    return '<span class="badge badge-' + badge + '">' + data + '</span>';
                }
            },
            { 
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        order: [[4, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});
</script>
<?= $this->endSection(); ?>
