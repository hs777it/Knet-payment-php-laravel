<?php

namespace Mgcoder\Knet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class KnetController extends Controller
{

  protected $TranTrackid;
  protected $TranportalId;
  protected $TranportalPassword;
  protected $TermResourceKey;
  protected $Action;
  protected $Langid;
  protected $Currency;
  protected $TranAmount;
  protected $ResponseUrl;
  protected $ErrorUrl;
  protected $TestUrl;
  protected $Url;
  protected $ReqUdf1 = "udf1=Test1";
  protected $ReqUdf2 = "udf2=Test2";
  protected $ReqUdf3 = "udf3=Test3";
  protected $ReqUdf4 = "udf4=Test4";
  protected $ReqUdf5 = "udf5=Test5";

  public function __construct(array $options = [])
  {
    foreach ($options as $key => $val) {
        $this->{$key} = $val;
    }

    $this->TranportalId = config('knet.TranportalId');
    $this->TranportalPassword = config('knet.TranportalPassword');
    $this->TermResourceKey = config('knet.TermResourceKey');
    $this->Action = config('knet.Action');
    $this->Langid = config('knet.Langid');
    $this->Currency = config('knet.Currency');
    $this->ResponseUrl = config('knet.ResponseUrl');
    $this->ErrorUrl = config('knet.ErrorUrl');
    $this->TestUrl = config('knet.TestUrl');
    $this->TranTrackid = Str::random(11);

    $this->initialParam();
  }

  public function knetRequest($param)
  {
    $param = $this->encrypt($param);
    $headerUrl = $this->TestUrl;
    return header("Location: ".$headerUrl."?param=paymentInit"."&trandata=".$param);
  }

  private function initialParam()
  {
    $this->setReqTranportalId();
    $this->setReqTranportalPassword();
    $this->setReqAction();
    $this->setReqLangid();
    $this->setReqCurrency();
    $this->setReqAmount();
    $this->setReqResponseUrl();
    $this->setReqErrorUrl();
    $this->setReqTrackId();

    $param = $this->ReqTranportalId."&".$this->ReqTranportalPassword."&".$this->ReqAction."&".$this->ReqLangid."&".$this->ReqCurrency."&".$this->ReqAmount."&".$this->ReqResponseUrl."&".$this->ReqErrorUrl."&".$this->ReqTrackId."&".$this->ReqUdf1."&".$this->ReqUdf2."&".$this->ReqUdf3."&".$this->ReqUdf4."&".$this->ReqUdf5;
    $this->knetRequest($param);
  }

  private function encrypt($param)
  {
    $encrypt = $this->encryptAES($param,$this->TermResourceKey)."&tranportalId=".$this->TranportalId."&responseURL=".$this->ResponseUrl."&errorURL=".$this->ErrorUrl;
    return $encrypt;
  }

  //
  public function setReqTranportalId()
  {
    $this->ReqTranportalId = "id=".$this->TranportalId;
  }

  public function getReqTranportalId()
  {}

  //
  public function setReqTranportalPassword()
  {
    $this->ReqTranportalPassword = "password=".$this->TranportalPassword;
  }

  public function getReqTranportalPassword()
  {}

  //
  public function setReqAction()
  {
    $this->ReqAction = "action=".$this->Action;
  }

  public function getReqAction()
  {}

  //
  public function setReqLangid()
  {
    $this->ReqLangid = "langid=".$this->Langid;
  }

  public function getReqLangid()
  {}

  //
  public function setReqCurrency()
  {
    $this->ReqCurrency = "currencycode=".$this->Currency;
  }

  public function getReqCurrency()
  {}

  //
  public function setReqAmount()
  {
    $this->ReqAmount = "amt=".$this->TranAmount;
  }

  public function getReqAmount()
  {}

  //
  public function setReqResponseUrl()
  {
    $this->ReqResponseUrl = "responseURL=".$this->ResponseUrl;
  }

  public function getReqResponseUrl()
  {}

  //
  public function setReqErrorUrl()
  {
    $this->ReqErrorUrl = "errorURL=".$this->ErrorUrl;
  }

  public function getReqErrorUrl()
  {}

  //
  public function setReqTrackId()
  {
    $this->ReqTrackId = "trackid=".$this->TranTrackid;
  }

  public function getReqTrackId()
  {}

  private function encryptAES($str,$key)
  {
    $str = $this->pkcs5_pad($str);
    $encrypted = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $key);
    $encrypted = base64_decode($encrypted);
    $encrypted = unpack('C*', ($encrypted));
    $encrypted = $this->byteArray2Hex($encrypted);
    $encrypted = urlencode($encrypted);
    return $encrypted;
  }

  function pkcs5_pad($text)
  {
	  $blocksize = 16;
	  $pad = $blocksize - (strlen($text) % $blocksize);
	  return $text . str_repeat(chr($pad), $pad);
  }

  function byteArray2Hex($byteArray)
  {
    $chars = array_map("chr", $byteArray);
    $bin = join($chars);
    return bin2hex($bin);
  }

  public function GetHandlerResponse()
  {
    session()->put(['knet_status' => 'success']);
    return redirect(url('checkout'));
  }

  public function Error()
  {
    session()->put(['knet_status' => 'failed']);
    return redirect(url('checkout'));
  }

}
?>
