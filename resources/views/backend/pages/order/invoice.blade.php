@extends('backend.layout.app')

@section('title', 'Invoice | ' . $order->invoice_no)

@section('content')
    <style>
        .invoice-preview-frame {
            width: 100%;
            min-height: 650px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f4f6f9;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            .print-area,
            .print-area * {
                visibility: visible !important;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row mb-3 no-print">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4>Invoice Details</h4>

                <div>
                    {{-- <a href="{{ route('admin.order.invoice.view', $order->id) }}" target="_blank" class="btn btn-primary">
                        <i class="fa-solid fa-eye"></i> View Invoice
                    </a>

                    <a href="{{ route('admin.order.invoice.pdf', $order->id) }}" class="btn btn-success">
                        <i class="fa-solid fa-download"></i> Download PDF
                    </a> --}}

                    <a href="{{ route('admin.order') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card no-print">
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    For perfect print/PDF layout, click <strong>View Invoice</strong>, then press <strong>Ctrl + P</strong>.
                </div>

                <iframe
                    src="{{ route('admin.order.invoice.view', $order->id) }}"
                    class="invoice-preview-frame">
                </iframe>
            </div>
        </div>
    </div>
@endsection