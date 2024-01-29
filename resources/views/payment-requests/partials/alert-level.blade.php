@if($paymentRequest->user->normal)
  <span class="badge badge-success">{{ $paymentRequest->user->alert_level->name }}</span>
@elseif($paymentRequest->user->alert)
  <span class="badge badge-warning">{{ $paymentRequest->user->alert_level->name }}</span>
@elseif($paymentRequest->user->blocked)
  <span class="badge badge-danger">{{ $paymentRequest->user->alert_level->name }}</span>
@endif