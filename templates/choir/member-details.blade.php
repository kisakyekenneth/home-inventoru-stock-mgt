<?php global $post;
$countryLists = get_countries_list();
$currencies = get_currencies(); ?>
<div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3> Grant Project Details</h3>
                </div>
            </div>
        </div>
        <div class="row card-body">
            <div class="col-12">
                @foreach ($grant_data as $key => $grant)
                <div>
                    <h5><span style="font-weight: bold;">Grant ID: </span> {{$grant['maksph_grant_id']}} </h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Grant Name: </span> {{$grant['grant_name']}} </h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Grant Description: </span> {{$grant['grant_description']}} </h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Principal Investigator: </span> {{$grant['principal']}}</h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Funder: </span> {{$grant['grant_funder']}}</h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Funding Amount: </span> {{$grant['grant_fund_amount']}}</h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Funding Currency: </span> {{$grant['grant_fund_currency']}}</h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Start Date: </span> {{$grant['grant_start_date']}} </h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">End Date: </span> {{$grant['grant_end_date']}} </h5>
                </div>
                <br>
                <div>
                    <h5><span style="font-weight: bold;">Department: </span> {{$grant['maksph_dept']}} </h5>
                </div>

                @endforeach

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3> SubContract Details</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 pl-0">
                    <button class="btn btn-md add-grant" data-toggle="modal" data-target="#subContractModal"> <i class="mdi mdi-plus-circle mr-2"></i> Add SubContract</button>

                </div>
            </div>
            <hr />

            <div class="row card-body">
                <div class="col-12">

                    <div class="row">
                        <div class="table-responsive" id="grants-list">
                            <table class="grants-list" id="list-sub-contract-tb">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Institution</th>
                                        <th>Principal</th>
                                        <th>Funder</th>
                                        <th>Amount</th>
                                        <th>Start date</th>
                                        <th>End date</th>
                                        <th>Country</th>
                                        <th>Currency</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subcontracts as $key => $subcontract)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td> {{$subcontract['institution']}}</td>
                                        <td> {{$subcontract['principal']}}</td>
                                        <td> {{$subcontract['_funder']}}</td>
                                        <td> {{$subcontract['_fund_amount']}}</td>
                                        <td><?php $_start_date = $subcontract['_start_date'];
                                            echo (new \DateTime($_start_date))->format("d-m-Y") ?> </td>
                                        <td><?php $_end_date = $subcontract['_end_date'];
                                            echo (new \DateTime($_end_date))->format("d-m-Y") ?> </td>

                                        <td> {{$subcontract['source_country']}}</td>
                                        <td> {{$subcontract['_fund_currency']}}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn-edit-subcontract" data-gdata="<?php echo htmlentities(json_encode($subcontract), ENT_QUOTES); ?> "> <i class="mdi mdi-square-edit-outline"></i> </a>
                                            <a href="javascript:void(0);" class="action-icon btn-delete-grant" data-id="<?php echo $subcontract['id']; ?>"> <i class="mdi mdi-delete btn-delete-grant"></i></a>

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
    </div>

    <div class="modal fade" id="subContractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Subcontract Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-grant-subcontract">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="institution">Institution:</label>
                                <input type="text" class="form-control institution" id="_institution" name="institution" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="principal">Principal Investigator:</label>
                                <input type="text" class="form-control" id="_principal" name="principal" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="fund_amount"> Funding amount</label>
                                <input type="number" class="form-control" id="grant_fund_amount" name="_fund_amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="currency">Currency</label>
                                <select name="_fund_currency" id="currency" class="form-control">
                                    <option value="">---------------------</option>
                                    <?php foreach ($currencies as $key => $currency) { ?>
                                        <option value="<?= $key ?>"> <?= $currency ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="country">Country</label>
                                <select name="source_country" class="form-control" id="country" required>
                                    <option value="">-- select one --</option>
                                    <?php foreach ($countryLists as $key => $countryList) { ?>
                                        <option value="<?= $key ?>"> <?= $countryList ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="datepicker">Issue Date:</label>
                                <input type="text" id="sub-issue-date" class="form-control issue-date-input" name="_issue_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="datepicker">Start Date:</label>
                                <input type="text" id="sub-start-date" class="form-control" name="_start_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="datepicker">End Date:</label>
                                <input type="text" class="form-control enddate-input" id="sub-end-date" name="_end_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="subcontracts">Subcontract agreement</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="subcontract_agreement" data-title="SubContract Agreement" id="_subcontract_agreement" class="file-uploads file-subcontract" accept=".doc,.docx,application/pdf,.jpeg,.jpg,.html,.png" />
                                </div>
                                <input type="hidden" name="_grant_id" id="_grant_id" value="<?php echo $_GET['id']; ?>">

                                <small><a href="#!" target="">Preview File</a> | Click on UPLOAD to replace existing. <strong class="error-report error"></strong></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="due_diligence">Due diligence assessment</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="due_diligence_assessment" data-title="Due Diligence Assessment" id="_due_diligence_assessment" class="file-uploads file-due-diligence" accept=".doc,.docx,application/pdf,.jpeg,.jpg,.html,.png" />
                                </div>
                                <small><a href="" target="">Preview File</a> | Click on UPLOAD to replace existing. <strong class="error-report error"></strong></small>
                            </div>
                        </div>
                        <input name="user_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                        <?php wp_nonce_field('maksph_subcontract_grant_nonce', 'maksph_subcontract_grant_nonce_field'); ?>
                        <input type="hidden" name="action" value="maksph_subcontract_grant" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary dismiss-grant-creation" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-sub-contract" data-count="1">Submit SubContract</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-subcontract-details" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update SubContract Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-update-subcontract">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="institution">Institution:</label>
                                <input type="text" class="form-control institution" id="institution" name="institution" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="principal">Principal Investigator:</label>
                                <input type="text" class="form-control" id="principal" name="principal" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="funder">Funder: </label>
                                <input type="text" class="form-control" id="funder" name="_funder" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="fund_amount"> Funding amount</label>
                                <input type="number" class="form-control" id="fund_amount" name="_fund_amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="currency">Currency</label>
                                <select name="_fund_currency" id="edit-currency" class="form-control">
                                    <option value="">---------------------</option>
                                    <?php foreach ($currencies as $key => $currency) { ?>
                                        <option value="<?= $key ?>"> <?= $currency ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="country">Country</label>
                                <select name="source_country" class="form-control" id="edit-country" required>
                                    <option value="">-- select one --</option>
                                    <?php foreach ($countryLists as $key => $countryList) { ?>
                                        <option value="<?= $key ?>"> <?= $countryList ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="datepicker">Issue Date:</label>
                                <input type="text" id="sub-edit-issue-date" class="form-control issue-date-input" name="_issue_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="datepicker">Start Date:</label>
                                <input type="text" id="sub-edit-start-date" class="form-control startdate-input" name="_start_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="datepicker">End Date:</label>
                                <input type="text" class="form-control enddate-input" id="sub-edit-end-date" name="_end_date" placeholder="dd-mm-yy" readonly="readonly" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="subcontracts">Subcontract agreement</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="subcontract_agreement" data-title="Subcontract Agreement" id="_subcontract_agreement" class="edit-file-uploads contact-file-upload" accept=".doc,.docx,application/pdf,.jpeg,.jpg,.html,.png" />
                                </div>
                                <input type="hidden" name="_grant_id" id="_grant_id" value="<?php echo $_GET['id']; ?>">

                                <small><a href="#!">Preview File</a> | Click on UPLOAD to replace existing. <strong class="error-report error"></strong></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="due_diligence">Due diligence assessment</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="due_diligence_assessment" data-title="Due Diligence Assessment" id="_due_diligence_assessment" class="edit-file-uploads diligence-file-upload" accept=".doc,.docx,application/pdf,.jpeg,.jpg,.html,.png" />
                                </div>
                                <small><a href="#!">Preview File</a> | Click on UPLOAD to replace existing. <strong class="error-report error"></strong></small>
                            </div>
                        </div>

                        <input id="subcontract-id" name="subcontract_id" type="hidden">
                        <input name="user_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                        <?php wp_nonce_field('maksph_update_subcontract_nonce', 'maksph_update_subcontract_nonce_field'); ?>
                        <input type="hidden" name="action" value="maksph_update_subcontracts" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary dismiss-grant-creation" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update-subcontract" data-count="1">Update Grant</button>
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
                    <button type="button" class="btn btn-primary delete-grant" data-count="1">Confirm Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>