<?php global $post;?>

<div>
  @if ($id == '')
  <div class="row">
    <div class="col-12">
      <div class="card-box">
        <div class="row">
          <div class="col-lg-8 pl-0">
            <button
              class="btn btn-md btn-primary"
              data-toggle="modal"
              data-target="#registerNewMember"
            >
              <i class="mdi mdi-plus-circle mr-2"></i> Register Member
            </button>
          </div>
          <div class="col-lg-4">
            <div class="text-lg-right mt-3 mt-lg-0">
              <button
                type="button"
                class="btn btn-info mb-2 mr-1 download-excel"
              >
                Export Excel
              </button>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="table-responsive" id="grants-list">
            <table class="grants-list" id="list-grants-tb">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Names</th>
                  <th>Joining Date</th>
                  <th>Contact</th>
                  <th>Residence</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($members as $key => $member)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $member["member_name"] }}</td>
                  <td>{{ $member["joining_date"] }}</td>
                  <td>{{ $member["telephone"] }}</td>
                  <td>{{ $member["residence"] }}</td>
                  <td>
                    <a
                      href="javascript:void(0);"
                      class="btn-edit-member"
                      data-gdata="<?php echo htmlentities(json_encode($member), ENT_QUOTES); ?> "
                    >
                      <i class="mdi mdi-square-edit-outline"></i>
                    </a>
                    <a
                      href="javascript:void(0);"
                      class="action-icon btn-delete-grant"
                      data-id="<?php echo $member['id']; ?>"
                    >
                      <i class="mdi mdi-delete btn-delete-grant"></i
                    ></a>
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
  <!-- prettier-ignore -->
  @endif
  <!-- prettier-ignore -->
  @if ($id != '') include 'member-details'; @endif

  <div
    class="modal fade"
    id="registerNewMember"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="exampleModalLongTitle">
            NJHM New Member Registration
          </h2>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="new-member-form">
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="memberName">Person's Names:</label>
                <input
                  type="text"
                  class="form-control"
                  id="memberName"
                  name="memberName"
                  required
                />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="funder">Date of Joining: </label>
                <input
                  type="date"
                  class="form-control"
                  id="date_of_joining"
                  name="joining_date"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="grant_id">Telephone(Contact):</label>
                <input
                  type="number"
                  class="form-control"
                  id="telephone"
                  name="telephone"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="grant_name">Residence:</label>
                <input
                  type="text"
                  class="form-control"
                  id="residence"
                  name="residence"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-6">
                <label for="baptised">Baptised?</label>
                <select
                  class="form-control"
                  id="baptised"
                  name="baptised"
                  required
                >
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
              <div class="form-group col-sm-6">
                <label for="principal">Church of Baptism:</label>
                <input
                  type="text"
                  class="form-control"
                  id="baptismChurch"
                  name="baptismChurch"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="marital_status">Marital Status:</label>
                <select
                  class="form-control"
                  id="marital_status"
                  name="marital_status"
                  required
                >
                  <option value="single">Single</option>
                  <option value="married">Married</option>
                </select>
              </div>
            </div>

            <input
              name="user_id"
              type="hidden"
              value="<?php echo get_current_user_id(); ?>"
            />

            <?php wp_nonce_field('njhm_new_member_nonce', 'njhm_new_member_nonce_field'); ?>
            <input type="hidden" name="action" value="njhm_new_choir_member" />
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-danger dismiss-grant-creation"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-success save-member"
            data-count="1"
          >
            Submit
          </button>
        </div>
      </div>
    </div>
  </div>

  <div
    class="modal fade"
    id="edit-member-details"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">
            Update Member Details
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-update-member">
            <input id="member-id" name="member_id" type="hidden" value="" />
            <input
              name="user_id"
              type="hidden"
              value="<?= get_current_user_id(); ?>"
            />
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="update_member_name">Person's Names:</label>
                <input
                  type="text"
                  class="form-control"
                  id="update_member_name"
                  name="memberName"
                  required
                />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="update_date_joining">Date of Joining: </label>
                <input
                  type="date"
                  class="form-control"
                  id="update_date_joining"
                  name="joining_date"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="update_telephone">Telephone(Contact):</label>
                <input
                  type="number"
                  class="form-control"
                  id="update_telephone"
                  name="telephone"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="update_residence">Residence:</label>
                <input
                  type="text"
                  class="form-control"
                  id="update_residence"
                  name="residence"
                  required
                />
              </div>
            </div>

            <?php wp_nonce_field('njhm_update_member_nonce', 'njhm_update_member_nonce_field'); ?>
            <input type="hidden" name="action" value="update_member_data" />
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-danger dismiss-grant-creation"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-success update-member-data"
            data-count="1"
          >
            Update Details
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Posts -->
  <div
    class="modal fade"
    id="deleteGrantModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">
            Confirm Deletion
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete Member...?</p>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary dismiss-grant-creation"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            type="button"
            class="btn btn-success delete-grant"
            data-count="1"
          >
            Confirm Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
