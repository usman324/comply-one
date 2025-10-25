<div class="modal fade bd-example-modal-lg" id="editModel" tabindex="-{{ $record->id }}" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title">Update {{ $title }}</h4>
                <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body dark-modal">
                <form class="edit-questionnaire-form pt-0" id="edit-questionnaire">
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" value="{{ $record->title }}"
                                name="title" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm select2" name="category" required>
                                <option value="customer_feedback" @if ($record->category === 'customer_feedback') selected @endif>
                                    Customer Feedback</option>
                                <option value="employee_survey" @if ($record->category === 'employee_survey') selected @endif>Employee
                                    Survey</option>
                                <option value="market_research" @if ($record->category === 'market_research') selected @endif>Market
                                    Research</option>
                                <option value="event_feedback" @if ($record->category === 'event_feedback') selected @endif>Event
                                    Feedback</option>
                                <option value="product_feedback" @if ($record->category === 'product_feedback') selected @endif>Product
                                    Feedback</option>
                                <option value="other" @if ($record->category === 'other') selected @endif>Other</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control form-control-sm" name="description" rows="3">{{ $record->description }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-sm"
                                value="{{ $record->start_date ? $record->start_date->format('Y-m-d') : '' }}"
                                name="start_date" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control form-control-sm"
                                value="{{ $record->end_date ? $record->end_date->format('Y-m-d') : '' }}"
                                name="end_date" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control form-control-sm select2" name="status">
                                <option value="active" @if ($record->status === 'active') selected @endif>Active</option>
                                <option value="inactive" @if ($record->status === 'inactive') selected @endif>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <small>This questionnaire has <strong>{{ $record->questions->count() }}</strong> questions
                                    and <strong>{{ $record->responses->count() }}</strong> responses.</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-pill float-end btn-outline-danger btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button"
                        onclick="addFormData(event,'post','{{ $url . '/' . $record->id }}','{{ $url }}','edit-questionnaire')"
                        class="btn btn-primary btn-pill btn-sm float-end me-sm-3 me-1 data-submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('.select2').each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            dropdownParent: $this.parent()
        });
    });
</script>
