import { SERVER_URL, SCRIPT_PATH } from "./serverDef.js";

export class DataBaseAccessProxy {

  // ユーザ認証
  static async DoAuthenticate(userName, password) {

    let params = new URLSearchParams();
    params.append("username", userName);
    params.append("password", password);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "doAuthenticate.php", params);
    console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

  // ユーザ追加
  static async AddUser(userName, password) {

    let params = new URLSearchParams();
    params.append("username", userName);
    params.append("password", password);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "addUser.php", params);
    console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

  // チャットリスト取得
  static async GetChatList() {

    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "getChatList.php", null);
    //console.log(result.data.channelList);
    return result.data.monsterList;
  }

  // チャット投稿
  // chatText       :チャットテキスト
  static async PostChatText(chatText) {

    let params = new URLSearchParams();
    params.append("chattext", chatText);
    
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "postChatText.php", params)
    //console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

}