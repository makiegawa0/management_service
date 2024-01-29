<?php

namespace App\Services;

use Carbon\Carbon;

class Helper
{
  /**
   * Generate a valid unique random string.
   *
   * @throws Exception
   */
  public static function generateUniqueRandomStr(?callable $validator = null, int $iPrefixNum = 8, $sErrorMessage = '', int $iErrorCode = 0)
  {
      $sUniqueCode = '';
      if (!$validator) {
          throw new \Exception($sErrorMessage, $iErrorCode);
      }
      for ($iCounter = 0; $iCounter < 100; $iCounter++) {
        // self::getRandStr($iPrefixNum)
        $sUniqueCode = $validator(\Str::random($iPrefixNum));
        if ($sUniqueCode !== '') {
          return $sUniqueCode;
        }
      }
      throw new \Exception($sErrorMessage, $iErrorCode);
  }

  /**
   * Generate a unique random string.
   */
  public static function getRandStr($iPrefixNum = 8)
  {
      $sCode = '';
      $sCharacters = 'ABCEFGHJKLMNPQRTUVXY34679';
      $aStr = str_split($sCharacters);
      for ($i = 0; $i < $iPrefixNum; $i++) {
          $iKey = array_rand($aStr, 1);
          $sCode .= $aStr[$iKey];
      }

      return $sCode;

      // $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      // $charactersLength = strlen($characters);
      // $randomString = '';
      // for ($i = 0; $i < $length; $i++) {
      //     $randomString .= $characters[rand(0, $charactersLength - 1)];
      // }
      // return $randomString;
  }

    /**
     * Display a given date in the active user's timezone.
     *
     * @param mixed $date
     * @param string $timezone
     * @return mixed
     */
    public function displayDate($date, string $timezone = null)
    {
      if (!$date) {
        return null;
      }

      return Carbon::parse($date)->copy()->setTimezone($timezone);
    }

    // public function isPro(): bool
    // {
    //     return class_exists(\Sendportal\Pro\SendportalProServiceProvider::class);
    // }
}
