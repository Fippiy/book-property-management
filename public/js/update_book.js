$(function() {
  // タイトル名更新
  $(".book-title").on("keyup", function() {
    var title_text = $(this).val();
    if (title_text.length == 0) {
      $('.aftertitle').html("<span class='alert_title'>タイトルを入力して下さい。</span>");
    } else {
      $('.aftertitle').html(title_text);
    }
  });

  // --画像処理--
  // 画像削除
  $(".delete-picture").click(function() {
    $('.delete-picture').after("<input type='hidden' id='no-picture' name='picture' value='no-picture'>");
    $('.afterimage').html('<span class="form-input__picture--text">写真が削除されます</span>');
  });
  // 画像追加処理
  // アップロードするファイルを選択
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];
    // 画像以外は処理を停止
    if (! file.type.match('image.*')) {
      // クリア
      $(this).val('');
      $('.afterimage').html('');
      return;
    }

    // 画像表示
    var reader = new FileReader();
    reader.onload = function() {
      var img_src = $('<img class="">').attr('src', reader.result);
      $('.afterimage').html(img_src);
    }
    reader.readAsDataURL(file);
    // 削除フラグがある場合は解除
    $('#no-picture').remove();
  });
});
