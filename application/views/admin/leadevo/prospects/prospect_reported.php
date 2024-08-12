<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 tw-mb-6">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">
                    <?php echo _l('Prospects Report'); ?>
                </h4>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table data-default-order="" id="proposals" class="table dataTable no-footer" role="grid" aria-describedby="proposals_info">
                                <thead>
                                    <tr role="row">
                                        <th colspan="1">Proposal #</th>
                                        <th colspan="1">Subject</th>
                                        <th colspan="1">To</th>
                                        <th colspan="1">Total</th>
                                        <th colspan="1">Date</th>
                                        <th colspan="1">Open Till</th>
                                        <th colspan="1">Project</th>
                                        <th colspan="1">Tags</th>
                                        <th colspan="1">Date Created</th>
                                        <th colspan="1">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="has-row-options odd">
                                        <td><a href="http://localhost/perfex_crm/admin/proposals/list_proposals/1" onclick="init_proposal(1); return false;">PRO-000001</a>
                                            <div class="row-options"><a href="http://localhost/perfex_crm/proposal/1/72a218ab2b3723d22d60f80033107494" target="_blank">View</a> | <a href="http://localhost/perfex_crm/admin/proposals/proposal/1">Edit </a></div>
                                        </td>
                                        <td><a href="http://localhost/perfex_crm/admin/proposals/list_proposals/1" onclick="init_proposal(1); return false;">asdfsadf</a></td>
                                        <td><a href="http://localhost/perfex_crm/admin/clients/client/1" target="_blank" data-toggle="tooltip" data-title="Customer">test contact</a></td>
                                        <td>$1,000.00</td>
                                        <td>2024-07-16</td>
                                        <td>2024-07-23</td>
                                        <td><a href="http://localhost/perfex_crm/admin/projects/view/0" target="_blank"></a></td>
                                        <td></td>
                                        <td class="sorting_1">2024-07-16 14:31:45</td>
                                        <td><span class="label label-default  s-status proposal-status-6">Draft</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">  

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<?php init_tail(); ?>
<script>
    $('#proposals').DataTable({
        "search": true
    });
</script>
</body>

</html>