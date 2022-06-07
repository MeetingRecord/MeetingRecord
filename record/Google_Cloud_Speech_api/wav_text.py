import os
import io
from google.cloud import speech
os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = 'hogehoge.json'


# Instantiates a client
client = speech.SpeechClient()
### 音声データを指定
speech_file = '../wav/hello.wav'

### rb(read binary)でデータを読み込む
with io.open(speech_file, 'rb') as f:
    content = f.read()

### RecognitionAudioにデータを渡す
audio = speech.RecognitionAudio(content=content)

config = speech.RecognitionConfig(
    ### encodeでエラーが出たのでENCODING_UNSPECIFIEDに変更
    encoding=speech.RecognitionConfig.AudioEncoding.ENCODING_UNSPECIFIED,
    sample_rate_hertz=16000,
    language_code="ja-JP",
)

### 音声を抽出
response = client.recognize(config=config, audio=audio)

### 抽出結果をprintで表示
for result in response.results:
    print("Transcript: {}".format(result.alternatives[0].transcript))