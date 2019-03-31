<?php

use Aws\S3\S3Client;

function picture_check($ext,$error) {
  if(strtolower($ext) !== 'png' && strtolower($ext) !== 'jpg' && strtolower($ext) !== 'gif'){
      echo '画像以外のファイルが指定されています。画像ファイル(png/jpg/jpeg/gif)を指定して下さい';
      exit();
  } elseif ($error == 1){
    echo '画像アップロードでエラーが発生しました';
    exit();
  }
}

function s3settings(){
  //S3関連共通処理
  //バケット名を指定
  $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');
  //リージョン名指定
  $region = env('AWS_DEFAULT_REGION');
  //S3clientのインスタンス生成
  $s3client = S3Client::factory([
      'credentials' => [
          'key' => env('AWS_ACCESS_KEY_ID'),
          'secret' => env('AWS_SECRET_ACCESS_KEY'),
      ],
      'region' => $region,
      'version' => 'latest',
  ]);

  // 結果を引き渡し
  return ['s3client'=>$s3client,'bucket'=>$bucket,'region'=>$region];
}

// 画像アップロード処理
function picture_upload($directory,$name,$tmp_name,$ext,$s3) {
  //読み込みの際のキーとなるS3上のファイルパスを作る
  $tmp_replace_name = str_replace('tmp/','',$tmp_name);
  $new_filename = $directory."/".$tmp_replace_name.'.'.$ext;
  //アップロードするファイルを用意
  $image = fopen($tmp_name,'rb');
  //S3画像のアップロード
  $result = $s3["s3client"]->putObject([
    'ACL' => 'public-read',
    'Bucket' => $s3["bucket"],
    'Key' => $new_filename,
    'Body' => $image,
    'ContentType' => mime_content_type($tmp_name),
  ]);
  // eval(\Psy\sh());
  // 画像読み取り用のパスを返す
  $path = $result['ObjectURL'];
  return $path;
}

function picture_delete($directory,$book,$s3) {
  // 画像削除用キー生成
  $book_pass = $book["picture"];// DB保存パス
  $bucket = $s3["bucket"];//バケット名
  $region = $s3["region"];//リージョン名
  $delete_pass = "{.*".$bucket.".s3.".$region.".amazonaws.com/}";//削除アドレス部生成
  $delete_key = preg_replace("$delete_pass", "", $book_pass);// DB保存パスから$delete_pass部を削除
  //削除キー指定によるS3データ削除実施
  $result = $s3["s3client"]->deleteObject([
    'Bucket' => $s3["bucket"],
    'Key' => $delete_key
  ]);
}
