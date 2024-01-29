@if($oUser->normal)
  <span class="badge badge-success">{{ $oUser->alert_level->name }}</span>
@elseif($oUser->alert)
  <span class="badge badge-warning">{{ $oUser->alert_level->name }}</span>
@elseif($oUser->blocked)
  <span class="badge badge-danger">{{ $oUser->alert_level->name }}</span>
@endif