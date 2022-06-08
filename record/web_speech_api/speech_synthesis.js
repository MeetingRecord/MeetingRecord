// ボタンクリック時に呼ばれる
button.addEventListener("click", function () {
  let utterance = new SpeechSynthesisUtterance()
  utterance.text = "こんにちは" // テキスト
  utterance.lang = "ja-JP" // 言語コード
  utterance.rate = 1.5 // 速度 (0.1〜10、初期値:1)
  utterance.pitch = 0.75 // ピッチ（0〜2、初期値:1）
  utterance.volume = 1 // 音量(0〜1、初期値1)
  speechSynthesis.speak(utterance)
})

console.log('hello');