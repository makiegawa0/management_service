@if($oPayIn->unprocessed)
  <span class="badge badge-warning">{{ $oPayIn->status->name }}</span>
@elseif($oPayIn->auto)
  <span class="badge badge-success">{{ $oPayIn->status->name }}</span>
@elseif($oPayIn->manual)
  <span class="badge badge-success">{{ $oPayIn->status->name }}</span>
@elseif($oPayIn->blocked)
  <span class="badge badge-danger">{{ $oPayIn->status->name }}</span>
@elseif($oPayIn->alert)
  <span class="badge badge-danger">{{ $oPayIn->status->name }}</span>
@elseif($oPayIn->closed)
  <span class="badge badge-dark">{{ $oPayIn->status->name }}</span>
@endif