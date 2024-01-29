<?php

namespace App\Http\Controllers\PayIns;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayInsImportRequest;
use App\Models\BankAccount;
use App\Models\BankType;
use App\Models\IncomingLog;
use App\Repositories\PayInRepository;
use App\Services\PayIns\ImportPayInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class PayInsImportController extends Controller
{

    /** @var ImportPayInService */
    protected $payinService;
    /** @var PayInRepository */
    private $payinRepo;

    public function __construct(PayInRepository $payinRepo, ImportPayInService $payinService)
    {
        $this->payinService = $payinService;
        $this->payinRepo = $payinRepo;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $this->authorize('import', IncomingLog::class);

        $oBanks = BankAccount::where('is_active', 1)->get();
        // dd($oMtbBanks);
        // $oMtbBank = MtbBank::where('nickname', $bank_type)->first();

        // $oTransferAccount = $oMtbBank->transferAccounts->where('is_active', 1)->first();

        // return view('admin.incoming_log.lock')
        //     ->with(compact(
        //         'bLock',
        //         'bInvalid',
        //         'oBankCsvUploadLocks',
        //         'oAdminUserLogin',
        //         'oMtbBanks'
        //     ));

        return view('payins.import')
            ->with(compact(
                'oBanks'
            ));
    }

    /**
     * @throws Exception
     */
    public function store(Request $oRequest)
    {
        $this->authorize('import', IncomingLog::class);

        $aPayInData = $oRequest->session()->get('payin_to_store', []);
        // dd($aPayInData);
        if (!$aPayInData) {
            session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.not_exist_preview_data'));

            return redirect()->route('payins.index');
        }

        if ($this->payinService->storeInBatch($aPayInData)) {
            session()->flash('flashSuccess', __('flash.callback_request_log.csv_upload.success'));
        } else {
            session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.failed'));
        }

        return redirect()->route('payins.index');

        // $oBankCsvUploadLockQuery = BankCsvUploadLock::lock();

        // $lockedData = IncomingLog::lockForUpdate()->first();

        // if ($lockedData) {
        //     // Table is locked, do not proceed with the insert
        //     DB::rollback();
        //     return response()->json(['message' => 'Concurrent insert prevented. Try again.'], 409);
        // }

        // if ($oAdminUserLogin->isUploader()) {
        //     $oBankCsvUploadLockQuery->where('bank_csv_uplo   ad_locks.lock_admin_user_id', $oAdminUserLogin->id);
        // }
        // $oBankCsvUploadLockQuery->where('bank_csv_upload_locks.lock_admin_user_id', $oAdminUserLogin->id);
        // $oBankCsvUploadLock = $oBankCsvUploadLockQuery->find($aPreviewData['bank_csv_upload_lock_id']);

        // if (!$oBankCsvUploadLock) {
        //     // セッション削除
        //     $oRequests->session()->forget('bank_csv_upload_data');
        //     session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.not_exist_lock'));

        //     return redirect()->route('admin.incoming-log');
        // }

        // $aInsertData = $aPreviewData['data'];

        // $aRequestData = [
        //     'mc' => $oBankCsvUploadLock->mc,
        //     'data' => $aPreviewData['data'],
        //     'bank_type' => $oBankCsvUploadLock->bank_type,
        //     'send_type' => 'admin',
        // ];

        // try {
        //     // 送信処理
        //     $rCurl = curl_init();

        //     //URLとオプションを指定する
        //     curl_setopt($rCurl, CURLOPT_URL, route('callback.request'));
        //     curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($rCurl, CURLOPT_CUSTOMREQUEST, 'POST');
        //     curl_setopt($rCurl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        //     curl_setopt($rCurl, CURLOPT_POSTFIELDS, json_encode($aRequestData));
        //     $sResult = curl_exec($rCurl);
        //     curl_close($rCurl);

        //     // セッション削除
        //     $oRequests->session()->forget('bank_csv_upload_data');

        //     // コールバックの返し確認
        //     if (strpos($sResult, 'finish process') === false) {
        //         throw new \Exception(__('flash.callback_request_log.csv_upload.callback_not_finish'));
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Admin | previewPost error:'.$e->getMessage());
        //     session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.failed_registration').$e->getMessage());

        //     return redirect()->route('admin.incoming-log');
        // }

        // return redirect()->route('admin.incoming-log.csv-upload-complete', ['mc' => $oBankCsvUploadLock->mc, 'bank_type' => $oBankCsvUploadLock->bank_type]);

        // return view('admin.incoming_log.preview')
        //     ->with(compact(
        //         'oBankCsvUploadLock',
        //         '$aPayInData'
        //     ));
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function show(PayInsImportRequest $oRequest)
    {
        // if ($request->file('file')->isValid()) {
        //     $filename = Str::random(16) . '.csv';

        //     $path = $request->file('file')->storeAs('imports', $filename, 'local');

        //     $errors = $this->validateCsvContents(Storage::disk('local')->path($path));

        //     if (count($errors->getBags())) {
        //         Storage::disk('local')->delete($path);

        //         return redirect()->back()
        //             ->withInput()
        //             ->with('error', __('The provided file contains errors'))
        //             ->with('errors', $errors);
        //     }

        //     $counter = [
        //         'created' => 0,
        //         'updated' => 0
        //     ];

        //     (new FastExcel)->import(Storage::disk('local')->path($path), function (array $line) use ($request, &$counter) {
        //         $data = Arr::only($line, ['id', 'email', 'first_name', 'last_name']);

        //         $data['tags'] = $request->get('tags') ?? [];
        //         $subscriber = $this->subscriberService->import(Sendportal::currentWorkspaceId(), $data);

        //         if ($subscriber->wasRecentlyCreated) {
        //             $counter['created']++;
        //         } else {
        //             $counter['updated']++;
        //         }
        //     });

        //     Storage::disk('local')->delete($path);

        //     return redirect()->route('sendportal.subscribers.index')
        //         ->with('success', __('Imported :created subscriber(s) and updated :updated subscriber(s) out of :total', [
        //             'created' => $counter['created'],
        //             'updated' => $counter['updated'],
        //             'total' => $counter['created'] + $counter['updated']
        //         ]));
        // }

        // return redirect()->route('sendportal.subscribers.index')
        //     ->with('errors', __('The uploaded file is not valid'));

        $this->authorize('import', IncomingLog::class);

        // $oAdminUserLogin = AdminUser::find(Auth::user()->id);
        // $bRedirect = true;
        // if ($oAdminUserLogin->isAdmin()) {
        //     $bRedirect = false;
        // }
        // if ($bRedirect) {
        //     return redirect()->route('admin.incoming-log');
        // }

        // $oBankCsvUploadLockQuery = BankCsvUploadLock::lock();
        // if ($oAdminUserLogin->isUploader()) {
        //     $oBankCsvUploadLockQuery->where('bank_csv_upload_locks.lock_admin_user_id', $oAdminUserLogin->id);
        // }
        // $oBankCsvUploadLockQuery->where('bank_csv_upload_locks.lock_admin_user_id', $oAdminUserLogin->id);
        // $oBankCsvUploadLock = $oBankCsvUploadLockQuery->find($oRequests->bank_csv_upload_lock_id);

        // if (!$oBankCsvUploadLock) {
        //     session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.not_exist_lock'));

        //     return redirect()->route('admin.incoming-log');
        // }
        // dd(request()->file(), $oRequest, $oRequest->file('csv_file'));

        if ($oRequest->file('csv_file')->isValid()) {
            // 文字コード変換
            // $sCsvData = file_get_contents($oRequest->file('csv_file')->path());
            // $sCsvData = mb_convert_encoding($sCsvData, 'utf-8', 'sjis-win');

            $filename = Str::random(16) . '.csv';

            $path = $oRequest->file('csv_file')->storeAs('imports', $filename, 'local');

            // Convert the encoding using iconv
            $convertedCsvContents = mb_convert_encoding(Storage::disk('local')->get($path), 'utf-8', 'sjis-win');

            // Save the converted contents to the destination file
            Storage::disk('local')->put($path, $convertedCsvContents);

            // tmpに一時保存
            // $oTmpStorage = Storage::disk('tmp');
            // if (!$oTmpStorage->exists('csv')) {
            //     $oTmpStorage->makeDirectory('csv', 0775, true);
            // }

            // $sTmp = tempnam($oTmpStorage->path('csv'), 'CallbackRequestLog_');
            // $sTmpName = basename($sTmp);
            // $sPathName = 'csv/'.$sTmpName;
            // $oTmpStorage->put($sPathName, $sCsvData);

            // TODO: validation depends on bank type

            // $errors = $this->validateCsvContents(Storage::disk('local')->path($path));

            // if (count($errors->getBags())) {
            //     Storage::disk('local')->delete($path);

            //     return redirect()->back()
            //         ->withInput()
            //         ->with('error', __('The provided file contains errors'))
            //         ->with('errors', $errors);
            // }

            // csv読み込み
            // $oFile = new \SplFileObject($oTmpStorage->path($sPathName));
            // $oFile->setFlags(
            //     \SplFileObject::READ_CSV |
            //     \SplFileObject::READ_AHEAD |
            //     \SplFileObject::SKIP_EMPTY |
            //     \SplFileObject::DROP_NEW_LINE
            // );

            // $oMtbBank = MtbBank::where('mtb_banks.nickname', $oBankCsvUploadLock->bank_type)->first();
            // みずほの場合はTSV
            // if ($oMtbBank->id === config('const.BANK_ID_MIZUHO')) {
            //     $oFile->setCsvControl("\t");
            // }
            $delimiter = $oRequest->bank_id === BankType::MIZUHO ? "\t" : ',';

            // 各フォーマットでCSVからデータ抽出
            // $aRequestCsvData = [];

            // $sProcessMc = config(sprintf('bank.API_BANK_NAME.%s.%s.mc', $oBankCsvUploadLock->bank_type, $oBankCsvUploadLock->mc));
            // if ($oMtbBank->id === config('const.BANK_ID_RAKUTEN')) {
            //     $aRequestCsvData = $this->getRakuten($aCsvData, $sProcessMc);
            // } elseif ($oMtbBank->id === config('const.BANK_ID_MIZUHO')) {
            //     $aRequestCsvData = $this->getMizuho($aCsvData, $sProcessMc);
            // } elseif ($oMtbBank->id === config('const.BANK_ID_PAYPAY')) {
            //     $aRequestCsvData = $this->getPaypay($aCsvData, $sProcessMc);
            // } else {
            //     $aRequestCsvData = $this->getZengin($aCsvData, $sProcessMc);
            // }

            // $collection = (new FastExcel)->configureCsv($delimiter, '"', 'sjis-win')->import('file.csv');
            // configureCsv($delimiter = ',', $enclosure = '"', $encoding = 'UTF-8', $bom = false)
            $dateCounter = [];

            /* TODO: steps for each line of the file
                1. normliase the data - create fileds needed to store into DB such as deposit_manage_id
                2. validate the data - check for conflict, if metadata is different to exisiting data
                3. store the payin
            */

            // Read the file into an array of lines
            // $lines = file(Storage::disk('local')->path($path), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Reverse the array
            // $reversedLines = array_reverse($lines);

            $newPayins = [];
            $oBankAccount = BankAccount::where('bank_id', (int) $oRequest->bank_id)
                ->where('account_name', $oRequest->account_name)
                ->first();
            (new FastExcel())
                ->configureCsv($delimiter)
                ->import(Storage::disk('local')->path($path), function (array $line) use ($oBankAccount, &$dateCounter, &$newPayins) {
                    // dd($line, $dateCounter);
                    // $data = Arr::only($line, ['id', 'email', 'first_name', 'last_name']);
                    // $counter++;

                    // $data['tags'] = $oRequest->get('tags') ?? [];

                    if ($payin = $this->payinService->import($oBankAccount, $line, $dateCounter)) {
                        $newPayins[] = $payin;
                    }
                    // dd($newPayins, $dateCounter);
                    // if ($subscriber->wasRecentlyCreated) {
                //     $counter['created']++;
                    // } else {
                //     $counter['updated']++;
                    // }
                });

            Storage::disk('local')->delete($path);

            $newPayins = array_reverse($newPayins);
            // dd($newPayins);

            // $aCsvData = [];
            // foreach ($oFile as $aLine) {
            //     $aCsvData[] = $aLine;
            // }
            // $oFile = null;
            // // csvは削除
            // $oTmpStorage->delete($sPathName);

            // CallbackRequestLogを確認して新規で登録するデータのみ対象にする
            // $aInsertData = IncomingCommon::getInsertData($aRequestCsvData, $oBankCsvUploadLock->mc, $oBankCsvUploadLock->bank_type);
            // if ($aInsertData === false) {
            //     $aInsertData = [];
            // }

            $oRequest->session()->put('payin_to_store', $newPayins);
        } else {
            session()->flash('flashFailure', __('flash.callback_request_log.csv_upload.not_exist_csv_upload'));
        }

        // dd(request()->account_name);

        return view('payins.preview')->with(compact('newPayins'));
    }

    /**
     * @param string $path
     *
     * @return ViewErrorBag
     *
     * @throws IOException
     * @throws ReaderNotOpenedException
     * @throws UnsupportedTypeException
     */
    protected function validateCsvContents(string $path): ViewErrorBag
    {
        $errors = new ViewErrorBag();

        $row = 1;

        (new FastExcel())->import($path, function (array $line) use ($errors, &$row) {
            $data = Arr::only($line, ['id', 'email', 'first_name', 'last_name']);

            try {
                $this->validateData($data);
            } catch (ValidationException $e) {
                $errors->put('Row ' . $row, $e->validator->errors());
            }

            $row++;
        });

        return $errors;
    }

    /**
     * 全銀フォーマットで取得
     *
     * @return array
     */
    private function getZengin(array $aCsvData, string $sMc)
    {
        $aRequestCsvData = [];
        $iLastRemainder = 0;
        $iRemainder = 0;
        $iInAmount = 0;
        $iOutAmount = 0;
        $dateCount = [];

        // 取引後の残高取得
        foreach ($aCsvData as $aLine) {
            if ($aLine[0] === '8') {
                $iLastRemainder = intval($aLine[6]);
                $iInAmount = intval($aLine[2]);
                $iOutAmount = intval($aLine[4]);
                $iRemainder = $iLastRemainder - $iInAmount + $iOutAmount;
            }
        }

        // 日付、金額、残高、内容
        for ($i = 0; $i < count($aCsvData); $i++) {
            if ($aCsvData[$i][0] === '2') {
                $aTmp = [];
                // 全銀で送信しているdateは「勘定日」になっている
                // deposit_manage_idは振込された日付「預入・払出日」で作成する
                $sIncomingDate = Common::replaceDate($aCsvData[$i][3], true);
                if (isset($dateCount[$sIncomingDate])) {
                    $dateCount[$sIncomingDate]++;
                } else {
                    $dateCount[$sIncomingDate] = 1;
                }

                if ($aCsvData[$i][4] === '1') {
                    $aTmp['date'] = Common::replaceDate($aCsvData[$i][2], true);
                    $aTmp['process_code'] = Common::toHankaku($aCsvData[$i][14]);

                    if (! preg_match('/'.$sMc.'-/', $aTmp['process_code'])) {
                        $aTmp['process_code'] = '要チェック';
                    }

                    $aTmp['process_code_org'] = preg_replace('/"/', '', $aCsvData[$i][14]).'ﾌﾘｺﾐ';
                    $aTmp['amount'] = intval($aCsvData[$i][6]);

                    if (preg_match('/ /', $aTmp['process_code'])) {
                        $aTmp2 = explode(' ', $aTmp['process_code']);
                        foreach ($aTmp2 as $sValue) {
                            if (preg_match('/D'.$sMc.'-/', $sValue)) {
                                $aTmp['process_code'] = $sValue;
                                break;
                            }
                        }
                    }

                    if ($aTmp['process_code'] !== '要チェック') {
                        $aTmp['process_code'] = preg_replace('/[^A-Za-z0-9\-]/', '', $aTmp['process_code']);
                    }

                    // 残高
                    $iRemainder += $aTmp['amount'];
                    $aTmp['remainder'] = $iRemainder;

                    // ユニークID付与
                    $aTmp['deposit_manage_id'] = str_replace('/', '', $sIncomingDate).sprintf('%04d', $dateCount[$sIncomingDate]);

                    $aRequestCsvData[] = $aTmp;
                } elseif ($aCsvData[$i][4] === '2') {
                    $iAmount = intval($aCsvData[$i][6]);
                    $iRemainder -= $iAmount;
                }
            }
        }

        return array_reverse($aRequestCsvData);
    }

    /**
     * 楽天フォーマットで取得
     *
     * @return array
     */
    private function getRakuten(array $aCsvData, string $sMc)
    {
        $aRequestCsvData = [];
        $dateCount = [];

        // ヘッダー削除
        array_shift($aCsvData);

        // 日付、金額、残高、内容
        for ($i = 0; $i < count($aCsvData); $i++) {
            $aTmp = [];
            $aTmp['date'] = Common::replaceDate($aCsvData[$i][0]);
            if (isset($dateCount[$aTmp['date']])) {
                $dateCount[$aTmp['date']]++;
            } else {
                $dateCount[$aTmp['date']] = 1;
            }

            if ($aCsvData[$i][1] > 0) {
                $aTmp['process_code'] = Common::toHankaku($aCsvData[$i][3]);

                // if (!preg_match('/'.$sMc.'-/', $aTmp['process_code'])) {
                //     $aTmp['process_code'] = '要チェック';
                // }

                $aTmp['process_code_org'] = $aCsvData[$i][3];
                $aTmp['amount'] = intval($aCsvData[$i][1]);
                $aTmp['remainder'] = intval($aCsvData[$i][2]);
                if (preg_match('/ /', $aTmp['process_code'])) {
                    $aTmp2 = explode(' ', $aTmp['process_code']);
                    foreach ($aTmp2 as $sValue) {
                        if (preg_match('/D'.$sMc.'-/', $sValue)) {
                            $aTmp['process_code'] = $sValue;
                            break;
                        }
                    }
                }

                if ($aTmp['process_code'] !== '要チェック') {
                    $aTmp['process_code'] = preg_replace('/[^A-Za-z0-9]/', '', $aTmp['process_code']);
                }

                // ユニークID付与
                $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']).sprintf('%04d', $dateCount[$aTmp['date']]);
                // $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']).strval($deposit_manage_id);
                // $deposit_manage_id--;
                // $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']);
                // Log::info($aTmp);
                // for ($i = 1000; $i >= 1; $i--) {
                //     $aTmp['deposit_manage_id'] .= strval($i);
                //     Log::info($aTmp);
                // }
                // Log::info($aTmp);

                $aRequestCsvData[] = $aTmp;
            }
        }

        return array_reverse($aRequestCsvData);
    }

    /**
     * みずほフォーマットで取得
     *
     * @return array
     */
    private function getMizuho(array $aCsvData, string $sMc)
    {
        $aRequestCsvData = [];

        // ヘッダー削除 (4行)
        array_shift($aCsvData);
        array_shift($aCsvData);
        array_shift($aCsvData);
        array_shift($aCsvData);

        $iLastRemainder = 0;
        $iRemainder = 0;
        $iInAmount = 0;
        $iOutAmount = 0;
        for ($i = 0; $i < count($aCsvData); $i++) {
            if ($aCsvData[$i][0] === '合計' && strpos($aCsvData[$i][12], '残高') !== false) {
                $iLastRemainder = intval($aCsvData[$i][19]);
            } elseif ($aCsvData[$i][0] === '明細' && strpos($aCsvData[$i][12], '入金') !== false) {
                $iInAmount += intval($aCsvData[$i][19]);
            } elseif ($aCsvData[$i][0] === '明細' && strpos($aCsvData[$i][12], '出金') !== false) {
                $iOutAmount += intval($aCsvData[$i][19]);
            }
        }
        $iRemainder = $iLastRemainder - $iInAmount + $iOutAmount;

        // 日付、金額、残高、内容
        for ($i = 0; $i < count($aCsvData); $i++) {
            if ($aCsvData[$i][0] === '明細' && strpos($aCsvData[$i][12], '入金') !== false) {
                $aTmp = [];
                // 年明けに12月のデータが来たら去年で処理を行う
                if (now()->month === 1) {
                    if ($aCsvData[$i][15] === 12) {
                        $sDate = sprintf('%d%02d%02d', (now()->year) - 1, $aCsvData[$i][15], $aCsvData[$i][16]);
                    } else {
                        $sDate = sprintf('%d%02d%02d', now()->year, $aCsvData[$i][15], $aCsvData[$i][16]);
                    }
                } else {
                    $sDate = sprintf('%d%02d%02d', now()->year, $aCsvData[$i][15], $aCsvData[$i][16]);
                }
                $aTmp['date'] = Common::replaceDate($sDate);
                $aTmp['process_code'] = Common::toHankaku($aCsvData[$i][21]);

                if (! preg_match('/'.$sMc.'-/', $aTmp['process_code'])) {
                    $aTmp['process_code'] = '要チェック';
                }

                $aTmp['process_code_org'] = $aCsvData[$i][21];
                $aTmp['amount'] = intval($aCsvData[$i][19]);
                if (preg_match('/ /', $aTmp['process_code'])) {
                    $aTmp2 = explode(' ', $aTmp['process_code']);
                    foreach ($aTmp2 as $sValue) {
                        if (preg_match('/D'.$sMc.'-/', $sValue)) {
                            $aTmp['process_code'] = $sValue;
                            break;
                        }
                    }
                }

                if ($aTmp['process_code'] !== '要チェック') {
                    $aTmp['process_code'] = preg_replace('/[^A-Za-z0-9\-]/', '', $aTmp['process_code']);
                }

                $iRemainder += intval($aCsvData[$i][19]);
                $aTmp['remainder'] = $iRemainder;

                $aTmp['deposit_manage_id'] = $sDate.$aCsvData[$i][13];

                $aRequestCsvData[] = $aTmp;
            } elseif ($aCsvData[$i][0] === '明細' && strpos($aCsvData[$i][12], '出金') !== false) {
                $iRemainder -= intval($aCsvData[$i][19]);
            }
        }

        return array_reverse($aRequestCsvData);
    }

    /**
     * paypayフォーマットで取得
     *
     * @return array
     */
    private function getPaypay(array $aCsvData, string $sMc)
    {
        $aRequestCsvData = [];
        $dateCount = [];

        // ヘッダー削除
        array_shift($aCsvData);

        // 日付、金額、残高、内容
        for ($i = 0; $i < count($aCsvData); $i++) {
            $aTmp = [];
            $sDate = sprintf('%d%02d%02d', $aCsvData[$i][0], $aCsvData[$i][1], $aCsvData[$i][2]);
            $aTmp['date'] = Common::replaceDate($sDate);
            if (isset($dateCount[$aTmp['date']])) {
                $dateCount[$aTmp['date']]++;
            } else {
                $dateCount[$aTmp['date']] = 1;
            }

            if ($aCsvData[$i][9] > 0 && (strpos($aCsvData[$i][7], '振込') !== false || strpos($aCsvData[$i][7], 'フリコミ') !== false)) {
                $aTmp['process_code'] = Common::toHankaku($aCsvData[$i][7]);

                // if (!preg_match('/'.$sMc.'-/', $aTmp['process_code'])) {
                //     $aTmp['process_code'] = '要チェック';
                // }

                $aTmp['process_code_org'] = $aCsvData[$i][7];
                $aTmp['amount'] = intval($aCsvData[$i][9]);
                $aTmp['remainder'] = $aCsvData[$i][10];
                if (preg_match('/ /', $aTmp['process_code'])) {
                    $aTmp2 = explode(' ', $aTmp['process_code']);
                    foreach ($aTmp2 as $sValue) {
                        if (preg_match('/D'.$sMc.'-/', $sValue)) {
                            $aTmp['process_code'] = $sValue;
                            break;
                        }
                    }
                }

                if ($aTmp['process_code'] !== '要チェック') {
                    $aTmp['process_code'] = preg_replace('/[^A-Za-z0-9]/', '', $aTmp['process_code']);
                }

                // ユニークID付与
                $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']).sprintf('%04d', $dateCount[$aTmp['date']]);
                // $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']).strval($deposit_manage_id);
                // $deposit_manage_id--;
                // $aTmp['deposit_manage_id'] = str_replace('/', '', $aTmp['date']);
                // for ($i = 1000; $i >= 1; $i--) {
                //     $aTmp['deposit_manage_id'] .= strval($i);
                // }

                $aRequestCsvData[] = $aTmp;
            }
        }

        return array_reverse($aRequestCsvData);
    }
}
