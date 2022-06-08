// SpeechRecognitionの準備
SpeechRecognition = webkitSpeechRecognition || SpeechRecognition;
const recognition = new SpeechRecognition()
recognition.lang = 'ja-JP' // 言語コード
recognition.interimResults = true // 認識途中の結果取得

// 確定テキスト
let text = ""

// 発話検出時に呼ばれる
recognition.onresult = (event) => {
  let utterance = event.results[0][0].transcript
  output.innerHTML = text + utterance + "<br>" // 認識途中の結果表示
  if (event.results[0].isFinal) text = output.innerHTML // 認識完了時
}

// 終了時に呼ばれる
recognition.onend = (event) => {
  // 音声認識の開始
  recognition.start()
}

// 音声認識の開始
output.innerHTML = "文字起こし開始...<br>"
text = output.innerHTML
recognition.start()