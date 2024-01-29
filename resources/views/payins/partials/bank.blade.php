@if($oPayIn->bank_account->mufg)
  <span class="badge badge-warning">{{ $oPayIn->bank_account->bank_info->name }}</span>
@elseif($oPayIn->bank_account->smbc)
  <span class="badge badge-success">{{ $oPayIn->bank_account->bank_info->name }}</span>
@elseif($oPayIn->bank_account->rakuten)
  <span class="badge badge-success">{{ $oPayIn->bank_account->bank_info->name }}</span>
@elseif($oPayIn->bank_account->mizuho)
  <span class="badge badge-danger">{{ $oPayIn->bank_account->bank_info->name }}</span>
@elseif($oPayIn->bank_account->paypay)
  <span class="badge badge-danger">{{ $oPayIn->bank_account->bank_info->name }}</span>
@endif