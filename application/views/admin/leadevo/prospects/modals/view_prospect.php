<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewProspectModalLabel">Prospect Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" />
            <!-- Prospect data will be loaded here via AJAX -->
            <div id="prospectDetails">
                <p><strong>Full Name:</strong> <span id="prospectFullName"></span></p>
                <p><strong>Status:</strong> <span id="prospectStatus"></span></p>
                <p><strong>Type:</strong> <span id="prospectType"></span></p>
                <p><strong>Category:</strong> <span id="prospectCategory"></span></p>
                <p><strong>Acquisition Channel:</strong> <span id="prospectAcquisitionChannel"></span></p>
                <p><strong>Industry:</strong> <span id="prospectIndustry"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#viewProspectModal').on('shown.bs.modal', function () {

            let prospectId = $('#viewProspectModal input[name=id]').val();

            $.ajax({
                url: '<?= admin_url('prospects/get_prospect_data') ?>',
                type: 'GET',
                data: { id: prospectId },
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.status === 'error') {
                        alert(res.message);
                        return;
                    }

                    const data = res.prospect;

                    $('#prospectFullName').text(data.full_name || 'N/A');
                    $('#prospectStatus').text(data.status || 'Unknown');
                    $('#prospectType').text(data.type || 'Unknown');
                    $('#prospectCategory').text(data.category || 'Unknown');
                    $('#prospectAcquisitionChannel').text(data.acquisition_channel || 'Unknown');
                    $('#prospectIndustry').text(data.industry || 'Unknown');

                    // Show the modal
                    $('#viewProspectModal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Failed to fetch prospect data: ' + errorThrown);
                }
            });
        });
    });
</script>
