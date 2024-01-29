@if($paymentRequest->unprocessed)
  <span class="badge badge-warning">{{ $paymentRequest->status->name }}</span>
@elseif($paymentRequest->auto)
  <span class="badge badge-success">{{ $paymentRequest->status->name }}</span>
@elseif($paymentRequest->manual)
  <span class="badge badge-success">{{ $paymentRequest->status->name }}</span>
@elseif($paymentRequest->failed)
  <span class="badge badge-danger">{{ $paymentRequest->status->name }}</span>
@endif