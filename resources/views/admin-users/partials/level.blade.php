@if($oAdminUser->admin)
  <span class="badge badge-danger">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->viewer)
  <span class="badge badge-light">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->operator)
  <span class="badge badge-warning">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->setter)
  <span class="badge badge-info">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->manager)
  <span class="badge badge-info">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->uploader)
  <span class="badge badge-info">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->accountant)
  <span class="badge badge-info">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->restrict)
  <span class="badge badge-success">{{ $oAdminUser->level->name }}</span>
@elseif($oAdminUser->root)
  <span class="badge badge-danger">{{ $oAdminUser->level->name }}</span>
@endif