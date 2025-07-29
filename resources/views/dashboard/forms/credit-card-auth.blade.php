@extends('layouts.master')

@section('title')
    Credit Card Authorization - @lang('translation.business-center')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Credit Card Authorization Form</h4>
                </div>
                <div class="card-body">
                    <form id="ccAuthForm" method="POST" action="{{ route('forms.credit-card-auth.store') }}">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project['project_id'] }}">

                        <!-- Training Purpose -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_training" name="is_training">
                                    <label class="form-check-label" for="is_training">
                                        <strong>Is this form for training purposes?</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

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
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Customer Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Name as appears on card</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Customer Phone</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Customer Email</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_email" class="form-label">Confirm Customer Email</label>
                                    <input type="email" class="form-control" id="confirm_email" name="confirm_email" required>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Billing Address</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address_line1" class="form-label">Address Line 1</label>
                                    <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address_line2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control" id="address_line2" name="address_line2">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-select" id="state" name="state" required>
                                        <option value="">Select State</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <!-- Add all states -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="zip_code" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                           pattern="[0-9]{5}" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Credit Card Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="card_type" class="form-label">Card Type</label>
                                    <select class="form-select" id="card_type" name="card_type" required>
                                        <option value="">Select Card Type</option>
                                        <option value="visa">Visa</option>
                                        <option value="mastercard">Mastercard</option>
                                        <option value="amex">American Express</option>
                                        <option value="discover">Discover</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" 
                                           pattern="[0-9]{16}" maxlength="16" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                           pattern="[0-9]{2}/[0-9]{2}" placeholder="MM/YY" maxlength="5" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" 
                                           pattern="[0-9]{3,4}" maxlength="4" required>
                                </div>
                            </div>
                        </div>

                        <!-- Authorization -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">Authorization</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="authorization" name="authorization" required>
                                        <label class="form-check-label" for="authorization">
                                            I authorize the merchant to charge my credit card for the agreed upon purchase. 
                                            I understand that this authorization will remain in effect until I cancel it in writing.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Authorization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format expiry date input
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Format card number with spaces
    document.getElementById('card_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Email confirmation validation
    document.getElementById('ccAuthForm').addEventListener('submit', function(e) {
        const email = document.getElementById('customer_email').value;
        const confirmEmail = document.getElementById('confirm_email').value;
        
        if (email !== confirmEmail) {
            e.preventDefault();
            alert('Email addresses do not match');
        }
    });
</script>
@endpush

@endsection 