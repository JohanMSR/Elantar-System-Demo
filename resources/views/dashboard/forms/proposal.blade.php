@extends('layouts.master')

@section('title')
    @lang('translation.proposal_form') - @lang('translation.business-center')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Proposal Form</h4>
                </div>
                <div class="card-body">
                    <form id="proposalForm" method="POST" action="{{ route('forms.proposal.store') }}">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project['project_id'] }}">

                        <!-- Project Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Project Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Project ID</label>
                                    <input type="text" class="form-control" value="{{ $project['project_id'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" class="form-control" value="{{ $project['client_name'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ $project['email'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" value="{{ $project['phone'] }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Address Information</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" value="{{ $project['address'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" value="{{ $project['city'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" value="{{ $project['state'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" value="{{ $project['zip_code'] }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Proposal Details -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Proposal Details</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="proposal_date" class="form-label">Proposal Date</label>
                                    <input type="date" class="form-control" id="proposal_date" name="proposal_date" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Proposal Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Terms and Conditions</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validity" class="form-label">Validity Period (days)</label>
                                    <input type="number" class="form-control" id="validity" name="validity" value="30" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="terms" class="form-label">Payment Terms</label>
                                    <select class="form-select" id="terms" name="terms" required>
                                        <option value="">Select Terms</option>
                                        <option value="immediate">Immediate</option>
                                        <option value="30days">30 Days</option>
                                        <option value="60days">60 Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Proposal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 