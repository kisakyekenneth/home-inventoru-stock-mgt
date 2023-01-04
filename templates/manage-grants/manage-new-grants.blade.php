<?php global $post;
$countryLists = get_countries_list();
$currencies = get_currencies(); ?>

<div>
    @if ($id == '')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-8 pl-0">
                        <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#captureNewSalesModal"> <i class="mdi mdi-plus-circle mr-2"></i> Add New</button>

                    </div>
                    <div class="col-lg-4">
                        <div class="text-lg-right mt-3 mt-lg-0">
                            <button type="button" class="btn btn-light mb-2 mr-1 download-excel">Export Excel</button>
                            <!-- <button type="button" class="btn btn-light mb-2 download-excel">Export CSV</button> -->
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="table-responsive" id="grants-list">
                        <table class="grants-list" id="list-grants-tb">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client Name</th>
                                    <th>Telephone</th>
                                    <th>Particular</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total Price</th>
                                    <th>Paid</th>
                                    <th> Balance</th>
                                    <th> Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $key => $sale)
                                <tr>
                                    <td>{{ $sale['id'] }}</td>
                                    <td> {{ $sale['client_name'] }}</td>
                                    <td> {{ $sale['client_telephone'] }}</td>
                                    <td> {{ $sale['particular'] }}</td>
                                    <td> {{ $sale['quantity'] }}</td>
                                    <td> {{ $sale['rate'] }}</td>
                                    <td>{{ $sale['amount_paid'] }} </td>
                                    <td></td>
                                    <td></td>
                                    <td> {{ $sale['date'] }}</td>

                                    <td>
                                        <a href="javascript:void(0);" class="btn-edit-grant" data-gdata="<?php echo htmlentities(json_encode($sale), ENT_QUOTES); ?> "> <i class="mdi mdi-square-edit-outline"></i> </a>
                                        <a href="javascript:void(0);" class="action-icon btn-delete-grant" data-id="<?php echo $sale['id']; ?>"> <i class="mdi mdi-delete btn-delete-grant"></i></a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($id != '')
    include 'manage-grant-details';
    @endif


    <div class="modal fade" id="captureNewSalesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Kisozi General Motor Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-new-grant">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_name">Client Name:</label>
                                <input type="text" class="form-control" id="client-name" name="client_name" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_id">Telephone:</label>
                                <input type="number" class="form-control" id="telephone" name="client_telephone" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_name">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>

                        </div>

                        <div class="row">

                            <div class="form-group col-sm-12">
                                <label for="principal">Particular:</label>
                                <textarea name="particular" id="particular"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="funder">Rate: </label>
                                <input type="number" class="form-control" id="rate" name="rate" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="fund_amount"> Amount Paid</label>
                                <input type="number" class="form-control" id="amount" name="amount_paid" required>
                            </div>

                        </div>

                        <input name="user_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                        <?php wp_nonce_field('maksph_new_grant_nonce', 'maksph_new_grant_nonce_field'); ?>
                        <input type="hidden" name="action" value="kisozi_new_sales" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger dismiss-grant-creation" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success save-grant" data-count="1">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-grants-details" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update sales Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-update-grant">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_name">Client Name:</label>
                                <input type="text" class="form-control" id="u-client-name" name="client_name" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_id">Telephone:</label>
                                <input type="number" class="form-control" id="u-telephone" name="client_telephone" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="grant_name">Quantity:</label>
                                <input type="number" class="form-control" id="u-quantity" name="quantity" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="principal">Particular:</label>
                                <textarea name="particular" id="u-particular"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="funder">Rate: </label>
                                <input type="number" class="form-control" id="u-rate" name="rate" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="fund_amount"> Amount Paid</label>
                                <input type="number" class="form-control" id="u-amount" name="amount_paid" required>
                            </div>

                        </div>

                        <input id="sales-id" name="grant_id" type="hidden" value="">
                        <input name="user_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                        <?php wp_nonce_field('maksph_update_grant_nonce', 'maksph_update_grant_nonce_field'); ?>
                        <input type="hidden" name="action" value="maksph_updates_grant" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger dismiss-grant-creation" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success update-grant" data-count="1">Update Record</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Posts -->
    <div class="modal fade" id="deleteGrantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"> Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete grant project...?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary dismiss-grant-creation" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary delete-grant" data-count="1">Confirm
                        Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>