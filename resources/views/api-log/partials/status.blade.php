@if($oApiLog->pending)
  <span class="badge badge-warning">{{ $oApiLog->status->name }}</span>
@elseif($oApiLog->confirmed)
  <span class="badge badge-success">{{ $oApiLog->status->name }}</span>
@endif