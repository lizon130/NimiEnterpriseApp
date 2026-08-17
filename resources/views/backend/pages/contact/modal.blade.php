<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createContactForm" action="{{ route('admin.contact.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                            <div class="step step_1 tab-pane fade show active">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Contact Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" placeholder="Contact Title" required>
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" >
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" >
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Toll Free</label>
                                        <input type="text" name="toll_free" class="form-control" placeholder="Toll Free" >
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Fax</label>
                                        <input type="text" name="fax" class="form-control" placeholder="Fax" >
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Address" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="" class="form-label">Google Map (Embed Map)</label>
                                        <textarea name="google_map" id="" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label mt-1">Visibility</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label mt-1">Make Default</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_default" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" data-bs-dismiss="modal" aria-label="Close" class="modal__btn_space next_btn" >Close</a>
                    <button type="submit" id="createContactBtn" class="btn btn-primary ">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
